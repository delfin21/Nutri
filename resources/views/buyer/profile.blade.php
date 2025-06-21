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
                            Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('buyer.profile.show', ['tab' => 'payments']) }}"
                           class="nav-link {{ request('tab') === 'payments' ? 'fw-bold text-primary' : 'text-dark' }}">
                            Payments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('buyer.profile.show', ['tab' => 'address']) }}"
                           class="nav-link {{ request('tab') === 'address' ? 'fw-bold text-primary' : 'text-dark' }}">
                            Address
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('buyer.profile.show', ['tab' => 'password']) }}"
                           class="nav-link {{ request('tab') === 'password' ? 'fw-bold text-primary' : 'text-dark' }}">
                            Change Password
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('buyer.orders.history') }}"
                           class="nav-link {{ request()->is('buyer/orders/history') ? 'fw-bold text-success' : 'text-dark' }}">
                            <i class="bi bi-clipboard-check me-1"></i> My Purchase
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('buyer.orders.history', ['status' => 'Return/Refund']) }}"
                        class="nav-link {{ request()->fullUrlIs('*Return*') ? 'fw-bold text-warning' : 'text-dark' }}">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> My Returns
                        </a>
                    </li>

                    {{-- Optional Store Credit tab (if implementing store credit tracking) --}}
                    <li class="nav-item">
                        <a href="{{ route('buyer.profile.show', ['tab' => 'store_credit']) }}"
                        class="nav-link {{ request('tab') === 'store_credit' ? 'fw-bold text-primary' : 'text-dark' }}">
                            <i class="bi bi-wallet2 me-1"></i> Store Credits
                        </a>
                    </li>

                </ul>
            </div>
        </div>

        <!-- Right Section: Dynamic Based on Tab -->
        <div class="col-md-9">
        @if (request('tab') === 'payments')
            @include('buyer.profile.sections.payments')
        @elseif (request('tab') === 'address')
            @include('buyer.profile.sections.address')
        @elseif (request('tab') === 'password')
            @include('buyer.profile.sections.password')
        @elseif (request('tab') === 'store_credit')
            @include('buyer.profile.sections.store_credit')
        @else
            @include('buyer.profile.sections.profile')
        @endif
        </div>
    </div>
</div>
@endsection
