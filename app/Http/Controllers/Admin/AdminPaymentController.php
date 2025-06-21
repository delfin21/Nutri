<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;

class AdminPaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('buyer')->latest()->paginate(20);
        return view('admin.payments.index', compact('payments'));
    }
    public function show($id)
    {
        $payment = Payment::with('buyer')->findOrFail($id);
        return view('admin.payments.partials.show', compact('payment'));
    }

}

