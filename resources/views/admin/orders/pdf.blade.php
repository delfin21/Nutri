<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Orders Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h2>NutriApp - Orders Report</h2>
    <table>
        <thead>
            <tr>
                <th>Order Code</th>
                <th>Product</th>
                <th>Farmer</th>
                <th>Buyer</th>
                <th>Delivery Address</th>
                <th>Quantity</th>
                <th>Total (₱)</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->order_code }}</td>
                    <td>{{ $order->product->name ?? 'N/A' }}</td>
                    <td>{{ $order->product->farmer->name ?? 'N/A' }}</td>
                    <td>
                        {{ $order->buyer->name ?? 'N/A' }}<br>
                        <small>{{ $order->buyer->email ?? '—' }}</small>
                    </td>
                    <td>
                        @php
                            $addressParts = array_filter([
                                $order->buyer_address,
                                $order->buyer_city,
                                $order->buyer_region,
                                $order->buyer_postal_code
                            ]);
                        @endphp
                        {{ count($addressParts) ? implode(', ', $addressParts) : '—' }}
                    </td>
                    <td>{{ $order->quantity }}</td>
                    <td>₱{{ number_format($order->total_price, 2) }}</td>
                    <td>{{ $order->status }}</td>
                    <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @endforeach
        </tbody>

    </table>
</body>
</html>
