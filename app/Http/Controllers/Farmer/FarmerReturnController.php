<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReturnRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\AdminAlertNotification;
use App\Notifications\ReturnRequestResolved;


class FarmerReturnController extends Controller
{
    public function index()
    {
        $returns = ReturnRequest::with('order', 'buyer')
            ->whereHas('order', function ($q) {
                $q->where('farmer_id', auth()->id());
            })
            ->latest()
            ->get();

        return view('farmer.returns.index', compact('returns'));
    }

    public function show($id)
    {
        $request = ReturnRequest::with('order')->whereHas('order', function ($q) {
            $q->where('farmer_id', Auth::id());
        })->findOrFail($id);

        return view('farmer.returns.show', compact('request'));
    }

public function respond(Request $request, $id)
{
    $request->validate([
        'farmer_response' => 'required|string|min:5'
    ]);

    $return = ReturnRequest::whereHas('order', function ($q) {
        $q->where('farmer_id', Auth::id());
    })->findOrFail($id);

    $return->farmer_response = $request->farmer_response;
    $return->responded_at = now();
    $return->save();

    // ✅ Notify all admins
    $admins = User::where('role', 'admin')->get();
    foreach ($admins as $admin) {
        $admin->notify(new AdminAlertNotification([
            'message' => 'Farmer responded to return request for Order ' . $return->order->order_code . '.',
            'icon' => 'bi-chat-left-dots',
            'type' => 'return-rebuttal',
            'link' => route('admin.returns.show', $return->id),
        ]));
    }

    // ✅ Notify the buyer as well
    if ($return->buyer) {
        $return->buyer->notify(new ReturnRequestResolved($return, 'rebuttal'));
    }

    return redirect()->route('farmer.dashboard')->with('success', 'Your response was submitted.');
}

}
