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

    {{-- Utility Functions --}}
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
            return 'text-dark';
        }
    @endphp

    {{-- SECTION: Success Cards --}}
    <h4 class="mt-4 mb-3 text-success">🌟 Best Sellers / Positive Trends</h4>
    <div class="row" id="recommendationCards">
        @foreach ($sorted->where('type', 'success') as $recommendation)
            <div class="col-md-6 col-lg-4 mb-4 recommendation-card" data-type="success">
                <div class="card shadow-sm h-100 bg-white {{ cardBorderClass('success') }}">
                    <div class="card-body {{ cardTextClass('success') }}">
                        <h5 class="card-title fw-bold">🌟 Best Seller</h5>
                        <p class="card-text mt-2">{!! $recommendation['message'] !!}</p>
                    </div>
                </div>
            </div>
        @endforeach

        {{-- New: Buyer Growth (Success) --}}
        <div class="col-md-6 col-lg-4 mb-4 recommendation-card" data-type="success">
            <div class="card shadow-sm h-100 bg-white {{ cardBorderClass('success') }}">
                <div class="card-body {{ cardTextClass('success') }}">
                    <h5 class="card-title fw-bold">👥 New Buyer Growth</h5>
                    <p class="card-text mt-2">You gained <strong>4 new buyers</strong> this week. Keep it up! Engaging with buyers and offering quality products builds loyalty.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION: Warning Cards --}}
    <h4 class="mt-4 mb-3 text-warning">⚠️ Inventory & Sales Alerts</h4>
    <div class="row">
        @foreach ($sorted->where('type', 'warning') as $recommendation)
            <div class="col-md-6 col-lg-4 mb-4 recommendation-card" data-type="warning">
                <div class="card shadow-sm h-100 bg-white {{ cardBorderClass('warning') }}">
                    <div class="card-body {{ cardTextClass('warning') }}">
                        <h5 class="card-title fw-bold">⚠️ Low Stock</h5>
                        <p class="card-text mt-2">{!! $recommendation['message'] !!}</p>
                    </div>
                </div>
            </div>
        @endforeach

        {{-- New: Overstock --}}
        <div class="col-md-6 col-lg-4 mb-4 recommendation-card" data-type="warning">
            <div class="card shadow-sm h-100 bg-white {{ cardBorderClass('warning') }}">
                <div class="card-body {{ cardTextClass('warning') }}">
                    <h5 class="card-title fw-bold">📦 Overstock Alert</h5>
                    <p class="card-text mt-2">You have 50kg of Sayote unsold for over 2 weeks. Consider offering a bundle discount or flash sale to move inventory.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION: Danger Cards --}}
    <h4 class="mt-4 mb-3 text-danger">📉 Declining Trends</h4>
    <div class="row">
        @foreach ($sorted->where('type', 'danger') as $recommendation)
            <div class="col-md-6 col-lg-4 mb-4 recommendation-card" data-type="danger">
                <div class="card shadow-sm h-100 bg-white {{ cardBorderClass('danger') }}">
                    <div class="card-body {{ cardTextClass('danger') }}">
                        <h5 class="card-title fw-bold">📉 Declining Sales</h5>
                        <p class="card-text mt-2">{!! $recommendation['message'] !!}</p>
                    </div>
                </div>
            </div>
        @endforeach

        {{-- New: Return Rate --}}
        <div class="col-md-6 col-lg-4 mb-4 recommendation-card" data-type="danger">
            <div class="card shadow-sm h-100 bg-white {{ cardBorderClass('danger') }}">
                <div class="card-body {{ cardTextClass('danger') }}">
                    <h5 class="card-title fw-bold">🧾 High Return Rate</h5>
                    <p class="card-text mt-2">Ampalaya had <strong>3 product returns</strong> this month. Consider checking product quality or packaging before shipping.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION: Info Cards --}}
    <h4 class="mt-4 mb-3 text-info">📊 General Platform Insights</h4>
    <div class="row">
        @foreach ($sorted->where('type', 'info') as $recommendation)
            <div class="col-md-6 col-lg-4 mb-4 recommendation-card" data-type="info">
                <div class="card shadow-sm h-100 bg-white {{ cardBorderClass('info') }}">
                    <div class="card-body {{ cardTextClass('info') }}">
                        <h5 class="card-title fw-bold">📊 Info</h5>
                        <p class="card-text mt-2">{!! $recommendation['message'] !!}</p>
                    </div>
                </div>
            </div>
        @endforeach

        {{-- New: Seasonal Trend --}}
        <div class="col-md-6 col-lg-4 mb-4 recommendation-card" data-type="info">
            <div class="card shadow-sm h-100 bg-white {{ cardBorderClass('info') }}">
                <div class="card-body {{ cardTextClass('info') }}">
                    <h5 class="card-title fw-bold">🗓️ Seasonal Trend Alert</h5>
                    <p class="card-text mt-2">Rainy season is coming! Ginger and Turmeric demand typically rises during this time. Consider preparing your supply.</p>
                </div>
            </div>
        </div>

        {{-- New: Page Abandonment --}}
        <div class="col-md-6 col-lg-4 mb-4 recommendation-card" data-type="info">
            <div class="card shadow-sm h-100 bg-white {{ cardBorderClass('info') }}">
                <div class="card-body {{ cardTextClass('info') }}">
                    <h5 class="card-title fw-bold">📣 Product Page Abandonment</h5>
                    <p class="card-text mt-2">Buyers often view your <strong>Pechay</strong> product but don’t purchase. Consider improving the product photo or lowering the price slightly.</p>
                </div>
            </div>
        </div>

        {{-- New: Popular Bundle Suggestion --}}
        <div class="col-md-6 col-lg-4 mb-4 recommendation-card" data-type="info">
            <div class="card shadow-sm h-100 bg-white {{ cardBorderClass('info') }}">
                <div class="card-body {{ cardTextClass('info') }}">
                    <h5 class="card-title fw-bold">🛒 Popular Bundle Suggestion</h5>
                    <p class="card-text mt-2">Buyers frequently order <strong>Tomato and Onion</strong> together. You could offer a “Sinigang Pack” to boost sales.</p>
                </div>
            </div>
        </div>

        {{-- New: Most Viewed Products --}}
        <div class="col-md-6 col-lg-4 mb-4 recommendation-card" data-type="info">
            <div class="card shadow-sm h-100 bg-white {{ cardBorderClass('info') }}">
                <div class="card-body {{ cardTextClass('info') }}">
                    <h5 class="card-title fw-bold">🔍 Most Viewed Products</h5>
                    <p class="card-text mt-2">These products are getting the most attention by <strong>39% of users</strong>:<br>
                    • Onion<br>• Papaya<br>• Calamansi<br><br>
                    <strong>If you’re not offering these, consider adding them!</strong></p>
                </div>
            </div>
        </div>

        {{-- New: Most Searched Term --}}
        <div class="col-md-6 col-lg-4 mb-4 recommendation-card" data-type="info">
            <div class="card shadow-sm h-100 bg-white {{ cardBorderClass('info') }}">
                <div class="card-body {{ cardTextClass('info') }}">
                    <h5 class="card-title fw-bold">🔎 Most Searched Crop</h5>
                    <p class="card-text mt-2"><strong>Okra</strong> is one of the most searched crops on the platform. If you grow it, consider posting it now to increase sales.</p>
                </div>
            </div>
        </div>

        {{-- New: Repeat Visitors --}}
        <div class="col-md-6 col-lg-4 mb-4 recommendation-card" data-type="info">
            <div class="card shadow-sm h-100 bg-white {{ cardBorderClass('info') }}">
                <div class="card-body {{ cardTextClass('info') }}">
                    <h5 class="card-title fw-bold">🔁 Returning Buyers</h5>
                    <p class="card-text mt-2">25% of your page views are from returning buyers — great engagement! Consider adding loyalty incentives or thank-you notes to encourage repeat purchases.</p>
                </div>
            </div>
        </div>
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
