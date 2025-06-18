@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4 class="mb-3">Return Request Details</h4>

    <div class="card">
        <div class="card-body">
            <p><strong>Order Code:</strong> {{ $request->order->order_code }}</p>
            <p><strong>Status:</strong>
                <span class="badge bg-{{ $request->status === 'approved' ? 'success' : ($request->status === 'rejected' ? 'danger' : 'warning') }}">
                    {{ ucfirst($request->status) }}
                </span>
            </p>
            <p><strong>Reason:</strong> {{ $request->reason }}</p>

            @if ($request->evidence_path)
                <p><strong>Submitted Photo:</strong><br>
                    <img src="{{ asset('storage/' . $request->evidence_path) }}" width="300" class="rounded border">
                </p>
            @endif

            @if ($request->farmer_response)
                <div class="alert alert-warning mt-3">
                    <strong>Farmer Response:</strong><br>
                    {{ $request->farmer_response }}
                </div>
            @endif

            @if ($request->admin_response)
                <div class="alert alert-info mt-3">
                    <strong>Admin Response:</strong><br>
                    {{ $request->admin_response }}
                </div>
            @endif

            <a href="{{ route('buyer.orders.history') }}" class="btn btn-secondary mt-3">← Back to My Orders</a>
        </div>
    </div>
</div>
@endsection
