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

@if ($payment->response_payload)
  <label><strong>Response Payload (JSON):</strong></label>
  <pre class="bg-dark text-white p-3 rounded" style="max-height: 300px; overflow: auto; font-size: 0.875rem;">
{{ json_encode(json_decode($payment->response_payload), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}
  </pre>
@else
  <div class="alert alert-secondary">No response payload recorded.</div>
@endif
