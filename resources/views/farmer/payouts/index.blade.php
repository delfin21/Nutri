@extends('layouts.farmer')

@section('title', 'Payout History')

@push('styles')
<style>
    .profile-title {
        font-weight: bold;
        font-size: 1.5rem;
        color: #1e4d2b;
        margin-bottom: 1.5rem;
    }
    .sidebar-nav a {
        display: block;
        padding: 0.5rem 1rem;
        font-weight: bold;
        color: #1e4d2b;
        text-decoration: none;
        border-left: 4px solid transparent;
        margin-bottom: 0.5rem;
    }
    .sidebar-nav a.active,
    .sidebar-nav a:hover {
        background-color: #d4edda;
        border-left: 4px solid #1e4d2b;
        border-radius: 5px;
    }
</style>
@endpush

@section('content')
<div class="container">
    <h2 class="profile-title">PROFILE</h2>

    <div class="row">
        {{-- ✅ Sidebar --}}
        @include('farmer.partials.profile-sidebar')

        {{-- ✅ Main Content --}}
        <div class="col-md-9">
            <div class="profile-section">
                <h4 class="mb-4">Payout History</h4>

                @if($payouts->isEmpty())
                    <div class="alert alert-info">No payouts released yet.</div>
                @else
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Reference ID</th>
                            <th>Amount</th>
                            <th>Sent To</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Released</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payouts as $payout)
                        <tr>
                            <td>#{{ $payout->id }}</td>
                            <td>₱{{ number_format($payout->amount, 2) }}</td>
                            <td>
                                {{ $payout->account_name ?? 'N/A' }}<br>
                                <small class="text-muted">{{ $payout->account_number ?? '-' }}</small>
                            </td>
                            <td>{{ ucfirst($payout->method) ?? '-' }}</td>
                            <td>
                                @if($payout->is_released)
                                    <span class="badge bg-success">Released</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </td>
                            <td>{{ optional($payout->released_at)->format('M d, Y') ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
