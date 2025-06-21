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
                <span class="badge bg-{{ $request->status === 'pending' ? 'warning text-dark' : ($request->status === 'approved' ? 'success' : 'danger') }}">
                    {{ ucfirst($request->status) }}
                </span>
            </p>

            @if ($request->resolution_type)
                <p><strong>Resolution Type:</strong> 
                    {{ ucfirst(str_replace('_', ' ', $request->resolution_type)) }} 
                    @if ($request->resolution_type === 'replacement')
                        <span class="badge bg-info text-dark">Arrange Replacement</span>
                    @elseif ($request->resolution_type === 'store_credit')
                        <span class="badge bg-secondary">Issue Store Credit</span>
                    @elseif ($request->resolution_type === 'refund')
                        <span class="badge bg-success">Refund + Restock</span>
                    @endif
                </p>
            @endif

            @if ($request->tracking_code)
                <p><strong>Tracking Code:</strong> {{ $request->tracking_code }}</p>
            @endif

            <p><strong>Reason:</strong><br>{{ $request->reason }}</p>

            {{-- 🧾 Buyer Evidence --}}
            @php
                $buyerPhotos = is_array($request->evidence_path)
                    ? $request->evidence_path
                    : json_decode($request->evidence_path, true);
            @endphp

            @if ($buyerPhotos)
                <p><strong>Buyer Evidence:</strong></p>
                <div class="d-flex flex-wrap gap-3">
                    @foreach ($buyerPhotos as $photo)
                        <a href="{{ asset('storage/' . $photo) }}" target="_blank">
                            <img src="{{ asset('storage/' . $photo) }}" width="180" class="img-thumbnail border">
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- 👨‍🌾 Farmer Response --}}
            @if ($request->farmer_response)
                <div class="mt-4">
                    <strong>Farmer Response:</strong>
                    <div class="alert alert-warning mt-2">
                        {{ $request->farmer_response }}<br>
                        <small class="text-muted">Submitted {{ \Carbon\Carbon::parse($request->responded_at)->diffForHumans() }}</small>
                    </div>

                    {{-- 👨‍🌾 Farmer Evidence --}}
                    @php
                        $farmerPhotos = is_array($request->farmer_evidence_path)
                            ? $request->farmer_evidence_path
                            : json_decode($request->farmer_evidence_path, true);
                    @endphp

                    @if ($farmerPhotos)
                        <p><strong>Farmer Evidence:</strong></p>
                        <div class="d-flex flex-wrap gap-3">
                            @foreach ($farmerPhotos as $photo)
                                <a href="{{ asset('storage/' . $photo) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $photo) }}" width="180" class="img-thumbnail border">
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

            {{-- 🧑‍⚖️ Admin Response --}}
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

        <!-- If resolution is replacement -->
        @if ($request->status === 'pending' && $request->resolution_type === 'replacement')
            <form method="POST" action="{{ route('admin.returns.markReplacementSent', $request->id) }}" class="mt-3">
                @csrf
                <div class="mb-2">
                    <label for="replacement_tracking_code" class="form-label">Replacement Delivery Tracking Code</label>
                    <input type="text" name="replacement_tracking_code" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-truck me-1"></i> Mark as Sent
                </button>
            </form>
        @endif


        <!-- Reject Modal Trigger -->
        <button class="btn btn-danger ms-2" data-bs-toggle="modal" data-bs-target="#rejectModal">
            <i class="bi bi-x-circle me-1"></i> Reject
        </button>
    @endif

    @if ($request->final_resolution_action)
    <p><strong>Final Resolution:</strong> 
        {{ ucfirst(str_replace('_', ' ', $request->final_resolution_action)) }}
    </p>
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
