<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class FarmerProfileController extends Controller
{
    public function show()
    {
        $farmer = Auth::user()->fresh();
        return view('farmer.profile', compact('farmer'));
    }

    public function edit()
    {
        $farmer = Auth::user();
        return view('farmer.edit-profile', compact('farmer'));
    }

    public function update(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'nullable|string|max:20',
        'business_name' => 'nullable|string|max:255',
        'bio' => 'nullable|string|max:1000',
        'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'business_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $data = $request->only([
        'name', 'phone', 'business_name', 'bio',

    ]);

    // ✅ Handle profile photo
if ($request->hasFile('profile_photo')) {
    $filename = $request->file('profile_photo')->hashName();
    $request->file('profile_photo')->storeAs('profile_photos', $filename, 'public');
    $data['profile_photo'] = $filename;
}


    // ✅ Handle business photo
    if ($request->hasFile('business_photo')) {
    $data['business_photo'] = $request->file('business_photo')->storeAs(
        'business_photos',
        $request->file('business_photo')->hashName(),
        'public'
    );
    $data['business_photo'] = basename($data['business_photo']);


    }

    $user->update($data);

    return redirect()->route('farmer.profile.show')->with('success', 'Profile updated!');
}


    public function payout()
    {
        return view('farmer.profile-payout');
    }

    public function address()
    {
        return view('farmer.profile-address');
    }

public function updatePayout(Request $request)
{
    $user = Auth::user();

    $validated = $request->validate([
        'payout_method' => 'required|in:GCash,Bank,Maya',
        // GCash / Maya fields
        'payout_account' => 'required_if:payout_method,GCash,Maya|nullable|digits:11',
        'payout_name' => 'required_if:payout_method,GCash,Maya|nullable|string|max:255',

        // Bank fields
        'payout_bank' => 'required_if:payout_method,Bank|nullable|string|max:255',
        'payout_bank_account' => 'required_if:payout_method,Bank|nullable|string|max:50',
        'payout_bank_name' => 'required_if:payout_method,Bank|nullable|string|max:255',

        // Secondary optional
        'payout_method_secondary' => 'nullable|in:GCash,Bank,Maya',
        'payout_account_secondary' => 'nullable|string|max:50',
        'payout_name_secondary' => 'nullable|string|max:255',
        'secondary_bank_name' => 'nullable|string|max:255',
        'secondary_bank_account' => 'nullable|string|max:50',
        'secondary_bank_account_name' => 'nullable|string|max:255',
    ]);

    $user->update([
        'payout_method' => $request->payout_method,
        'payout_name' => $request->payout_method === 'Bank' ? $request->payout_bank_name : $request->payout_name,
        'payout_account' => $request->payout_method === 'Bank' ? $request->payout_bank_account : $request->payout_account,
        'payout_bank' => $request->payout_bank,

        'payout_method_secondary' => $request->payout_method_secondary,
        'payout_account_secondary' => $request->payout_method_secondary === 'Bank'
            ? $request->secondary_bank_account
            : $request->payout_account_secondary,
        'payout_name_secondary' => $request->payout_method_secondary === 'Bank'
            ? $request->secondary_bank_account_name
            : $request->payout_name_secondary,

        'secondary_bank_name' => $request->secondary_bank_name,
        
    ]);
    \Log::info('[PAYOUT DEBUG]', $request->all());

    return back()->with('success', 'Payout settings updated.');
}



    public function updateAddress(Request $request)
{
    $request->validate([
        'street' => 'required|string|max:100',
        'barangay' => 'required|string|max:100',
        'city' => 'required|string|max:100',
        'province' => 'required|string|max:100',
        'zip' => 'required|string|max:10',
    ]);

    

    $fullAddress = implode(', ', [
        $request->street,
        $request->barangay,
        $request->city,
        $request->province,
        $request->zip
    ]);

    $farmer = Auth::user();
    $farmer->update([
        'street'   => $request->street,
        'barangay' => $request->barangay,
        'city'     => $request->city,
        'province' => $request->province,
        'zip'      => $request->zip,
    ]);


    return back()->with('success', 'Address updated.');
}
}
