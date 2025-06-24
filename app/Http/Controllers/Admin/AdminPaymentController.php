<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\TransactionExport;
use Maatwebsite\Excel\Facades\Excel;

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

    public function markAsVerified(Payment $payment)
    {
        $payment->update(['is_verified' => true]);
        return response()->json(['status' => 'ok']);
    }

    public function exportPdf()
{
    $payments = Payment::with('buyer', 'orders')->latest()->get();
    $pdf = Pdf::loadView('admin.payments.pdf', compact('payments'));
    return $pdf->download('transaction.pdf');
}

public function exportCsv()
{
    return Excel::download(new TransactionExport, 'transaction.csv');
}

}

