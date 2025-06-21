<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReturnRequest;

class ReturnRequestController extends Controller
{
   public function store(Request $request)
{
    $request->validate([
        'order_id' => 'required|exists:orders,id',
        'reason' => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);

    $return = ReturnRequest::create([
        'order_id' => $request->order_id,
        'buyer_id' => $request->user()->id, // ✅ Add this
        'reason' => $request->reason,
        'description' => $request->description,
    ]);

    return response()->json([
        'message' => 'Return request submitted successfully.',
        'data' => $return
    ], 201);
}

}
