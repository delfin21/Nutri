<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AdminAlertNotification;

class RegisteredUserController extends Controller
{
    /**
     * Show registration form.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        // Base validation
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&]/',
                'confirmed',
            ],
            'role' => ['required', 'in:admin,buyer,farmer'],
        ]);

        // Add payout validation if role is farmer
        if ($request->role === 'farmer') {
            $request->validate([
                'payout_method' => ['required', 'in:GCash,Bank,Maya'],
            ]);

            if (in_array($request->payout_method, ['GCash', 'Maya'])) {
                $request->validate([
                    'payout_account' => ['required', 'digits:11'],
                    'payout_name' => ['required', 'string', 'max:255'],
                ]);
            } elseif ($request->payout_method === 'Bank') {
                $request->validate([
                    'payout_bank' => ['required', 'string', 'max:100'],
                    'payout_bank_name' => ['required', 'string', 'max:255'],
                    'payout_bank_account' => ['required', 'string', 'max:30'],
                ]);
            }
        }

        // Prepare user data
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ];

        // Add payout fields if farmer
        if ($request->role === 'farmer') {
            $userData['payout_method'] = $request->payout_method;

            if (in_array($request->payout_method, ['GCash', 'Maya'])) {
                $userData['payout_account'] = $request->payout_account;
                $userData['payout_name'] = $request->payout_name;
            } elseif ($request->payout_method === 'Bank') {
                $userData['payout_bank'] = $request->payout_bank;
                $userData['payout_name'] = $request->payout_bank_name;
                $userData['payout_account'] = $request->payout_bank_account;
            }
        }

        $user = User::create($userData);

        event(new Registered($user));
        Auth::login($user);

        // Notify admins
        $admins = User::where('role', 'admin')->get();
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new AdminAlertNotification([
                'message' => 'New user registered: ' . $user->email,
                'link' => route('admin.users.index'),
                'icon' => 'bi-person',
                'type' => 'registration',
            ]));
        }

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'farmer' => redirect()->route('farmer.dashboard'),
            'buyer' => redirect()->route('buyer.dashboard'),
            default => redirect('/'),
        };
    }
}
