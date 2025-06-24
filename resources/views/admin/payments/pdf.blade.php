<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Transaction Report</title>
  <style>
    body { font-family: sans-serif; font-size: 12px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
    th { background-color: #f2f2f2; }
    h2 { text-align: center; }
  </style>
</head>
<body>
  <h2>NutriApp - Transaction Report</h2>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Buyer</th>
        <th>Amount</th>
        <th>Status</th>
        <th>Method</th>
        <th>Orders</th>
        <th>Test?</th>
        <th>Verified</th>
        <th>Date</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($payments as $p)
        <tr>
          <td>{{ $p->id }}</td>
          <td>{{ $p->buyer->name ?? 'N/A' }}</td>
          <td>₱{{ number_format($p->amount / 100, 2) }}</td>
          <td>{{ ucfirst($p->status) }}</td>
          <td>{{ strtoupper($p->method) }}</td>
          <td>
            @if ($p->orders->isNotEmpty())
              {{ $p->orders->pluck('order_code')->join(', ') }}
            @else
              —
            @endif
          </td>
          <td>{{ $p->is_test ? 'Yes' : 'No' }}</td>
          <td>{{ $p->is_verified ? 'Yes' : 'No' }}</td>
          <td>{{ $p->created_at->format('Y-m-d H:i') }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
