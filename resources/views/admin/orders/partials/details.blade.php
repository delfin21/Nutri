<div class="row">
  <div class="col-md-6">
    <p><strong>Order Code:</strong> {{ $order->order_code }}</p>
    <p><strong>Product:</strong> {{ $order->product->name ?? 'N/A' }}</p>
    <p><strong>Farmer:</strong> {{ $order->product->farmer->name ?? 'Unknown' }}</p>
    <p><strong>Buyer:</strong> {{ $order->buyer->name ?? 'Unknown' }}</p>
    <p><strong>Email:</strong> {{ $order->buyer->email ?? '—' }}</p>
  </div>
  <div class="col-md-6">
  <p><strong>Delivery Address:</strong>
    @php
      $parts = array_filter([
        $order->buyer_address,
        $order->buyer_city,
        $order->buyer_region,
        $order->buyer_postal_code
      ]);
    @endphp

    {{ count($parts) ? implode(', ', $parts) : '—' }}
  </p>
    <p><strong>Status:</strong> {{ $order->status }}</p>
    <p><strong>Quantity:</strong> {{ $order->quantity }}</p>
    <p><strong>Total Price:</strong> ₱{{ number_format($order->total_price, 2) }}</p>
    <p><strong>Ordered On:</strong> {{ $order->created_at->format('d M Y, h:i A') }}</p>
  </div>
</div>
