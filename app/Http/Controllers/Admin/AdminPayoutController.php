<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FarmerPayout;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PayoutReleasedNotification;

class AdminPayoutController extends Controller
{
    public function index()
    {
        $payouts = FarmerPayout::with('farmer')->latest()->paginate(20);
        return view('admin.payouts.index', compact('payouts'));
    }

    public function verify($id)
    {
        $payout = FarmerPayout::findOrFail($id);
        $payout->is_verified = true;
        $payout->save();

        // Optional: Notify the farmer via email or app
        if ($payout->farmer) {
            $payout->farmer->notify(new PayoutReleasedNotification($payout));
        }

        return back()->with('success', 'Payout has been marked as verified.');
    }
    public function release(Request $request, $id)
{
    $payout = \App\Models\FarmerPayout::findOrFail($id);

    $payout->is_released = true;
    $payout->released_at = now();
    $payout->save();

    // Notify farmer
    Notification::send($payout->farmer, new \App\Notifications\PayoutReleasedNotification($payout));

    return back()->with('success', 'Payout marked as released.');
}

}
