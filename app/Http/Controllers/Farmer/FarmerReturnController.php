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
        'farmer_response' => 'required|string|min:5',
        'farmer_evidence.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $return = ReturnRequest::whereHas('order', function ($q) {
        $q->where('farmer_id', Auth::id());
    })->findOrFail($id);

    $return->farmer_response = $request->farmer_response;
    $return->responded_at = now();

    if ($request->hasFile('farmer_evidence')) {
        $paths = [];
        foreach ($request->file('farmer_evidence') as $file) {
            $paths[] = $file->store('returns/farmer', 'public');
        }
        $return->farmer_evidence_path = $paths;
    }

    $return->save();

    // Notify admins
    $admins = User::where('role', 'admin')->get();
    foreach ($admins as $admin) {
        $admin->notify(new AdminAlertNotification([
            'message' => 'Farmer responded to return request for Order ' . $return->order->order_code . '.',
            'icon' => 'bi-chat-left-dots',
            'type' => 'return-rebuttal',
            'link' => route('admin.returns.show', $return->id),
        ]));
    }

    // Notify buyer
    if ($return->buyer) {
        $return->buyer->notify(new ReturnRequestResolved($return, 'rebuttal'));
    }

    return redirect()->route('farmer.returns.index')->with('success', 'Your response was submitted.');
}


}
