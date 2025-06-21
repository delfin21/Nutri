<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class FarmerOrderController extends Controller
{
    // GET /api/farmer/orders
    public function index(Request $request)
{
    $user = $request->user();

    \Log::info('Authenticated farmer ID: ' . $user->id);

    $orders = Order::where('farmer_id', $user->id)
        ->with(['product', 'buyer'])
        ->latest()
        ->get();

    \Log::info('Orders count for farmer ' . $user->id . ': ' . $orders->count());
    \Log::info('Order IDs: ' . $orders->pluck('id')->join(', '));

    $formattedOrders = $orders->map(function ($order) {
        $product = $order->product;
        $buyer = $order->buyer;

        return [
            'id' => $order->id,
            'product_name' => $product?->name ?? 'Unknown',
            'quantity' => $order->quantity,
            'total_price' => $order->total_price ?? ($order->price * $order->quantity),
            'status' => $order->status,
            'buyer_name' => $buyer?->name ?? 'N/A',
            'ordered_at' => $order->created_at->toDateTimeString(),
        ];
    });

    \Log::info('Formatted Orders: ' . json_encode($formattedOrders));

    return response()->json($formattedOrders);
}
    // PUT /api/farmer/orders/{id}
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,to_ship,shipped,completed,canceled',
        ]);

        $order = Order::where('id', $id)
            ->where('farmer_id', $request->user()->id)
            ->firstOrFail();

        $order->status = $request->status;
        $order->save();

        return response()->json(['message' => 'Order status updated successfully.']);
    }
}
