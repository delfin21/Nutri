@extends('layouts.farmer')

@section('title', 'Help & Tutorials')

@section('content')
<div class="container">
    <h2 class="mb-4">📹 Help & Tutorial Videos</h2>
    <p>Welcome, farmer! Watch the videos below to learn how to use NutriApp effectively.</p>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="ratio ratio-16x9">
                    <iframe src="https://www.youtube.com/embed/1hQi_zfna0Q" title="Tutorial 1" allowfullscreen></iframe>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Getting Started with NutriApp</h5>
                    <p class="card-text">Learn how to register, log in, and explore the dashboard.</p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="ratio ratio-16x9">
                    <iframe src="https://www.youtube.com/embed/ZR5MLUJZYTk" title="Tutorial 2" allowfullscreen></iframe>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Managing Your Products</h5>
                    <p class="card-text">Add, update, and manage your farm products.</p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="ratio ratio-16x9">
                    <iframe src="https://www.youtube.com/embed/KTYIQ1Wz7Dg" title="Tutorial 3" allowfullscreen></iframe>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Handling Orders & Returns</h5>
                    <p class="card-text">View and manage customer orders and handle return requests.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
