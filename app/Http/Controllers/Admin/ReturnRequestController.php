<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReturnRequest;
use App\Notifications\ReturnRequestResolved;
use App\Models\StoreCredit;

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

    // Approve the return request (Refund, Replacement, Store Credit)
    public function approve($id)
    {
        $request = ReturnRequest::with('buyer', 'order.product')->findOrFail($id);

        $order = $request->order;

        // ✅ Update return request
        $request->status = 'approved';

        // Mark what was actually done (can be same as requested or different if you add manual override later)
        $request->final_resolution_action = $request->resolution_type;

        $request->admin_response = 'Approved by admin for ' . ucfirst($request->resolution_type) . ' on ' . now()->format('M d, Y h:i A');

        $request->resolved_at = now();
        $request->save();

        // ✅ Handle based on resolution type
        if ($order) {
            switch ($request->resolution_type) {
                case 'refund':
                    $order->status = 'returned/refund';
                    break;

                case 'replacement':
                    $order->status = 'replacement_arranged';
                    break;

                case 'store_credit':
                    $order->status = 'store_credit_issued';
                    break;

                default:
                    $order->status = 'returned/refund';
            }

            // ✅ Restore stock only if deducted previously
            if ($order->stock_deducted && $order->product) {
                $order->product->stock += $order->quantity;
                $order->product->save();
                $order->stock_deducted = false;
            }

            if ($request->resolution_type === 'store_credit') {
            StoreCredit::create([
                'buyer_id' => $request->buyer_id,
                'amount' => $request->order->total_price,
                'description' => 'Store credit issued for return of Order ' . $request->order->order_code,
            ]);
}

            $order->save();
        }

        // ✅ Notify buyer
        if ($request->buyer) {
            $request->buyer->notify(new ReturnRequestResolved($request, 'approved'));
        }

        return redirect()->route('admin.returns.index')->with('success', 'Return request approved and order updated.');
    }

    // Reject the return request with reason
    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_response' => 'required|string|min:5',
        ]);

        $returnRequest = ReturnRequest::with('buyer', 'order')->findOrFail($id);

        $returnRequest->status = 'rejected';
        $returnRequest->admin_response = $request->admin_response;
        $returnRequest->resolved_at = now();
        $returnRequest->save();

        if ($returnRequest->order && $returnRequest->order->status === 'shipped') {
            $returnRequest->order->status = 'completed';
            $returnRequest->order->save();
        }

        if ($returnRequest->buyer) {
            $returnRequest->buyer->notify(new ReturnRequestResolved($returnRequest, 'rejected'));
        }

        return redirect()->route('admin.returns.index')->with('success', 'Return request rejected.');
    }

    public function markReplacementSent(Request $request, $id)
    {
        $request->validate([
            'replacement_tracking_code' => 'required|string|max:255',
        ]);

        $return = ReturnRequest::findOrFail($id);
        $return->replacement_tracking_code = $request->replacement_tracking_code;
        $return->final_resolution_action = 'replacement';
        $return->status = 'approved';
        $return->resolved_at = now();
        $return->admin_response = 'Replacement sent with tracking code: ' . $request->replacement_tracking_code;
        $return->save();

        // Optional: notify buyer
        if ($return->buyer) {
            $return->buyer->notify(new ReturnRequestResolved($return, 'replacement'));
        }

        return redirect()->back()->with('success', 'Replacement marked as sent.');
    }

}
