@extends('layouts.admin')

@section('title', 'Farmer Payouts')

@push('styles')
<style>
    .card-box {
        background-color: #fff;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
    }

    .table thead th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #333;
    }

    .table td,
    .table th {
        vertical-align: middle !important;
    }

    .badge {
        font-size: 0.8rem;
        padding: 0.4em 0.6em;
        border-radius: 12px;
    }

    .badge.bg-success {
        background-color: #28a745;
    }

    .badge.bg-warning {
        background-color: #ffc107;
        color: #000;
    }

    .text-muted {
        font-style: italic;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <h2 class="mb-4 text-white">Farmer Payouts</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card-box">
        @if($payouts->isEmpty())
            <div class="alert alert-info">No payouts recorded yet.</div>
        @else
            <table class="table table-bordered table-hover align-middle">
                <thead>
                    <tr>
                        <th>Farmer</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Account Name</th>
                        <th>Account No.</th>
                        <th>Release Status</th>
                        <th>Requested At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payouts as $payout)
                        <tr>
                            <td>
                                {{ $payout->farmer->name ?? 'Unknown' }}<br>
                                <small class="text-muted">{{ $payout->farmer->email ?? '' }}</small>
                            </td>
                            <td>₱{{ number_format($payout->amount, 2) }}</td>
                            <td>{{ ucfirst($payout->method) }}</td>
                            <td>{{ $payout->account_name }}</td>
                            <td>{{ $payout->account_number }}</td>
                            <td>
                                @if($payout->is_released)
                                    <span class="badge bg-success">Released</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </td>
                            <td>{{ $payout->created_at->format('M d, Y h:i A') }}</td>
                            <td>
                                @if(!$payout->is_released)
                                    <form action="{{ route('admin.payouts.release', $payout->id) }}" method="POST" onsubmit="return confirm('Mark this payout as released?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary">Mark as Released</button>
                                    </form>
                                @else
                                    <span class="text-muted">✔</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3 d-flex justify-content-center">
                {{ $payouts->links() }}
            </div>

        @endif
    </div>
</div>
@endsection
