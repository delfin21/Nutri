<ul class="list-group mb-3">
  <li class="list-group-item"><strong>Buyer:</strong> {{ $payment->buyer->name ?? 'N/A' }}</li>
  <li class="list-group-item"><strong>Amount:</strong> ₱{{ number_format($payment->amount / 100, 2) }}</li>
  <li class="list-group-item"><strong>Status:</strong> {{ ucfirst($payment->status) }}</li>
  <li class="list-group-item"><strong>Method:</strong> {{ strtoupper($payment->method) }}</li>
  <li class="list-group-item">
    <strong>Order Codes:</strong><br>
    @if (is_iterable($payment->orders) && $payment->orders->isNotEmpty())
      @foreach ($payment->orders as $order)
        <span class="badge bg-success me-1">{{ $order->order_code }}</span>
      @endforeach
    @else
      <span class="text-muted">—</span>
    @endif
  </li>
  <li class="list-group-item"><strong>Is Test:</strong> {{ $payment->is_test ? 'Yes' : 'No' }}</li>
  <li class="list-group-item">
    <strong>Date:</strong>
    <span title="{{ $payment->created_at->format('Y-m-d H:i') }}">
      {{ $payment->created_at->diffForHumans() }}
    </span>
  </li>
</ul>

{{-- 📦 Decode response payload if it's JSON --}}
@php
  $payload = json_decode($payment->response_payload, true);
@endphp

@if (is_array($payload))
  <div class="card border mb-3">
    <div class="card-header bg-light"><strong>📄 QR Payment Details</strong></div>
    <div class="card-body">
      @if (!empty($payload['qr_reference']))
        <p><strong>Reference No:</strong> {{ $payload['qr_reference'] }}</p>
      @endif
      @if (!empty($payload['qr_name']))
        <p><strong>Name Used:</strong> {{ $payload['qr_name'] }}</p>
      @endif
      @if (!empty($payload['qr_mobile']))
        <p><strong>Mobile Used:</strong> {{ $payload['qr_mobile'] }}</p>
      @endif
      @if (!empty($payload['proof_path']))
        @php
            $filename = basename($payload['proof_path']);
        @endphp
        <p><strong>Uploaded Proof:</strong></p>
        <img src="{{ asset('storage/payments/' . $filename) }}"
             alt="Uploaded Proof"
             class="img-fluid rounded border"
             style="max-height: 300px;">
      @endif

      @if (empty($payload['qr_reference']) && empty($payload['qr_name']) && empty($payload['qr_mobile']) && empty($payload['proof_path']))
        <div class="text-muted fst-italic">No QR-specific fields found in payload.</div>
      @endif
    </div>
  </div>
@else
  <div class="alert alert-secondary">No response payload recorded.</div>
@endif

{{-- ✅ Verification Controls --}}
@if (! $payment->is_verified)
  <div class="text-center mt-4">
    <button class="btn btn-success" onclick="markAsVerified({{ $payment->id }})">
      ✅ Mark as Verified
    </button>
  </div>
@else
  <div class="text-center mt-4">
    <span class="badge bg-primary fs-6">✔️ Already Verified</span>
  </div>
@endif
