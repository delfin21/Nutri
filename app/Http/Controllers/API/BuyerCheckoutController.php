<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BuyerCheckoutController extends Controller
{
    public function checkout(Request $request)
{
    $user = $request->user();

    $validated = $request->validate([
        'buyer_name' => 'required|string',
        'buyer_phone' => 'required|string',
        'buyer_address' => 'required|string',
        'buyer_region' => 'required|string',
        'buyer_city' => 'required|string',
        'buyer_postal_code' => 'required|string',
        'items' => 'required|array',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|numeric|min:1',
        'payment_method' => 'required|string',
    ]);

    DB::beginTransaction();

    try {
        $totalAmount = 0;

        foreach ($validated['items'] as $item) {
            $product = \App\Models\Product::findOrFail($item['product_id']);
            $total = $product->price * $item['quantity'];
            $totalAmount += $total;

            $order = Order::create([
                'order_code' => 'ORD-' . strtoupper(Str::random(10)),
                'buyer_id' => $user->id,
                'farmer_id' => $product->farmer_id, // ✅ Make sure this is passed
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
                'total_price' => $total,
                'buyer_name' => $validated['buyer_name'],
                'buyer_phone' => $validated['buyer_phone'],
                'buyer_address' => $validated['buyer_address'],
                'buyer_region' => $validated['buyer_region'],
                'buyer_city' => $validated['buyer_city'],
                'buyer_postal_code' => $validated['buyer_postal_code'],
                'payment_method' => $validated['payment_method'],
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::commit();

        return response()->json([
            'message' => 'Order placed successfully',
            'total_amount' => $totalAmount
        ], 200);

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Checkout failed: ' . $e->getMessage());
        return response()->json(['error' => 'Checkout failed'], 500);
    }
}
}

