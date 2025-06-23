@extends('layouts.app')

@section('content')
<div class="container text-center py-5">
  <h2 class="mb-3">✅ Payment Submitted</h2>
  <p class="text-muted">
    Thank you! Your order has been placed successfully. We are verifying your payment.
  </p>

  <h5 class="mt-4">Reference Code:</h5>
  <div class="alert alert-success w-50 mx-auto">
    {{ session('reference') ?? 'N/A' }}
  </div>

  <a href="{{ route('buyer.profile.show', ['tab' => 'purchase']) }}" class="btn btn-primary mt-3">
    View My Purchase
  </a>
</div>
@endsection
