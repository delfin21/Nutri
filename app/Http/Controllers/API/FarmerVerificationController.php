<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FarmerVerificationRequest;
use Illuminate\Support\Facades\Storage;

class FarmerVerificationController extends Controller
{
    // POST /api/farmer/verify
    public function verify(Request $request)
    {
        $request->validate([
            'document' => 'required|image|max:2048',
        ]);

        $path = $request->file('document')->store('verification_docs', 'public');

        FarmerVerificationRequest::create([
            'user_id' => auth()->id(),
            'document_path' => $path,
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        return response()->json(['message' => 'Verification document submitted.'], 200);
    }

    // GET /api/farmer/my-verification
    public function myRequest()
    {
        $request = FarmerVerificationRequest::where('user_id', auth()->id())->latest()->first();

        return response()->json([
            'data' => $request,
        ]);
    }
}
