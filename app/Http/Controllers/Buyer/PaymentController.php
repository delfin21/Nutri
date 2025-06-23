<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Payment;
use App\Notifications\FarmerOrderPlacedNotification;
use App\Notifications\AdminAlertNotification;

class PaymentController extends Controller
{
    public function showForm()
    {
        return view('buyer.payment.form');
    }

    public function processForm(Request $request)
    {
        $request->validate([
            'contact_email' => 'required|email',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'region' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'payment_method' => 'required|in:qr',
        ]);

        session([
            'checkout_email' => $request->contact_email,
            'checkout_first_name' => $request->first_name,
            'checkout_last_name' => $request->last_name,
            'checkout_phone' => $request->phone,
            'checkout_address' => $request->address,
            'checkout_city' => $request->city,
            'checkout_region' => $request->region,
            'checkout_postal_code' => $request->postal_code,
        ]);

        return redirect()->route('buyer.payment.qrScan');
    }

    public function qrScan()
    {
        return view('buyer.payment.qr-scan');
    }

    public function verifyUpload()
    {
        return view('buyer.payment.verify-upload');
    }

    public function mockSuccess(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:gcash,paymaya,card',
            'qr_reference' => 'nullable|string',
            'qr_name' => 'nullable|string',
            'qr_mobile' => 'nullable|string',
            'qr_proof' => 'nullable|image|max:2048',
        ]);

        $buyer = Auth::user();
        $cartIds = session('checkout_items', []);
        $cartItems = Cart::with('product')
            ->whereIn('id', $cartIds)
            ->where('buyer_id', $buyer->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('buyer.cart.index')->with('error', 'Cart is empty.');
        }

        $totalAmount = 0;
        $orderIds = [];

        foreach ($cartItems as $item) {
            $product = $item->product;
            $order = Order::create([
                'order_code' => Order::generateStructuredOrderCode($product->user),
                'buyer_id' => $buyer->id,
                'product_id' => $product->id,
                'farmer_id' => $product->farmer_id ?? $product->user_id,
                'quantity' => $item->quantity,
                'price' => $product->price,
                'total_price' => $product->price * $item->quantity,
                'status' => 'Pending',
                'payment_status' => 'paid',
                'buyer_phone' => session('checkout_phone'),
                'buyer_address' => session('checkout_address'),
                'buyer_city' => session('checkout_city'),
                'buyer_region' => session('checkout_region'),
                'buyer_postal_code' => session('checkout_postal_code'),
            ]);

            $orderIds[] = $order->id;
            $totalAmount += $order->total_price;

            $this->notifyUsers($order, $buyer);
        }

        Cart::whereIn('id', $cartIds)->delete();
        session()->forget('checkout_items');

        $proofPath = null;
        if ($request->hasFile('qr_proof')) {
            $filename = 'proof_' . time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $request->file('qr_proof')->getClientOriginalName());
            $proofPath = $request->file('qr_proof')->storeAs('payments', $filename, 'public');



            // Optional debug log
            \Log::info('QR proof uploaded to: ' . $proofPath);
        }


        $payment = Payment::create([
            'intent_id' => 'mock_' . Str::random(10),
            'method' => $request->payment_method,
            'amount' => $totalAmount * 100,
            'status' => 'paid',
            'buyer_id' => $buyer->id,
            'order_ids' => json_encode($orderIds),
            'is_test' => true,
            'response_payload' => json_encode([
                'qr_reference' => $request->qr_reference,
                'qr_name' => $request->qr_name,
                'qr_mobile' => $request->qr_mobile,
                'proof_path' => $proofPath,
            ]),
        ]);

        Order::whereIn('id', $orderIds)->update(['payment_id' => $payment->id]);

        session(['reference' => 'MOCK_REF_' . Str::random(6)]);

        return redirect()->route('buyer.payment.thankYou');
    }

    public function showReceipt(Payment $payment)
    {
        if ($payment->buyer_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $orders = $payment->orders;
        return view('buyer.payment.receipt', compact('payment', 'orders'));
    }

    protected function notifyUsers(Order $order, $buyer)
    {
        if ($farmer = User::find($order->farmer_id)) {
            $farmer->notify(new FarmerOrderPlacedNotification($order));
        }

        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new AdminAlertNotification([
            'message' => 'Order ' . $order->order_code . ' has been placed by ' . $buyer->name,
            'icon' => 'bi-cart-check',
            'link' => route('admin.orders.index', ['highlight_order' => $order->id]),
            'type' => 'order',
        ]));
    }

    public function paymentSuccess()
    {
        return redirect()->route('buyer.payment.thankYou');
    }

    public function paymentFailure()
    {
        return view('buyer.payment.failure');
    }

    public function thankYou()
    {
        $reference = session('reference') ?? 'MOCK_REF_' . Str::random(6);
        return view('buyer.payment.thank-you', compact('reference'));
    }

    public function review()
    {
        $buyNow = session('buy_now');
        $items = [];
        $totalAmount = 0;

        if ($buyNow) {
            $product = Product::withTrashed()->findOrFail($buyNow['product_id']);
            $items[] = [
                'product' => $product,
                'quantity' => $buyNow['quantity'],
                'total' => $product->price * $buyNow['quantity'],
            ];
            $totalAmount = $items[0]['total'];
        } else {
            $items = Cart::with(['product' => fn ($q) => $q->withTrashed()])
                ->where('buyer_id', Auth::id())
                ->get()
                ->map(fn ($item) => [
                    'product' => $item->product,
                    'quantity' => $item->quantity,
                    'total' => $item->product->price * $item->quantity,
                ]);

            $totalAmount = $items->sum('total');
        }

        return view('buyer.payment.review', compact('items', 'totalAmount', 'buyNow'));
    }

    public function checkoutPreview()
    {
        $cartIds = session('checkout_items', []);
        $buyerId = Auth::id();

        $cartItems = Cart::with('product')
            ->whereIn('id', $cartIds)
            ->where('buyer_id', $buyerId)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('buyer.cart.index')->with('error', 'No items to preview.');
        }

        $displayItems = $cartItems->map(function ($item) {
            return [
                'name' => $item->product->name,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
                'total' => $item->product->price * $item->quantity,
            ];
        });

        $totalAmount = $displayItems->sum('total');

        return view('buyer.payment.checkout-preview', [
            'checkout_items' => $displayItems,
            'totalAmount' => $totalAmount,
        ]);
    }
}