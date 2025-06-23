<div class="pb-4">
    <h4 class="text-warning mb-4">
        <i class="bi bi-arrow-counterclockwise me-2"></i>My Return Requests
    </h4>

    @forelse ($returns as $request)
        <div class="card mb-3">
            <div class="card-body d-flex justify-content-between">
                <div>
                    <p class="mb-1"><strong>Order Code:</strong> {{ $request->order->order_code }}</p>
                    <p class="mb-1"><strong>Reason:</strong> {{ \Illuminate\Support\Str::limit($request->reason, 80) }}</p>
                    <p class="mb-2"><strong>Resolution:</strong>
                        @switch($request->resolution_type)
                            @case('refund') 💰 Refund @break
                            @case('replacement') 📦 Replacement @break
                            @case('store_credit') 🏷 Store Credit @break
                            @default N/A
                        @endswitch
                    </p>
                    <a href="{{ route('buyer.returns.show', $request->id) }}" class="btn btn-outline-primary btn-sm">
                        View Full Details
                    </a>
                </div>
                <div class="text-end">
                    <span class="badge bg-{{ 
                        $request->status === 'approved' ? 'success' : 
                        ($request->status === 'rejected' ? 'danger' : 'warning text-dark') 
                    }}">
                        {{ ucfirst($request->status) }}
                    </span>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info text-center">No return requests submitted yet.</div>
    @endforelse
</div>
