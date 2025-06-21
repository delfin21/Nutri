@extends('layouts.farmer')

@section('title', 'Return Request')

@section('content')
<div class="container py-4">
    <h4 class="mb-3">Return Request from Buyer</h4>

    <div class="card mb-3">
        <div class="card-body">
            <p><strong>Order Code:</strong> {{ $request->order->order_code }}</p>
            <p><strong>Product:</strong> {{ $request->order->product->name ?? 'N/A' }}</p>
            <p><strong>Quantity:</strong> {{ $request->order->quantity }} {{ $request->order->product->stock_unit ?? '' }}</p>

            <p><strong>Preferred Resolution:</strong> 
                <span class="badge bg-primary text-white">{{ ucfirst($request->resolution_type) }}</span>
            </p>

            @if ($request->tracking_code)
                <p><strong>Delivery Tracking Code:</strong> {{ $request->tracking_code }}</p>
            @endif

            <p><strong>Reason for Return:</strong> {{ $request->reason }}</p>

            <p><strong>Status:</strong> 
                <span class="badge bg-{{ $request->status === 'approved' ? 'success' : ($request->status === 'rejected' ? 'danger' : 'warning text-dark') }}">
                    {{ ucfirst($request->status) }}
                </span>
            </p>

            {{-- 🧾 Buyer Evidence --}}
            @php
                $photos = is_array($request->evidence_path)
                    ? $request->evidence_path
                    : json_decode($request->evidence_path, true);
            @endphp

            @if (!empty($photos) && is_array($photos))
                <p><strong>Photo Evidence from Buyer:</strong></p>
                <div class="d-flex flex-wrap gap-3">
                    @foreach ($photos as $photo)
                        <a href="{{ asset('storage/' . $photo) }}" target="_blank">
                            <img src="{{ asset('storage/' . $photo) }}" class="img-thumbnail border" width="180">
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- 👨‍🌾 Farmer Response Evidence --}}
            @if (is_array($request->farmer_evidence_path))
                <p class="mt-4"><strong>Farmer's Photo Evidence:</strong></p>
                <div class="d-flex flex-wrap gap-3">
                    @foreach ($request->farmer_evidence_path as $photo)
                        <a href="{{ asset('storage/' . $photo) }}" target="_blank">
                            <img src="{{ asset('storage/' . $photo) }}" width="180" class="img-thumbnail border">
                        </a>
                    @endforeach
                </div>
            @elseif ($request->farmer_evidence_path)
                <p class="mt-4"><strong>Farmer's Photo Evidence:</strong></p>
                <a href="{{ asset('storage/' . $request->farmer_evidence_path) }}" target="_blank">
                    <img src="{{ asset('storage/' . $request->farmer_evidence_path) }}" class="img-fluid rounded border" style="max-width: 300px;">
                </a>
            @endif
        </div>
    </div>

    {{-- 🧾 RESPONSE FORM --}}
    @if (!$request->farmer_response && $request->status === 'pending')
    <form action="{{ route('farmer.returns.respond', $request->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="delivered_condition" class="form-label">Condition at Time of Delivery</label>
            <select name="delivered_condition" class="form-select" required>
                <option value="">-- Select Condition --</option>
                <option value="Fresh-Sealed">Fresh Sealed Packaged</option>
                <option value="Sealed">Sealed Packaged</option>
                <option value="Others">Others</option>
            </select>
        </div>

        {{-- 📌 Dynamic Prompt Based on Resolution --}}
        @php
            $type = strtolower($request->resolution_type);
            $message = match ($type) {
                'refund' => 'Buyer is requesting a refund. Please explain the condition of the item and why a refund should or should not be issued.',
                'replacement' => 'Buyer wants a replacement. If you agree, state how and when you can ship the replacement product.',
                'store_credit' => 'Buyer prefers store credit. Please explain how you can issue store credit (e.g., discount, future product offer).',
                default => '',
            };
        @endphp

        <div class="alert alert-secondary">
            <strong>Note:</strong> {{ $message }}
        </div>

        <div class="mb-3">
            <label for="farmer_evidence" class="form-label">Upload Photo Evidence</label>
            <input type="file" name="farmer_evidence[]" class="form-control" multiple accept="image/png,image/jpeg">
            <small class="text-muted">You may upload multiple images (JPG/PNG only, up to 2MB each).</small>
        </div>

        <div class="mb-3">
            <label for="farmer_response" class="form-label">Your Response</label>
            <textarea name="farmer_response" id="farmer_response" class="form-control" rows="5" required></textarea>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Submit Rebuttal</button>
        </div>
    </form>
    @else
        <div class="alert alert-info">
            <strong>Your Response:</strong> {{ $request->farmer_response }}
        </div>
    @endif

    <a href="{{ route('farmer.returns.index') }}" class="btn btn-secondary mt-3">Back to List</a>
</div>
@endsection
