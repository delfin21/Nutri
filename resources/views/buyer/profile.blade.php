@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 px-4 py-3">
            <div class="text-center bg-white p-3 rounded shadow-sm">
                <!-- Profile Avatar -->
                @php
                    $initial = strtoupper(substr(Auth::user()->name, 0, 1));
                @endphp

                <div class="d-flex justify-content-center">
                    @if (Auth::user()->profile_photo_path)
                        <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}"
                             class="rounded-circle mb-2"
                             width="100"
                             height="100"
                             alt="User Photo">
                    @else
                        <div class="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center mb-2"
                             style="width:100px; height:100px; font-size:32px;">
                            {{ $initial }}
                        </div>
                    @endif
                </div>

                <h6 class="fw-bold mt-2 mb-3"><i class="bi bi-person-circle me-1"></i> My Account</h6>

                <!-- Navigation Links -->
                <ul class="nav flex-column gap-2 text-start">
                    <li class="nav-item">
                        <a href="{{ route('buyer.profile.show') }}"
                        class="nav-link {{ request('tab') === null ? 'fw-bold text-primary' : 'text-dark' }}">
                            <i class="bi bi-person-circle me-1"></i> Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('buyer.profile.show', ['tab' => 'payments']) }}"
                        class="nav-link {{ request('tab') === 'payments' ? 'fw-bold text-primary' : 'text-dark' }}">
                            <i class="bi bi-credit-card me-1"></i> Payments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('buyer.profile.show', ['tab' => 'address']) }}"
                        class="nav-link {{ request('tab') === 'address' ? 'fw-bold text-primary' : 'text-dark' }}">
                            <i class="bi bi-geo-alt me-1"></i> Address
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('buyer.profile.show', ['tab' => 'password']) }}"
                        class="nav-link {{ request('tab') === 'password' ? 'fw-bold text-primary' : 'text-dark' }}">
                            <i class="bi bi-shield-lock me-1"></i> Change Password
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('buyer.profile.show', ['tab' => 'purchase']) }}"
                        class="nav-link {{ request('tab') === 'purchase' ? 'fw-bold text-success' : 'text-dark' }}">
                            <i class="bi bi-clipboard-check me-1"></i> My Purchase
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('buyer.profile.show', ['tab' => 'return']) }}"
                        class="nav-link {{ request('tab') === 'return' ? 'fw-bold text-warning' : 'text-dark' }}">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> My Returns
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('buyer.profile.show', ['tab' => 'store_credit']) }}"
                        class="nav-link {{ request('tab') === 'store_credit' ? 'fw-bold text-primary' : 'text-dark' }}">
                            <i class="bi bi-wallet2 me-1"></i> Store Credits
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('buyer.profile.show', ['tab' => 'receipts']) }}"
                        class="nav-link {{ request('tab') === 'receipts' ? 'fw-bold text-primary' : 'text-dark' }}">
                            <i class="bi bi-receipt-cutoff me-1"></i> My Receipts
                        </a>
                    </li>
                </ul>

            </div>
        </div>

        <!-- Right Section: Display content based on the active tab -->
        <div class="col-md-9">
            @if (request('tab') === 'payments')
                @include('buyer.profile.sections.payments')
            @elseif (request('tab') === 'address')
                @include('buyer.profile.sections.address')
            @elseif (request('tab') === 'password')
                @include('buyer.profile.sections.password')
            @elseif (request('tab') === 'store_credit')
                @include('buyer.profile.sections.store_credit')
            @elseif (request('tab') === 'receipts')
                @include('buyer.profile.sections.receipt')
            @elseif (request('tab') === 'purchase')
                @include('buyer.profile.sections.purchase')
            @elseif (request('tab') === 'return')
                @include('buyer.profile.sections.return')
            @else
                @include('buyer.profile.sections.profile')
            @endif
        </div>
    </div>
</div>
@endsection
