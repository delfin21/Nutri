@extends('layouts.app')

@section('content')
<div class="container py-5">
  <h3 class="mb-4 text-center">🧾 Upload Payment Proof</h3>
  <p class="text-muted text-center">
    Upload your payment reference and screenshot to complete your order verification.
  </p>

  <div class="row justify-content-center">
    <div class="col-md-8">
      <form action="{{ route('buyer.payment.mockSuccess') }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 shadow rounded">
        @csrf

        {{-- 💳 Payment Method --}}
        <div class="mb-3">
          <label class="form-label">Payment Method <span class="text-danger">*</span></label>
          <select name="payment_method" id="payment_method" class="form-select" required onchange="toggleFields()">
            <option value="">Select One</option>
            <option value="gcash" {{ old('payment_method') === 'gcash' ? 'selected' : '' }}>GCash</option>
            <option value="paymaya" {{ old('payment_method') === 'paymaya' ? 'selected' : '' }}>Maya</option>
            <option value="card" {{ old('payment_method') === 'card' ? 'selected' : '' }}>Credit/Debit Card</option>
          </select>
        </div>

        {{-- 🔢 GCash / Maya Fields --}}
        <div id="gcashFields" class="payment-fields" style="display: none;">
          <div class="mb-3">
            <label class="form-label">Reference Number <span class="text-danger">*</span></label>
            <input type="text" name="qr_reference" class="form-control" placeholder="e.g. 123456789012"
                   value="{{ old('qr_reference') }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Name Used in Payment <span class="text-danger">*</span></label>
            <input type="text" name="qr_name" class="form-control" placeholder="Full name used in GCash/Maya"
                   value="{{ old('qr_name') }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Mobile Number Used (optional)</label>
            <input type="text" name="qr_mobile" class="form-control" placeholder="e.g. 0917xxxxxxx"
                   value="{{ old('qr_mobile') }}">
          </div>
        </div>

        {{-- 💳 Card Fields --}}
        <div id="cardFields" class="payment-fields" style="display: none;">
          <div class="mb-3">
            <label class="form-label">Cardholder Name <span class="text-danger">*</span></label>
            <input type="text" name="qr_name" class="form-control" placeholder="As it appears on card"
                   value="{{ old('qr_name') }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Last 4 digits of Card <span class="text-danger">*</span></label>
            <input type="text" name="qr_reference" class="form-control" placeholder="e.g. 1234"
                   value="{{ old('qr_reference') }}">
          </div>
        </div>

        {{-- 🖼 Upload Screenshot --}}
        <div class="mb-3">
          <label class="form-label">Upload Screenshot / Photo <span class="text-danger">*</span></label>
          <input type="file" name="qr_proof" class="form-control" accept="image/*" required onchange="previewProof(this)">
          <img id="proofPreview" class="img-fluid mt-2" style="display: none; max-height: 300px;" />
        </div>

        {{-- ✅ Submit --}}
        <button type="submit" class="btn btn-success w-100">Submit & Finish</button>
      </form>
    </div>
  </div>
</div>

{{-- 🧠 Script --}}
<script>
function toggleFields() {
  const method = document.getElementById('payment_method').value;

  // Hide all payment groups and disable inputs
  document.querySelectorAll('.payment-fields').forEach(group => {
    group.style.display = 'none';
    group.querySelectorAll('input').forEach(input => input.disabled = true);
  });

  // Show and enable only relevant group
  if (method === 'gcash' || method === 'paymaya') {
    const gcashFields = document.getElementById('gcashFields');
    gcashFields.style.display = 'block';
    gcashFields.querySelectorAll('input').forEach(input => input.disabled = false);
  } else if (method === 'card') {
    const cardFields = document.getElementById('cardFields');
    cardFields.style.display = 'block';
    cardFields.querySelectorAll('input').forEach(input => input.disabled = false);
  }
}

function previewProof(input) {
  const file = input.files[0];
  const preview = document.getElementById('proofPreview');
  if (file) {
    preview.src = URL.createObjectURL(file);
    preview.style.display = 'block';
  }
}

// ✅ Apply correct input state after reload
window.onload = toggleFields;
</script>
@endsection
