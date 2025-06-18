@extends('layouts.farmer')

@section('title', 'Return Request')

@section('content')
<div class="container py-4">
    <h4 class="mb-3">Return Request from Buyer</h4>

    <div class="card mb-3">
        <div class="card-body">
            <p><strong>Order Code:</strong> {{ $request->order->order_code }}</p>
            <p><strong>Reason for Return:</strong> {{ $request->reason }}</p>
            <p><strong>Status:</strong> <span class="badge bg-warning text-dark">{{ ucfirst($request->status) }}</span></p>
            @if ($request->evidence_path)
                <p><strong>Photo Evidence:</strong></p>
                <img src="{{ asset('storage/' . $request->evidence_path) }}" class="img-fluid" style="max-width: 300px;">
            @endif
        </div>
    </div>

    @if (!$request->farmer_response)
    <form action="{{ route('farmer.returns.respond', $request->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="farmer_response" class="form-label">Your Response</label>
            <textarea name="farmer_response" id="farmer_response" class="form-control" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit Rebuttal</button>
    </form>
    @else
        <div class="alert alert-info">
            <strong>Your Response:</strong> {{ $request->farmer_response }}
        </div>
    @endif

    <a href="{{ route('farmer.dashboard') }}" class="btn btn-secondary mt-3">Back</a>
</div>
@endsection
