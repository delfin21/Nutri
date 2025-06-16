<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class BuyerPurchaseController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $orders = Order::with(['product.user']) // product.user = farmer
            ->where('buyer_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $formattedOrders = $orders->map(function ($order) {
            return [
                'id' => $order->id,
                'farmer' => $order->product->user->name ?? 'Unknown',
                'product_name' => $order->product->name ?? 'Unnamed',
                'weight' => $order->quantity . ' kg',
                'price_per_kg' => (float) $order->price,
                'total_price' => (float) $order->price * $order->quantity,
                'status' => $order->status,
                'image_url' => $order->product->image
                    ? url('storage/' . $order->product->image)
                    : null,
                // ✅ Add these:
                'farmer_id' => $order->product->user_id ?? null,
                'conversation_id' => $order->conversation_id ?? null,
            ];
        });

        return response()->json([
            'orders' => $formattedOrders,
        ]);
    }

    // ✅ Confirm Delivery (PUT /api/buyer/orders/{id}/confirm)
    public function confirmDelivery($id, Request $request)
    {
        $order = Order::where('id', $id)
            ->where('buyer_id', $request->user()->id)
            ->firstOrFail();

        if ($order->status !== 'shipped') {
            return response()->json([
                'message' => 'Cannot confirm delivery. Status must be shipped.'
            ], 400);
        }

        $order->status = 'completed';
        $order->save();

        return response()->json(['message' => 'Order marked as completed.']);
    }

    // ✅ Cancel Order (PUT /api/buyer/orders/{id}/cancel)
    public function cancel($id, Request $request)
    {
        $order = Order::where('id', $id)
            ->where('buyer_id', $request->user()->id)
            ->firstOrFail();

        if (!in_array($order->status, ['pending', 'to_ship'])) {
            return response()->json([
                'message' => 'Cannot cancel after order is shipped.'
            ], 400);
        }

        $order->status = 'canceled';
        $order->save();

        return response()->json(['message' => 'Order canceled.']);
    }
}
