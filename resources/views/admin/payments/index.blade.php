@extends('layouts.admin')

@section('title', 'Transaction Logs')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4 text-white">Transaction Logs</h3>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light text-center">
                <tr>
                    <th>ID</th>
                    <th>Buyer</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Method</th>
                    <th>Order Code</th>
                    <th>Is Test?</th>
                    <th>Verified</th> {{-- ✅ Added Column --}}
                    <th>Date</th>
                    <th>View</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach ($payments as $payment)
                    <tr>
                        <td>{{ $payment->id }}</td>
                        <td>{{ $payment->buyer->name ?? 'N/A' }}</td>
                        <td>₱{{ number_format($payment->amount / 100, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $payment->status === 'paid' ? 'success' : 'secondary' }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td>{{ strtoupper($payment->method) }}</td>
                        <td>
                            @if (is_iterable($payment->orders) && $payment->orders->isNotEmpty())
                                @foreach ($payment->orders as $order)
                                    <div class="text-success small">{{ $order->order_code }}</div>
                                @endforeach
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $payment->is_test ? 'warning' : 'info' }}">
                                {{ $payment->is_test ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $payment->is_verified ? 'primary' : 'danger' }}">
                                {{ $payment->is_verified ? 'Verified' : 'Unverified' }}
                            </span>
                        </td>
                        <td>
                            <span title="{{ $payment->created_at->format('Y-m-d H:i') }}">
                                {{ $payment->created_at->diffForHumans() }}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary"
                                    onclick="loadPaymentDetails({{ $payment->id }})">
                                🔍 View
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $payments->links() }}
    </div>
</div>

<!-- Global Payment Modal -->
<div class="modal fade" id="paymentDetailsModal" tabindex="-1" aria-labelledby="paymentDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-light">
        <h5 class="modal-title" id="paymentDetailsModalLabel">Payment Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="payment-details-content">
          <p class="text-muted">Loading...</p>
        </div>
      </div>
      <div class="modal-footer">
        {{-- 🟩 Verified Button Placeholder (will be injected from show.blade.php) --}}
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  function loadPaymentDetails(paymentId) {
    const content = document.getElementById('payment-details-content');
    content.innerHTML = '<p class="text-muted">Loading...</p>';

    fetch(`/admin/payments/${paymentId}`)
      .then(response => response.text())
      .then(data => {
        content.innerHTML = data;
        const modal = new bootstrap.Modal(document.getElementById('paymentDetailsModal'));
        modal.show();
      })
      .catch(() => {
        content.innerHTML = '<p class="text-danger">Failed to load payment details.</p>';
      });
  }

  function markAsVerified(paymentId) {
    if (!confirm('Are you sure you want to mark this payment as verified?')) return;

    fetch(`/admin/payments/${paymentId}/verify`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json'
      }
    }).then(res => res.json())
      .then(data => {
        if (data.status === 'ok') {
          alert('Marked as verified!');
          location.reload();
        }
      });
  }
</script>
@endpush
