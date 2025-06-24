<div class="bg-white p-4 shadow rounded">
    <h3 class="mb-3">🧾 Transaction Receipt</h3>

    @if ($payment)
        {{-- 👉 Show full receipt --}}
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

        <h5 class="mb-2">🛍 Orders</h5>
        <ul class="list-group mb-3">
            @foreach ($orders as $order)
                <li class="list-group-item">
                    <div><strong>Order Code:</strong> {{ $order->order_code }}</div>
                    <div><small><strong>Product:</strong> {{ $order->product->name ?? 'N/A' }}</small></div>
                    <div><small><strong>Farmer:</strong> {{ $order->farmer->name ?? 'N/A' }}</small></div>
                    <div><small><strong>Quantity:</strong> {{ $order->quantity }} × ₱{{ number_format($order->price, 2) }}</small></div>
                    <div><small><strong>Total:</strong> ₱{{ number_format($order->total_price, 2) }}</small></div>
                </li>
            @endforeach
        </ul>

        <div class="text-end mb-4">
            <h5>Total Paid: ₱{{ number_format($payment->amount / 100, 2) }}</h5>
        </div>

        {{-- Verification Details --}}
        @php $payload = json_decode($payment->response_payload, true); @endphp

        @if (is_array($payload))
            <div class="card border mb-4">
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
                        @php $filename = basename($payload['proof_path']); @endphp
                        <p><strong>Uploaded Proof:</strong></p>
                        <img src="{{ asset('storage/payments/' . $filename) }}"
                             class="img-fluid border rounded"
                             style="max-height: 300px;">
                    @endif
                </div>
            </div>
        @endif

        <div class="text-center">
            <a href="{{ route('buyer.profile.show', ['tab' => 'receipts']) }}" class="btn btn-outline-success">
                ← Back to My Receipts
            </a>
        </div>

    @elseif ($payments->count())
        {{-- 👉 List of clickable verified receipts --}}
        <div class="alert alert-info">
            ✅ Below are your verified receipts. Click to view full details.
        </div>
        <div class="list-group">
            @foreach ($payments as $verified)
                @php
                    $orders = $verified->orders;
                    $firstProduct = $orders->first()?->product?->name ?? 'Unknown Product';
                    $totalQty = $orders->sum('quantity');
                    $totalPrice = number_format($verified->amount / 100, 2);
                @endphp

                <a href="{{ route('buyer.profile.show', ['tab' => 'receipts', 'payment_id' => $verified->id]) }}"
                   class="list-group-item list-group-item-action">
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>{{ $verified->reference_id ?? $verified->intent_id }}</strong><br>
                            <small class="text-muted">{{ $verified->created_at->format('F j, Y - h:i A') }}</small><br>
                            <small class="text-muted">{{ $firstProduct }}</small><br>
                            <small class="text-muted">{{ $totalQty }} items &middot; ₱{{ $totalPrice }}</small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-success">{{ strtoupper($verified->method) }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

    @else
        <div class="alert alert-warning">
            ⚠ No verified receipts yet. Please check back after your payment is reviewed.
        </div>
    @endif
</div>
