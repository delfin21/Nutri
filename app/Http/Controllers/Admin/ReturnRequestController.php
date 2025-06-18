<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReturnRequest;
use App\Notifications\ReturnRequestResolved;

class ReturnRequestController extends Controller
{
    // Show all return requests
    public function index(Request $request)
    {
        $query = ReturnRequest::with('order', 'buyer');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('order', fn($q) => $q->where('order_code', 'like', "%$search%"))
                  ->orWhereHas('buyer', fn($q) => $q->where('name', 'like', "%$search%"));
            });
        }

        $requests = $query->latest()->get();

        return view('admin.returns.index', compact('requests'));
    }

    // Show specific return request
    public function show($id)
    {
        $request = ReturnRequest::with('order.product', 'order.farmer', 'buyer')->findOrFail($id);
        return view('admin.returns.show', compact('request'));
    }

    
    // Approve the return request
    public function approve($id)
    {
        $request = ReturnRequest::with('buyer', 'order.product')->findOrFail($id);

        // 1. Update return request
        $request->status = 'approved';
        $request->admin_response = 'Approved by admin on ' . now()->format('M d, Y h:i A');
        $request->resolved_at = now();
        $request->save();

        // 2. Update order status
        if ($request->order) {
            $order = $request->order;
            $order->status = 'returned/refund';

            // ✅ Restore stock only if previously deducted
            if ($order->stock_deducted && $order->product) {
                $order->product->stock += $order->quantity;
                $order->product->save();

                $order->stock_deducted = false; // reset to prevent double-addition
            }

            $order->save();
        }

        // 3. Notify buyer
        if ($request->buyer) {
            $request->buyer->notify(new ReturnRequestResolved($request, 'approved'));
        }

        return redirect()->route('admin.returns.index')->with('success', 'Return request approved and stock restored.');
    }

    // Reject the return request with reason
public function reject(Request $request, $id)
{
    $request->validate([
        'admin_response' => 'required|string|min:5',
    ]);

    $returnRequest = ReturnRequest::with('buyer', 'order')->findOrFail($id);

    // ✅ 1. Update return request record
    $returnRequest->status = 'rejected';
    $returnRequest->admin_response = $request->admin_response;
    $returnRequest->resolved_at = now();
    $returnRequest->save();

    // ✅ 2. Update order status to 'completed' since return was rejected
    if ($returnRequest->order && $returnRequest->order->status === 'shipped') {
        $returnRequest->order->status = 'completed';
        $returnRequest->order->save();
    }

    // ✅ 3. Notify buyer
    if ($returnRequest->buyer) {
        $returnRequest->buyer->notify(new \App\Notifications\ReturnRequestResolved($returnRequest, 'rejected'));
    }

    return redirect()->route('admin.returns.index')->with('success', 'Return request rejected.');
}

}
