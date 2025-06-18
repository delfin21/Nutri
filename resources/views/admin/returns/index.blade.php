@extends('layouts.admin')

@section('title', 'Return Requests')

@section('content')
<div class="container py-4">
    <h4 class="mb-3 text-white">All Return Requests</h4>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <form method="GET" action="{{ route('admin.returns.index') }}" class="d-flex gap-2">
            <select name="status" class="form-select form-select-sm">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search Order or Buyer..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-sm btn-outline-primary">Filter</button>
        </form>
    </div>

    <div class="card">
        <div class="card-body table-responsive p-0">
            <table class="table table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Order ID</th>
                        <th>Buyer</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($requests as $req)
                    <tr>
                        <td>{{ $req->id }}</td>
                        <td>{{ $req->order->order_code ?? 'N/A' }}</td>
                        <td>{{ $req->buyer->name ?? 'Unknown' }}</td>
                        <td>
                            <span class="badge bg-{{ $req->status === 'pending' ? 'warning' : ($req->status === 'approved' ? 'success' : 'danger') }}">
                                {{ ucfirst($req->status) }}
                            </span>
                        </td>
                        <td>{{ $req->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('admin.returns.show', $req->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No return requests found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
