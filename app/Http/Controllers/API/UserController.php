<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Return a list of all “opposite-role” users:
     * - If authenticated user is buyer, return all farmers
     * - If authenticated user is farmer, return all buyers
     *
     * Response format: [ { "id": 12, "name": "Farmer Juan" }, { ... } ]
     */
    public function listOppositeUsers()
    {
        $user = Auth::user();

        if ($user->role === 'buyer') {
            $roleToFetch = 'farmer';
        } elseif ($user->role === 'farmer') {
            $roleToFetch = 'buyer';
        } else {
            return response()->json(['message' => 'Unknown role'], 400);
        }

        // Grab only id & name for the dropdown/list
        $users = User::where('role', $roleToFetch)->get(['id','name']);

        return response()->json($users);
    }
    public function profile(Request $request)
{
    $user = $request->user();

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
}
