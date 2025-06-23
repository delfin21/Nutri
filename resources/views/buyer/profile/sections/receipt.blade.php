<div class="bg-white p-4 shadow rounded">
    <h3 class="mb-3">🧾 Transaction Receipt</h3>

    @if ($payment)
        <div class="mb-3">
            <p><strong>Reference ID:</strong> {{ $payment->reference_id ?? $payment->intent_id }}</p>
            <p><strong>Payment Method:</strong> {{ ucfirst($payment->method) }}</p>
            <p><strong>Status:</strong> 
                <span class="badge bg-{{ $payment->status === 'paid' ? 'success' : 'secondary' }}">
                    {{ strtoupper($payment->status) }}
                </span>
            </p>
            <p><strong>Date & Time:</strong> {{ $payment->created_at->format('F j, Y h:i A') }}</p>
        </div>

        <hr>

        <h5>🛍 Orders</h5>
        <ul class="list-group mb-3">
            @foreach ($orders as $order)
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="me-3">
                        <div><strong>Order Code:</strong> {{ $order->order_code }}</div>
                        <div><small>Product: {{ $order->product->name ?? 'N/A' }}</small></div>
                        <div><small>Farmer: {{ $order->farmer->name ?? 'N/A' }}</small></div>
                        <div><small>Quantity: {{ $order->quantity }} x ₱{{ number_format($order->price, 2) }}</small></div>
                    </div>
                    <div>
                        <strong>₱{{ number_format($order->total_price, 2) }}</strong>
                    </div>
                </li>
            @endforeach
        </ul>

        <div class="text-end mb-4">
            <h5>Total Paid: ₱{{ number_format($payment->amount / 100, 2) }}</h5>
        </div>

        {{-- 📎 Decoded Additional Details --}}
        @php
            $payload = json_decode($payment->response_payload, true);
        @endphp

        @if (is_array($payload))
            <div class="card border">
                <div class="card-header bg-light"><strong>📄 Payment Verification Details</strong></div>
                <div class="card-body">
                    @if (!empty($payload['qr_reference']))
                        <p><strong>Reference Number:</strong> {{ $payload['qr_reference'] }}</p>
                    @endif
                    @if (!empty($payload['qr_name']))
                        <p><strong>Name Used:</strong> {{ $payload['qr_name'] }}</p>
                    @endif
                    @if (!empty($payload['qr_mobile']))
                        <p><strong>Mobile Number:</strong> {{ $payload['qr_mobile'] }}</p>
                    @endif
                    @if (!empty($payload['proof_path']))
                        @php
                            $filename = basename($payload['proof_path']);
                        @endphp
                        <p><strong>Uploaded Proof:</strong></p>
                        <img src="{{ asset('storage/payments/' . $filename) }}"
                             alt="Uploaded Proof"
                             class="img-fluid border rounded"
                             style="max-height: 300px;">
                    @endif
                </div>
            </div>
        @endif
    @else
        <div class="alert alert-warning">
            ⚠ No receipt selected. Please go to <strong>My Receipts</strong> tab and click a specific payment to view details.
        </div>
    @endif

    <div class="mt-4 text-center">
        <a href="{{ route('buyer.profile.show', ['tab' => 'receipts']) }}" class="btn btn-outline-success">← Back to Receipts</a>
    </div>
</div>
