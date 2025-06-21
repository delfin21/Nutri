<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReturnRequest;

class FarmerReturnController extends Controller
{
    public function index(Request $request)
    {
        $farmerId = $request->user()->id;

        $returns = ReturnRequest::with('order.product', 'order.buyer')
            ->whereHas('order', function ($query) use ($farmerId) {
                $query->where('farmer_id', $farmerId);
            })
            ->latest()
            ->get();

        return response()->json($returns);
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string',
        ]);

        $return = ReturnRequest::findOrFail($id);
        $return->farmer_reply = $request->reply;
        $return->replied_at = now();
        $return->save();

        return response()->json(['message' => 'Reply submitted']);
    }

    public function approve($id)
    {
        $return = ReturnRequest::findOrFail($id);
        $return->status = 'approved';
        $return->save();

        return response()->json(['message' => 'Return approved']);
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string',
        ]);

        $return = ReturnRequest::findOrFail($id);
        $return->status = 'rejected';
        $return->rejection_reason = $request->reason;
        $return->save();

        return response()->json(['message' => 'Return rejected']);
    }
}
