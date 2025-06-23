<div class="pb-4">
    <h4 class="text-success mb-4">My Purchase</h4>

    {{-- Tabs --}}
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item"><a class="nav-link {{ request('status') == null ? 'active' : '' }}" href="{{ route('buyer.profile.show', ['tab' => 'purchase']) }}">All</a></li>
        <li class="nav-item"><a class="nav-link {{ request('status') == 'To Ship' ? 'active' : '' }}" href="{{ route('buyer.profile.show', ['tab' => 'purchase', 'status' => 'To Ship']) }}">To Ship</a></li>
        <li class="nav-item"><a class="nav-link {{ request('status') == 'Pending' ? 'active' : '' }}" href="{{ route('buyer.profile.show', ['tab' => 'purchase', 'status' => 'Pending']) }}">Pending</a></li>
        <li class="nav-item"><a class="nav-link {{ request('status') == 'Completed' ? 'active' : '' }}" href="{{ route('buyer.profile.show', ['tab' => 'purchase', 'status' => 'Completed']) }}">Completed</a></li>
        <li class="nav-item"><a class="nav-link {{ request('status') == 'Cancelled' ? 'active' : '' }}" href="{{ route('buyer.profile.show', ['tab' => 'purchase', 'status' => 'Cancelled']) }}">Cancelled</a></li>
        <li class="nav-item"><a class="nav-link {{ request('status') == 'Return/Refund' ? 'active' : '' }}" href="{{ route('buyer.profile.show', ['tab' => 'purchase', 'status' => 'Return/Refund']) }}">Return/Refund</a></li>
    </ul>

    {{-- Orders --}}
    @forelse ($orders as $order)
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>{{ $order->product->farmer->name ?? 'AGRO FRESH' }}</strong>
                <span class="status {{ strtolower(str_replace([' ', '/'], ['-', '-'], $order->status)) }}">
                    {{ strtoupper($order->status) }}
                </span>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    @if ($order->product)
                        <a href="{{ route('product.show', $order->product->id) }}" class="text-decoration-none text-dark d-flex align-items-center">
                            <img src="{{ asset('storage/' . $order->product->image) }}" width="60" class="me-3" onerror="this.src='{{ asset('img/default.png') }}'">
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $order->product->name }}</h6>
                                <small class="text-muted">{{ $order->quantity }}kg @ ₱{{ number_format($order->price, 2) }} / kg</small>
                            </div>
                        </a>
                    @else
                        <div class="d-flex align-items-center text-muted">
                            <img src="{{ asset('img/default.png') }}" width="60" class="me-3">
                            <div>
                                <h6 class="mb-0 fw-bold">[Product Deleted]</h6>
                                <small class="text-muted">{{ $order->quantity }}kg — order preserved</small>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="mb-2">
                    @php
                        $label = match($order->payment_status) {
                            'paid' => '✅ PAID',
                            'pending' => '⏳ PENDING',
                            'failed' => '❌ FAILED',
                            default => '⚠ UNKNOWN',
                        };
                    @endphp
                    <small class="text-muted">💳 Payment: {{ $label }}</small>
                </div>

                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <strong class="text-success">₱{{ number_format($order->total_price, 2) }}</strong>
                    <div class="d-flex gap-2 mt-2 mt-md-0">
                        @if (in_array(strtolower($order->status), ['pending', 'to ship']))
                            <form method="POST" action="{{ route('buyer.orders.cancel', $order->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to cancel this order?')">
                                    Cancel
                                </button>
                            </form>
                        @endif

                        @if (strtolower($order->status) === 'shipped' && !$order->returnRequest)
                            <form method="POST" action="{{ route('buyer.orders.confirm', $order->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-outline-success btn-sm" onclick="return confirm('Confirm delivery for this order?')">
                                    Confirm Delivery
                                </button>
                            </form>
                        @endif

                        @if (strtolower($order->status) === 'shipped' && !$order->returnRequest)
                            <a href="{{ route('buyer.returns.create', $order->id) }}" class="btn btn-outline-danger btn-sm"
                               onclick="return confirm('Are you sure you want to request a return/refund?')">
                                Return / Refund
                            </a>
                        @elseif ($order->returnRequest && $order->returnRequest->status === 'pending')
                            <div class="d-flex flex-column align-items-end">
                                <span class="badge bg-warning text-dark mb-1">Return Requested</span>
                                <a href="{{ route('buyer.returns.show', $order->returnRequest->id) }}" class="btn btn-outline-secondary btn-sm">
                                    View Return Details
                                </a>
                            </div>
                        @elseif ($order->returnRequest)
                            @if ($order->returnRequest->status === 'rejected')
                                <span class="badge bg-danger text-white">Return Rejected</span>
                                <a href="{{ route('buyer.returns.show', $order->returnRequest->id) }}" class="btn btn-outline-secondary btn-sm">View Return</a>
                            @elseif ($order->returnRequest->status === 'approved')
                                <span class="badge bg-success text-white">Return Approved</span>
                                <a href="{{ route('buyer.returns.show', $order->returnRequest->id) }}" class="btn btn-outline-secondary btn-sm">View Return</a>
                            @endif
                        @endif

                        @if ($order->payment_id)
                            <a href="{{ route('buyer.payments.receipt', ['payment' => $order->payment_id]) }}" class="btn btn-outline-secondary btn-sm">
                                View Receipt
                            </a>
                        @endif

                        @if (strtolower($order->status) === 'completed' && !$order->rating)
                            <a href="{{ route('buyer.orders.rate.create', $order->id) }}" class="btn btn-outline-primary btn-sm">
                                Rate Product
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info text-center">No orders found.</div>
    @endforelse
</div>
