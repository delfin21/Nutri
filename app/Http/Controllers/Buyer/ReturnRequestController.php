<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Order;
use App\Models\ReturnRequest;
use App\Models\User;
use App\Notifications\ReturnRequestFiled;
use App\Notifications\AdminAlertNotification;

class ReturnRequestController extends Controller
{
    public function create($orderId)
    {
        $order = Order::where('id', $orderId)
                    ->where('buyer_id', Auth::id())
                    ->firstOrFail();

        // Optional: Prevent duplicate return request
        if ($order->returnRequest) {
            return redirect()->back()->with('error', 'You already submitted a return request for this order.');
        }

        return view('buyer.returns.create', compact('order'));
    }

    public function store(Request $request, $orderId)
    {
        $order = Order::where('id', $orderId)
                    ->where('buyer_id', Auth::id())
                    ->firstOrFail();

        $request->validate([
            'reason' => 'required|string|min:10',
            'evidence' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->file('evidence')->store('returns', 'public');

        $returnRequest = ReturnRequest::create([
            'order_id' => $order->id,
            'buyer_id' => Auth::id(),
            'reason' => $request->input('reason'),
            'evidence_path' => $path,
            'status' => 'pending',
        ]);

        // ✅ Notify the farmer
        if ($order->farmer) {
            $order->farmer->notify(new ReturnRequestFiled($returnRequest));
        }

        // ✅ Notify all admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new AdminAlertNotification([
                'message' => 'A return request was filed for Order ' . $order->order_code . ' by ' . Auth::user()->name . '.',
                'icon' => 'bi-box-arrow-in-left',
                'type' => 'return-request',
                'link' => route('admin.returns.show', $returnRequest->id),
            ]));
        }

        return redirect()->route('buyer.orders.history')->with('success', 'Your return request has been submitted and is under review.');
    }

    public function show($id)
    {
        $request = ReturnRequest::with('order.product', 'order.farmer', 'buyer')->findOrFail($id);

        // Optional: Check if the buyer owns the order
        if ($request->buyer_id !== auth()->id()) {
            abort(403);
        }

        return view('buyer.returns.show', compact('request'));
    }

}
