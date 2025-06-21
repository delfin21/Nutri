@extends('layouts.app')

@section('title', 'Return / Refund Request')

@section('content')
<div class="container py-4">
    <h3 class="text-success mb-4">Request Return / Refund</h3>

    <div class="card">
        <div class="card-body">
<form method="POST" action="{{ route('buyer.returns.store', $order->id) }}" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label class="form-label fw-semibold">Product Being Returned</label>
        <div class="d-flex align-items-center border rounded p-2 bg-light">
            <img src="{{ asset('storage/' . $order->product->image) }}" alt="Product Image" width="80" height="80" class="rounded me-3">
            <div>
                <strong>{{ $order->product->name }}</strong><br>
                Quantity: {{ $order->quantity }} {{ $order->product->stock_unit }}<br>
                Ordered on: {{ $order->created_at->format('F d, Y') }}
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Order Code</label>
        <input type="text" class="form-control" value="{{ $order->order_code }}" disabled>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Reason for Return <span class="text-danger">*</span></label>
        <textarea name="reason" class="form-control" rows="4" required>{{ old('reason') }}</textarea>
        @error('reason') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Delivery Tracking Code <small class="text-muted">(Optional)</small></label>
        <input type="text" name="tracking_code" class="form-control" placeholder="e.g., LALAMOVE #12345">
        @error('tracking_code') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Preferred Resolution <span class="text-danger">*</span></label>
        <select name="resolution_type" class="form-select" required>
            <option value="">-- Select --</option>
            <option value="refund">Refund</option>
            <option value="replacement">Replacement</option>
            <option value="store_credit">Store Credit</option>
        </select>
        @error('resolution_type') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Upload Photo Evidence <span class="text-danger">*</span></label>
        <input type="file" name="evidence[]" class="form-control" accept="image/png,image/jpeg" multiple required>
        <small class="text-muted">Accepted: JPG/PNG • You may upload multiple files (max 2MB each).</small>
        @error('evidence') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="declaration" id="declaration" required>
        <label class="form-check-label" for="declaration">
            I confirm that this return request is made in good faith and understand that false claims may result in account penalties.
        </label>
        @error('declaration') <br><small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="alert alert-warning small">
        <strong>Reminder:</strong> Return requests must be submitted within <strong>24 hours</strong> of delivery.
    </div>

    <div class="d-grid">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmSubmitModal">
            Submit Request
        </button>
    </div>

    <div class="modal fade" id="confirmSubmitModal" tabindex="-1" aria-labelledby="confirmSubmitModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Return Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to submit this return request? You will not be able to edit it afterward.
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Yes, Submit</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</form>
        </div>
    </div>
</div>
@endsection
