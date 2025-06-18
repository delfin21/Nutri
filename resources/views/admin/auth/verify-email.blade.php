@extends('layouts.auth')

@section('title', 'Verify Email')

@section('body-class', 'login-page')

@section('content')
<div class="nutri-login-container">
    <div class="nutri-login-box">

        <!-- Branding Sidebar -->
        <div class="nutri-login-sidebar">
            <img src="{{ asset('img/nutriteam-logo.png') }}" alt="NutriApp Logo">
            <h3>Welcome to NutriHub</h3>
            <p>Confirm your email to access the admin panel securely.</p>
        </div>

        <!-- Form Content -->
        <div class="nutri-login-form text-center">
            <h4 class="mb-3">Verify Your Admin Email</h4>

            @if (session('status') === 'verification-link-sent')
                <div class="alert alert-success">
                    ✅ A new verification link has been sent to your email address.
                </div>
            @endif

            <p>Please check your email for the verification link.</p>
            <p>If you didn’t receive one, you may request another below:</p>

            <form method="POST" action="{{ route('admin.verification.send') }}" class="mb-3 mt-3">
                @csrf
                <button type="submit" class="btn btn-nutri w-100">Resend Verification Email</button>
            </form>

            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100">Logout</button>
            </form>
        </div>
    </div>
</div>
@endsection