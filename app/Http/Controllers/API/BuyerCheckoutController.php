<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;

class BuyerCheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'full_name' => 'required|string',
            'phone' => 'required|string',
            'street' => 'required|string',
            'region' => 'required|string',
            'city' => 'required|string',
            'postal_code' => 'required|string',
            'payment_method' => 'required|string|in:gcash,card,maya,cod'
        ]);

        $product = Product::findOrFail($validated['product_id']);

        $order = Order::create([
            'order_code' => strtoupper(uniqid('ORD')),
            'buyer_id' => $user->id,
            'farmer_id' => $product->farmer_id,
            'product_id' => $product->id,
            'quantity' => $validated['quantity'],
            'price' => $product->price,
            'total_price' => $product->price * $validated['quantity'],
            'status' => 'pending',
            'buyer_name' => $validated['full_name'],
            'buyer_phone' => $validated['phone'],
            'buyer_address' => $validated['street'],
            'buyer_region' => $validated['region'],
            'buyer_city' => $validated['city'],
            'buyer_postal_code' => $validated['postal_code'],
            'payment_method' => $validated['payment_method'],
        ]);

        return response()->json([
            'message' => 'Order placed successfully!',
            'order_id' => $order->id,
            'order_code' => $order->order_code,
        ], 201);
    }
}
