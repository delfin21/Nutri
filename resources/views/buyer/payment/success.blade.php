@extends('layouts.app')

@section('content')
<div class="container text-center py-5">
    <h2 class="text-success">Payment Successful!</h2>
    <p class="lead">Thank you for your purchase. Your payment has been confirmed.</p>
    <a href="{{ route('buyer.payments.receipt', ['payment' => $payment->id]) }}" class="btn btn-success mt-3">View Receipt</a>
</div>
@endsection
