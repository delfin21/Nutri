<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    public function index()
    {
        $products = Auth::user()->products;
        return view('farmer.products.index', compact('products'));
    }

    public function create()
    {
        return view('farmer.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $productData = $request->only(['name', 'description', 'price', 'stock']);

        if ($request->hasFile('image')) {
            $filename = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/products', $filename);
            $productData['image'] = 'products/' . $filename;
        }

        Auth::user()->products()->create($productData);

        return redirect()->route('farmer.products.index')
                         ->with('success', 'Product created successfully.');
    }

    public function show(Request $request)
    {
        $tab = $request->query('tab', 'profile');
        $user = Auth::user();

        $payment = null;
        $orders = collect();
        $returns = collect();
        $payments = collect(); // <- Add this

        if ($tab === 'purchase') {
            $orders = $user->orders()->with(['product', 'rating', 'returnRequest', 'farmer'])->latest()->get();
        }

        if ($tab === 'return') {
            $returns = \App\Models\ReturnRequest::with(['order.product'])
                        ->whereHas('order', fn($q) => $q->where('buyer_id', $user->id))
                        ->latest()
                        ->get();
        }

        if ($tab === 'receipts') {
            $payments = \App\Models\Payment::where('buyer_id', $user->id)
                        ->where('is_verified', true)
                        ->latest()
                        ->get();

            // 👉 Load selected payment if payment_id exists
            if ($request->has('payment_id')) {
                $payment = $payments->firstWhere('id', $request->payment_id);
                if ($payment) {
                    $orders = $payment->orders;
                }
            }
        }




        return view('buyer.profile', compact('user', 'tab', 'payment', 'orders', 'returns', 'payments'));
    }



public function update(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'phone' => 'nullable|string|max:20', 
        'address' => 'nullable|string|max:255',
    ]);

    $user->update($request->only('name', 'email', 'phone', 'address'));
    return back()->with('success', 'Profile updated successfully.');
}
public function edit()
{
    return view('buyer.profile.edit');
}
public function updateAddress(Request $request)
{
    $request->validate([
        'address' => 'required|string|max:255',
    ]);

    $user = Auth::user();
    $user->address = $request->address;
    $user->save();

    return redirect()->route('buyer.profile.show', ['tab' => 'address'])
                     ->with('address_success', 'Address updated successfully.');
}
public function updatePassword(Request $request)
{
    $request->validate([
        'current_password' => ['required'],
        'new_password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    $user = Auth::user();

    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'The current password is incorrect.']);
    }

    $user->password = Hash::make($request->new_password);
    $user->save();

    return redirect()->route('buyer.profile.show', ['tab' => 'password'])
                     ->with('password_success', 'Password changed successfully.');
}

}