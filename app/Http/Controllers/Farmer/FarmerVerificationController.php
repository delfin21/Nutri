<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\FarmerDocument;
use Illuminate\Support\Facades\Auth;

class FarmerVerificationController extends Controller
{
    public function index()
    {
        $documents = FarmerDocument::where('farmer_id', Auth::id())->latest()->get();
        return view('farmer.verification.index', compact('documents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:jpeg,png,pdf,jpg|max:2048',
        ]);

        $path = $request->file('document')->store('verification_documents', 'public');

        FarmerDocument::create([
            'farmer_id' => Auth::id(),
            'document_path' => $path,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Document submitted. Please wait for admin approval.');
    }
public function verify(Request $request)
    {
        $request->validate([
            'document' => 'required|image|max:2048',
        ]);

        $user = $request->user();

        // Save document to storage
        $path = $request->file('document')->store('verification_docs', 'public');

        // Save to FarmerDocument model (shared with web)
        FarmerDocument::create([
            'farmer_id' => $user->id,
            'document_path' => $path,
            'status' => 'pending',
            'admin_note' => null,
        ]);

        return response()->json(['message' => 'Document submitted for verification']);
    }
}


