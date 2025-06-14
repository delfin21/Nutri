<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FarmerNotificationController extends Controller
{
    // ✅ Mobile API endpoint
    public function farmerIndex(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $notifications = $user->notifications()->latest()->take(50)->get();

        return response()->json([
            'notifications' => $notifications->map(function ($n) {
                return [
                    'id' => $n->id,
                    'data' => $n->data,
                    'read_at' => $n->read_at,
                    'created_at' => $n->created_at->toDateTimeString(),
                ];
            }),
        ]);
    }

    // ✅ Web route view
    public function index(Request $request)
    {
        $filter = $request->query('filter');

        $notifications = Auth::user()
            ->notifications()
            ->when($filter, fn($q) => $q->where('data->type', $filter))
            ->latest()
            ->paginate(10);

        return view('farmer.notifications.index', compact('notifications'));
    }

    public function markAll(Request $request)
    {
        Auth::user()->unreadNotifications->markAsRead();

        return redirect()->route('farmer.notifications.index', $request->query())
            ->with('success', 'All notifications marked as read.');
    }
}
