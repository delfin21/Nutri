<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FarmerProfileController extends Controller
{
    // Get farmer profile info
       public function show(Request $request)
    {
        $user = Auth::user();

        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'business_name' => $user->business_name,
            'business_type' => $user->business_type,
            'payout_method' => $user->payout_method,
            'payout_details' => $user->payout_details,
            'street' => $user->street,
            'barangay' => $user->barangay,
            'city' => $user->city,
            'province' => $user->province,
            'zip' => $user->zip,
            'created_at' => $user->created_at->diffForHumans(),
        ]);
    }
    // Update farmer profile info
    public function update(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return response()->json(['message' => 'Profile updated successfully']);
    }
}
