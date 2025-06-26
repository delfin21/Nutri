<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Prescriptive Recommendations</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        h2 { text-align: center; margin-bottom: 20px; }
        .card { border: 1px solid #ccc; border-radius: 6px; padding: 10px; margin-bottom: 12px; }
        .success { background-color: #d4edda; }
        .warning { background-color: #fff3cd; }
        .danger  { background-color: #f8d7da; }
        .info    { background-color: #d1ecf1; }
    </style>
</head>
<body>
    <h2>Prescriptive Recommendations</h2>

    @foreach ($recommendations as $rec)
        <div class="card {{ $rec['type'] }}">
            <strong>
                @switch($rec['type'])
                    @case('success') 🌟 Best Seller @break
                    @case('warning') ⚠️ Low Stock @break
                    @case('danger') 📉 Declining Sales @break
                    @case('info') 📊 Info @break
                    @default 🧩 Note
                @endswitch
            </strong>
            <p>{!! $rec['message'] !!}</p>
        </div>
    @endforeach
</body>
</html>
