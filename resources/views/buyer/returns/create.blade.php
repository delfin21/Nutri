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
                    <label class="form-label fw-semibold">Order Code</label>
                    <input type="text" class="form-control" value="{{ $order->order_code }}" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Reason for Return <span class="text-danger">*</span></label>
                    <textarea name="reason" class="form-control" rows="4" required>{{ old('reason') }}</textarea>
                    @error('reason') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Upload Photo Evidence <span class="text-danger">*</span></label>
                    <input type="file" name="evidence" class="form-control" required>
                    @error('evidence') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-success">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
