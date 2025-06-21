@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4 class="mb-3">Return Request Details</h4>

    <div class="card">
        <div class="card-body">
            {{-- 🧾 Basic Info --}}
            <p><strong>Order Code:</strong> {{ $request->order->order_code }}</p>
            <p><strong>Status:</strong>
                <span class="badge bg-{{ $request->status === 'approved' ? 'success' : ($request->status === 'rejected' ? 'danger' : 'warning text-dark') }}">
                    {{ ucfirst($request->status) }}
                </span>
            </p>
            <p><strong>Submitted At:</strong> {{ $request->created_at->format('F d, Y h:i A') }}</p>
            <p><strong>Preferred Resolution:</strong> 
                @switch($request->resolution_type)
                    @case('refund')
                        💰 Refund
                        @break
                    @case('replacement')
                        📦 Replacement
                        @break
                    @case('store_credit')
                        🏷 Store Credit
                        @break
                    @default
                        N/A
                @endswitch
            </p>
            @if ($request->tracking_code)
                <p><strong>Delivery Tracking Code:</strong> {{ $request->tracking_code }}</p>
            @endif
            <p><strong>Reason:</strong> {{ $request->reason }}</p>

            {{-- 📸 Buyer Evidence --}}
            @php
                $buyerPhotos = is_array($request->evidence_path)
                    ? $request->evidence_path
                    : json_decode($request->evidence_path, true);
            @endphp

            @if (!empty($buyerPhotos))
                <p><strong>Photo Evidence from You:</strong></p>
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
                    <p><strong>Farmer's Response:</strong></p>
                    <div class="alert alert-info">{{ $request->farmer_response }}</div>

                    {{-- Farmer Evidence --}}
                    @php
                        $farmerPhotos = is_array($request->farmer_evidence_path)
                            ? $request->farmer_evidence_path
                            : json_decode($request->farmer_evidence_path, true);
                    @endphp

                    @if (!empty($farmerPhotos))
                        <p><strong>Photo Evidence from Farmer:</strong></p>
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
                <div class="alert alert-secondary mt-4">
                    <strong>Admin Decision:</strong><br>
                    {{ $request->admin_response }}
                </div>
            @endif

            @if ($request->status === 'approved' && $request->final_resolution_action)
                <p><strong>Final Action Taken:</strong> 
                    {{ ucfirst(str_replace('_', ' ', $request->final_resolution_action)) }}
                </p>
            @endif

            {{-- 💬 Contact Seller --}}
            @if ($request->order->farmer_id)
                <a href="{{ route('buyer.messages.show', $request->order->farmer_id) }}" class="btn btn-outline-success mt-4">
                    <i class="bi bi-chat-dots"></i> Contact Seller
                </a>
            @endif

            <a href="{{ route('buyer.orders.history') }}" class="btn btn-secondary mt-3">← Back to My Orders</a>
        </div>
    </div>
</div>
@endsection
