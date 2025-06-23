<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FarmerVerificationApiController extends Controller
{
    public function verify(Request $request)
    {

    $request->validate([
        'document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120'
    ]);

    $user = $request->user();

    // Save file
    $path = $request->file('document')->store('verification_docs', 'public');

    // Update user record
    $user->verification_document = $path;
    $user->verification_status = 'pending';
    $user->save();

    return response()->json(['message' => 'Verification document submitted.']);
}
}
