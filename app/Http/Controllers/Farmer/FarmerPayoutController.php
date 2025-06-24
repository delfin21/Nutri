<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\FarmerPayout;

class FarmerPayoutController extends Controller
{
    public function index()
    {
        $farmerId = Auth::id();
        $payouts = FarmerPayout::where('farmer_id', $farmerId)->latest()->get();

        return view('farmer.payouts.index', compact('payouts'));
    }
}
