@extends('layouts.admin')

@section('title', 'Manage Orders')

@section('content')
<div class="container py-4">
  <h2 class="mb-4 text-white">Orders Management</h2>

  <form method="GET" action="{{ route('admin.orders.index') }}" class="d-flex justify-content-between mb-3 gap-3">
    <div class="d-flex gap-2">
      <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by product or buyer..." style="min-width: 260px;">
      <select name="status" class="form-select">
        <option value="">All Status</option>
        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
        <option value="To Ship" {{ request('status') == 'To Ship' ? 'selected' : '' }}>To Ship</option>
        <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
        <option value="Return/Refund" {{ request('status') == 'Return/Refund' ? 'selected' : '' }}>Return/Refund</option>
        <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
      </select>
      <button type="submit" class="btn btn-search btn-utility">Search</button>
    </div>

    <div>
      <a href="{{ route('admin.orders.index') }}" class="btn btn-reset btn-utility">Reset</a>
    </div>
  </form>

  <div class="card">
    <div class="card-body p-0">
      <div class="table-responsive rounded-top">
        <table class="table table-borderless align-middle text-sm admin-order-table table-rounded">
          <thead class="table-light">
            <tr>
              <th>Order ID</th>
              <th>Product ID</th>
              <th>Product Name</th>
              <th>Farmer</th>
              <th>Buyer</th>
              <th>Quantity</th>
              <th>Total (₱)</th>
              <th>Status</th>
              <th>Action</th>
              <th>Created</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($orders as $order)
              <tr>
                <td>
                  <span class="order-id text-success fw-semibold text-uppercase">
                    {{ $order->order_code }}
                  </span>
                </td>
                <td>{{ $order->product_id }}</td>
                <td>{{ $order->product->name ?? 'N/A' }}</td>
                <td>{{ $order->product->farmer->name ?? 'Unknown' }}</td>
                <td>{{ $order->buyer->name ?? 'Unknown' }}</td>
                <td>{{ $order->quantity }}</td>
                <td>₱{{ number_format($order->total_price, 2) }}</td>
                <td>
                  <span class="status {{ strtolower(str_replace([' ', '/'], ['-', '-'], $order->status)) }}">
                    {{ strtoupper($order->status) }}
                  </span>
                </td>
                <td>
                  <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" 
                      type="button" 
                      data-bs-toggle="dropdown" 
                      data-bs-offset="0,10"
                      data-bs-auto-close="outside"
                      data-bs-display="static"
                      style="padding: 4px 6px;">
                      <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                      <li>
                        <a href="#" class="dropdown-item dropdown-hover"
                          data-bs-toggle="modal" 
                          data-bs-target="#orderDetailsModal" 
                          onclick="loadOrderDetails({{ $order->id }})">
                          View Details
                        </a>
                      </li>
                      <li>
                        <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}">
                          @csrf
                          @method('PATCH')
                          <select name="status" class="form-select form-select-sm status-select mt-2" onchange="this.form.submit()">
                            @foreach (['Pending', 'To Ship', 'Completed', 'Return/Refund', 'Cancelled'] as $status)
                              <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>
                                {{ $status }}
                              </option>
                            @endforeach
                          </select>
                        </form>
                      </li>
                    </ul>
                  </div>
                </td>
                <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="10" class="text-center text-muted py-4">No orders found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="mt-3 px-3">
        {{ $orders->appends(request()->query())->links('pagination::bootstrap-5') }}
      </div>
    </div>
  </div>
</div>
@endsection

<!-- Order Details Modal -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="orderDetailsModalLabel">Order Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="order-details-content">
          <p class="text-muted">Loading...</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  function loadOrderDetails(orderId) {
    const content = document.getElementById('order-details-content');
    content.innerHTML = '<p class="text-muted">Loading...</p>';
    fetch(`/admin/orders/${orderId}`)
      .then(response => response.text())
      .then(data => {
        content.innerHTML = data;
      })
      .catch(error => {
        content.innerHTML = '<p class="text-danger">Failed to load order details.</p>';
        console.error(error);
      });
  }

  // Highlight order on URL param
  document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const orderId = urlParams.get('highlight_order');
    if (orderId) {
      loadOrderDetails(orderId);
      const modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
      modal.show();
    }

    // Dynamic dropup if near bottom
document.querySelectorAll('.dropdown').forEach(dropdown => {
  dropdown.addEventListener('show.bs.dropdown', function () {
    const menu = dropdown.querySelector('.dropdown-menu');
    const button = dropdown.querySelector('.dropdown-toggle');
    const dropdownRect = button.getBoundingClientRect();
    const menuHeight = menu.offsetHeight;

    if (dropdownRect.bottom + menuHeight > window.innerHeight) {
      dropdown.classList.add('dropup');
    } else {
      dropdown.classList.remove('dropup');
    }
  });
});

  });
</script>
@endpush
