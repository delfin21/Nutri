<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class FarmerDashboardController extends Controller
{
    public function index(Request $request)
    {
        $farmerId = Auth::id();

        // Count orders related to this farmer
        $ordersCount = Order::where('farmer_id', $farmerId)->count();

        // Count unread messages for this farmer
        $messagesCount = Message::where('receiver_id', $farmerId)
            ->where('is_read', false)
            ->count();

        // Calculate total sales amount for this farmer
        $totalSales = Order::where('farmer_id', $farmerId)
            ->where('status', 'completed')
            ->sum('total_price');

        return response()->json([
            'orders' => $ordersCount,
            'messages' => $messagesCount,
            'sales' => $totalSales,
        ]);
    }
}
