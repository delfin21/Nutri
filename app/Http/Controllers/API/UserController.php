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
}
