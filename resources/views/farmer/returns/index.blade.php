@extends('layouts.farmer')

@section('title', 'Return Requests')

@section('content')
<div class="container py-4">
    <h4 class="mb-3">Return Requests</h4>

    @if ($returns->isEmpty())
        <div class="alert alert-info text-center">No return requests filed yet.</div>
    @else
        <table class="table table-bordered bg-white">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Order</th>
                    <th>Buyer</th>
                    <th>Resolution</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($returns as $req)
                    <tr>
                        <td>{{ $req->id }}</td>
                        <td>{{ $req->order->order_code ?? 'N/A' }}</td>
                        <td>{{ $req->buyer->name ?? 'Unknown' }}</td>
                        <td>{{ ucfirst($req->resolution_type) ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-{{ $req->status === 'pending' ? 'warning text-dark' : ($req->status === 'approved' ? 'success' : 'danger') }}">
                                {{ ucfirst($req->status) }}
                            </span>
                        </td>
                        <td>{{ $req->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('farmer.returns.show', $req->id) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
