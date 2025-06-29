@extends('layouts.admin')

@section('title', 'Sales Report')

@section('content')
<div class="container py-4">
    <h4 class="mb-4 text-white">Sales Report</h4>

    {{-- Filter & Export Form --}}
 <form method="GET" action="{{ route('admin.reports.index') }}" class="row gx-3 gy-2 align-items-end mb-4">
    <div class="col-md-2 col-sm-6">
        <label class="form-label text-white mb-1">Start</label>
        <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $startDate }}">
    </div>

    <div class="col-md-2 col-sm-6">
        <label class="form-label text-white mb-1">End</label>
        <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $endDate }}">
    </div>

    <div class="col-md-3 col-sm-6">
        <label class="form-label text-white mb-1">Category</label>
        <select name="category" class="form-select form-select-sm">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                    {{ $cat }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3 col-sm-6">
        <label class="form-label text-white mb-1">Product</label>
        <input type="text" name="product" class="form-control form-control-sm" placeholder="Product name..." value="{{ request('product') }}">
    </div>

    <div class="col-md-1 col-6">
        <button type="submit" class="btn btn-primary w-100">Filter</button>
    </div>

    <div class="col-md-1 col-6">
        <button type="submit" formaction="{{ route('admin.reports.export') }}" class="btn btn-success w-100">
            Export
        </button>
    </div>
</form>


    {{-- Report Table --}}
    <div class="card p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle text-center" style="border-collapse: separate; border-spacing: 0 0.75rem;">
                <thead class="table-light">
                    <tr>
                        <th>Order ID</th>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Farmer</th>
                        <th>Buyer</th>
                        <th>Quantity</th>
                        <th>Total Price (₱)</th>
                        <th>Status</th>
                        <th>Order Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr style="background: white; border-radius: 12px; box-shadow: 0 2px 6px rgba(0,0,0,0.05);">
                            <td><span class="order-id">{{ $order->id }}</span></td>
                            <td>{{ $order->product->name ?? '-' }}</td>
                            <td>{{ $order->product->category ?? '-' }}</td>
                            <td>{{ $order->product->farmer->name ?? '-' }}</td>
                            <td>{{ $order->buyer->name ?? '-' }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>₱{{ number_format($order->total_price, 2) }}</td>
                            <td><span class="status {{ strtolower($order->status) }}">{{ ucfirst($order->status) }}</span></td>
                            <td>{{ $order->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="9">No sales found for this period.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->count())
            <div class="mt-3 text-end fw-bold">
                Total Sales: ₱{{ number_format($orders->sum('total_price'), 2) }}
            </div>
        @endif

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 px-3">
            <div class="text-muted small mb-2 mb-md-0">
                Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} results
            </div>
            <div>
                {{ $orders->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

{{-- Confirmation Modal (Optional Future Use) --}}
<div class="modal fade" id="reportConfirmModal" tabindex="-1" aria-labelledby="reportConfirmLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4">
            <div class="modal-header">
                <h5 class="modal-title" id="reportConfirmLabel">Export Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to export this sales report to Excel?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" form="exportForm">Yes, Export</button>
            </div>
        </div>
    </div>
</div>
@endsection
