@extends('layouts.farmer')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <h3 class="mb-3 mb-md-0">📝 Prescriptive Recommendations for You</h3>
        <div class="d-flex gap-2">
            <a href="{{ route('farmer.recommendations.pdf') }}" class="btn btn-outline-primary">
                <i class="bi bi-download"></i> Export PDF
            </a>
        </div>
    </div>

    {{-- Success Flash Message --}}
    @if(session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Filter Dropdown --}}
    <div class="mb-3">
        <label for="filterSelect" class="form-label fw-bold">Filter by Type:</label>
        <select class="form-select w-auto" id="filterSelect">
            <option value="all" selected>Show All</option>
            <option value="success">Best Sellers</option>
            <option value="warning">Low Stock Alerts</option>
            <option value="danger">Declining Sales</option>
            <option value="info">General Info</option>
        </select>
    </div>

    {{-- Recommendations Grid --}}
    <div class="row" id="recommendationCards">
        @php
            $sorted = collect($recommendations)->sortBy(function($r) {
                return match($r['type']) {
                    'success' => 1,
                    'warning' => 2,
                    'danger'  => 3,
                    default   => 4
                };
            });

            function cardBorderClass($type) {
                return match($type) {
                    'success' => 'border-start border-4 border-success',
                    'warning' => 'border-start border-4 border-warning',
                    'danger'  => 'border-start border-4 border-danger',
                    'info'    => 'border-start border-4 border-info',
                    default   => 'border-start border-4 border-secondary',
                };
            }

            function cardTextClass($type) {
                return 'text-dark'; // Uniform for all types now
            }
        @endphp

        @foreach ($sorted as $recommendation)
            <div class="col-md-6 col-lg-4 mb-4 recommendation-card" data-type="{{ $recommendation['type'] }}">
                <div class="card shadow-sm h-100 bg-white {{ cardBorderClass($recommendation['type']) }}">
                    <div class="card-body {{ cardTextClass($recommendation['type']) }}">
                        <h5 class="card-title fw-bold">
                            @switch($recommendation['type'])
                                @case('success') 🌟 Best Seller @break
                                @case('warning') ⚠️ Low Stock @break
                                @case('danger') 📉 Declining Sales @break
                                @case('info') 📊 Info @break
                                @default 🧩 Note
                            @endswitch
                        </h5>
                        <p class="card-text mt-2">{!! $recommendation['message'] !!}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- Script for filtering --}}
@push('scripts')
<script>
    document.getElementById('filterSelect').addEventListener('change', function () {
        const selectedType = this.value;
        const cards = document.querySelectorAll('.recommendation-card');

        cards.forEach(card => {
            const type = card.getAttribute('data-type');
            card.style.display = (selectedType === 'all' || type === selectedType) ? 'block' : 'none';
        });
    });
</script>
@endpush
@endsection
