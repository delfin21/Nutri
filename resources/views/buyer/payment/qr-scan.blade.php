@extends('layouts.app')

@section('content')
<div class="container text-center py-5">
  <h2 class="mb-3">📱 Scan to Pay</h2>
  <p class="text-muted mb-4">
    Open your GCash, Maya, or bank app and scan the QR below. After payment, go back and upload your reference on the form.
  </p>

  <div class="d-flex justify-content-center">
    <div class="bg-white p-4 shadow rounded">
      <img src="{{ asset('img/payments/Nutri_Instapay.jpg') }}" alt="NutriApp QR Code"
           style="max-width: 100%; height: auto; width: 400px;">
    </div>
  </div>

  <div class="mt-4">
    <a href="{{ route('buyer.payment.verify') }}" class="btn btn-outline-primary">
      I have paid – Go to Verification
    </a>
  </div>
</div>
@endsection
