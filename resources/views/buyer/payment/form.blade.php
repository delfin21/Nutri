@extends('layouts.app')

@section('content')
<style>
.checkout-wrapper {
  display: flex;
  flex-wrap: wrap;
  gap: 30px;
  margin-top: 30px;
}
.checkout-form {
  flex: 1 1 60%;
  background: #fff;
  padding: 25px;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0,0,0,0.05);
}
.checkout-summary {
  flex: 1 1 35%;
  background: #f8f8f8;
  padding: 25px;
  border-radius: 10px;
  border: 1px solid #ddd;
}
.summary-item {
  display: flex;
  justify-content: space-between;
  margin-bottom: 10px;
}
</style>

<div class="container checkout-wrapper">

{{-- 💳 Buyer Info (Step 1) --}}
<form id="paymentSelectorForm" class="checkout-form" action="{{ route('buyer.payment.process') }}" method="POST">
  @csrf
  <h4 class="mb-2">Step 1: Buyer Information</h4>
  <p class="text-muted">Fill out your contact and shipping details below. You’ll proceed to payment after this step.</p>

  <div class="row g-3">
    {{-- 📩 Contact Info --}}
    <div class="col-md-12">
      <label>Email</label>
      <input type="email" name="contact_email" class="form-control" required
             value="{{ old('contact_email', session('checkout_email')) }}"
             placeholder="e.g. buyer@email.com">
    </div>
    <div class="col-md-6">
      <label>Phone</label>
      <input type="text" name="phone" class="form-control" required
             value="{{ old('phone', session('checkout_phone')) }}"
             placeholder="e.g. 09XXXXXXXXX">
    </div>

    {{-- 💳 Payment Choice --}}
    <div class="col-md-6">
      <label>Payment Method</label>
      <select name="payment_method" id="payment_method" class="form-select" required>
        <option value="">Select Payment Method</option>
        <option value="qr" {{ old('payment_method') === 'qr' ? 'selected' : '' }}>QR / Instapay</option>
        <option value="paymongo" disabled>PayMongo (Coming Soon)</option>
      </select>
    </div>

    {{-- 👤 Personal Info --}}
    <div class="col-md-6">
      <label>First Name</label>
      <input type="text" name="first_name" class="form-control" required
             value="{{ old('first_name', session('checkout_first_name')) }}"
             placeholder="e.g. Delfin">
    </div>
    <div class="col-md-6">
      <label>Last Name</label>
      <input type="text" name="last_name" class="form-control" required
             value="{{ old('last_name', session('checkout_last_name')) }}"
             placeholder="e.g. Genon">
    </div>

    {{-- 🏠 Address --}}
    <div class="col-md-12">
      <label>Street Address</label>
      <input type="text" name="address" class="form-control" required
             value="{{ old('address', session('checkout_address')) }}"
             placeholder="e.g. BLK 5 LOT 10...">
    </div>
    <div class="col-md-6">
      <label>Postal Code</label>
      <input type="text" name="postal_code" class="form-control" required
             value="{{ old('postal_code', session('checkout_postal_code')) }}">
    </div>
    <div class="col-md-6">
      <label>City</label>
      <input type="text" name="city" class="form-control" required
             value="{{ old('city', session('checkout_city')) }}">
    </div>
    <div class="col-md-12">
      <label>Region</label>
      <input type="text" name="region" class="form-control" required
             value="{{ old('region', session('checkout_region')) }}">
    </div>
  </div>

  {{-- 🔘 Redirect --}}
  <button type="submit" class="btn btn-success w-100 mt-4">Continue</button>
</form>

{{-- 🛒 Order Summary --}}
<div class="checkout-summary">
  <h5>Order Summary</h5>
  @php
    $cartIds = session('checkout_items', []);
    $items = App\Models\Cart::with('product')->whereIn('id', $cartIds)->where('buyer_id', Auth::id())->get();
    $total = 0;
  @endphp

  <hr>
  @foreach ($items as $item)
    @php $subtotal = $item->quantity * $item->product->price; $total += $subtotal; @endphp
    <div class="summary-item">
      <div>
        <strong>{{ $item->product->name }}</strong><br>
        x{{ $item->quantity }} @ ₱{{ number_format($item->product->price, 2) }}
      </div>
      <div>
        ₱{{ number_format($subtotal, 2) }}
      </div>
    </div>
  @endforeach
  <hr>
  <div class="summary-item">
    <strong>Total</strong>
    <strong>₱{{ number_format($total, 2) }}</strong>
  </div>
</div>
</div>

@endsection
