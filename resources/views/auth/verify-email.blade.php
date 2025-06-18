@extends('layouts.app')

@section('title', 'Verify Email')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (session('status') === 'verification-link-sent')
                <div class="alert alert-success">
                    A new verification link has been sent to your email address.
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">Email Verification</div>

                <div class="card-body">
                    <p class="mb-3">
                        Before proceeding, please check your email for a verification link.
                        If you did not receive the email, you can request another one below.
                    </p>

                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            Resend Verification Email
                        </button>
                    </form>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary btn-sm">
                            Edit Profile
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
