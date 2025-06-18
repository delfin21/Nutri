@extends('layouts.auth')

@section('title', 'Admin Register')

@section('body-class', 'login-page')

@section('content')
<div class="nutri-login-container">
    <div class="nutri-login-box">
        <!-- Sidebar / Branding -->
        <div class="nutri-login-sidebar">
            <img src="{{ asset('img/nutriteam-logo.png') }}" alt="NutriApp Logo">
            <h3>Welcome to NutriHub</h3>
            <p>Manage your agricultural marketplace with confidence.</p>
        </div>

        <!-- Registration Form -->
        <div class="nutri-login-form">
            <h4 class="mb-4">Register a new Admin account</h4>

            @if ($errors->any())
                <div class="alert alert-danger"><strong>{{ $errors->first() }}</strong></div>
            @endif

            <form method="POST" action="{{ route('admin.register.store') }}">
                @csrf

                <div class="mb-3 nutri-form-group">
                    <label for="name" class="form-label">Full name</label>
                    <input type="text" name="name" id="name"
                           value="{{ old('name') }}"
                           class="form-control @error('name') is-invalid @enderror"
                           placeholder="John Admin" required autofocus>
                    @error('name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 nutri-form-group">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" name="email" id="email"
                           value="{{ old('email') }}"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="admin@example.com" required>
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 nutri-form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="********" required>
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 nutri-form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation"
                           class="form-control"
                           placeholder="Repeat password" required>
                </div>

                <button type="submit" class="btn btn-nutri w-100">Register</button>
            </form>

            <div class="text-center mt-3">
                <a href="{{ route('admin.login') }}" class="text-muted">Already have an account? Login here</a>
            </div>
        </div>
    </div>
</div>
@endsection
