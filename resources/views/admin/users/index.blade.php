@extends('layouts.admin')

@section('title', 'Manage Users')

@section('content')
<div class="container py-4">
    <h4 class="mb-4 text-white">Manage Users</h4>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Filters and Export -->
    <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3 align-items-center mb-3">
        <div class="col-auto">
            <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
        </div>
        <div class="col-auto">
            <select name="role" class="form-select">
                <option value="">All Roles</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="farmer" {{ request('role') == 'farmer' ? 'selected' : '' }}>Farmer</option>
                <option value="buyer" {{ request('role') == 'buyer' ? 'selected' : '' }}>Buyer</option>
            </select>
        </div>
        <div class="col-auto">
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>Banned</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-utility btn-search">Search</button>
            <a href="{{ route('admin.users.export', request()->query()) }}" class="btn btn-utility btn-export">
                <i class="bi bi-download me-1"></i> Export
            </a>
            <a href="{{ route('admin.users.create') }}" class="btn btn-utility btn-add-product">
                + Add New User
            </a>
        </div>
    </form>

    <!-- User Table -->
<div class="card">
    <div class="card-body p-0 position-relative">
        <div class="table-responsive rounded-top position-relative" style="overflow: visible;">
            <table class="table table-borderless align-middle custom-product-table table-rounded mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&color=fff&size=40"
                                         class="rounded-circle me-3" width="40" height="40" alt="Avatar">
                                    <div>
                                        <strong>{{ $user->name }}</strong>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge role-badge role-{{ $user->role }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1 align-items-start">
                                    <!-- Status Toggle -->
                                    <form method="POST" action="{{ route('admin.users.toggleStatus', $user->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="badge rounded-pill border-0 {{ $user->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                            {{ ucfirst($user->status) }}
                                        </button>
                                    </form>

                                    <!-- Banned Badge -->
                                    @if ($user->is_banned)
                                        <span class="badge rounded-pill bg-danger">Banned</span>
                                    @endif
                                </div>
                            </td>

                            <td class="text-end position-relative" style="width: 60px;">
                            @php
                                $isLastRow = $loop->remaining < 2;
                            @endphp
                            <div class="dropdown {{ $isLastRow ? 'dropup' : '' }}">

                                    <button class="btn btn-sm btn-light p-1" type="button" data-bs-toggle="dropdown"
                                        data-bs-boundary="viewport"
                                        data-bs-display="static"
                                        aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.users.edit', $user->id) }}">
                                                <i class="fas fa-edit me-2"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.users.show', $user->id) }}">
                                                <i class="fas fa-eye me-2"></i> View
                                            </a>
                                        </li>

                                        @if ($user->role !== 'admin')
                                            <li>
                                                @if (!$user->is_banned)
                                                    <a href="{{ route('admin.users.ban.form', $user->id) }}" class="dropdown-item text-warning">
                                                        <i class="bi bi-slash-circle me-2"></i> Issue Ban
                                                    </a>
                                                @else
                                                    <form action="{{ route('admin.users.unban', $user->id) }}" method="POST" onsubmit="return confirm('Lift ban on this user?');">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item text-success">
                                                            <i class="bi bi-check-circle me-2"></i> Lift Ban
                                                        </button>
                                                    </form>
                                                @endif
                                            </li>
                                        @endif

                                        <li>
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Delete this user?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="fas fa-trash me-2"></i> Delete
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div> 

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap px-3">
                <div class="text-muted small">
                    Showing 
                    {{ $users->firstItem() ?? 0 }} 
                    to 
                    {{ $users->lastItem() ?? 0 }} 
                    of 
                    {{ $users->total() }} 
                    results
                </div>
                <div>
                    {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
