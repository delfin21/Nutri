@extends('layouts.admin')

@section('title', 'Return Request Details')

@section('content')
<div class="container py-4">
    <h4 class="mb-3 text-white">Return Request #{{ $request->id }}</h4>

    <div class="card mb-4">
        <div class="card-body">
        <p><strong>Order Code:</strong> {{ $request->order->order_code ?? 'N/A' }}</p>
        <p><strong>Buyer:</strong> {{ $request->buyer->name ?? 'Unknown' }}</p>
        <p><strong>Submitted:</strong> {{ $request->created_at->format('M d, Y h:i A') }}</p>
        <p><strong>Status:</strong>
            <span class="badge bg-{{ $request->status === 'pending' ? 'warning' : ($request->status === 'approved' ? 'success' : 'danger') }}">
                {{ ucfirst($request->status) }}
            </span>
        </p>

        <p><strong>Reason:</strong><br>{{ $request->reason }}</p>

        @if ($request->evidence_path)
            <p><strong>Evidence:</strong></p>
            <a href="{{ asset('storage/' . $request->evidence_path) }}" target="_blank">
                <img src="{{ asset('storage/' . $request->evidence_path) }}" class="img-fluid rounded border" style="max-width: 300px;">
            </a>
        @endif

        @if ($request->farmer_response)
            <div class="mt-4">
                <strong>Farmer Response:</strong>
                <div class="alert alert-warning mt-2">
                    {{ $request->farmer_response }}<br>
                    <small class="text-muted">Submitted {{ \Carbon\Carbon::parse($request->responded_at)->diffForHumans() }}</small>
                </div>
            </div>
        @endif

        @if ($request->admin_response)
            <div class="mt-4">
                <strong>Admin Response:</strong>
                <div class="alert alert-info mt-2">
                    {{ $request->admin_response }}
                    <br><small class="text-muted">Resolved {{ \Carbon\Carbon::parse($request->resolved_at)->diffForHumans() }}</small>
                </div>
            </div>
        @endif

        </div>
    </div>

    @if ($request->status === 'pending')
        <!-- Approve Button -->
        <form method="POST" action="{{ route('admin.returns.approve', $request->id) }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-success" onclick="return confirm('Approve this return request?')">
                <i class="bi bi-check-circle me-1"></i> Approve
            </button>
        </form>

        <!-- Reject Modal Trigger -->
        <button class="btn btn-danger ms-2" data-bs-toggle="modal" data-bs-target="#rejectModal">
            <i class="bi bi-x-circle me-1"></i> Reject
        </button>
    @endif

    <a href="{{ route('admin.returns.index') }}" class="btn btn-secondary mt-3">← Back to List</a>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.returns.reject', $request->id) }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Reject Return Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="admin_response" class="form-label">Rejection Reason</label>
                    <textarea name="admin_response" id="admin_response" class="form-control" rows="3" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Reject Request</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
