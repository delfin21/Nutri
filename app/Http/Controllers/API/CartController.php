<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
        public function addToCart(Request $request)
{
    $request->validate([
        'product_id' => 'required|integer|exists:products,id',
        'quantity' => 'required|integer|min:1',
    ]);

    $user = $request->user();

    // Check if product already in cart for user
    $cartItem = Cart::where('buyer_id', $user->id)
                    ->where('product_id', $request->product_id)
                    ->first();

    if ($cartItem) {
        // Update quantity
        $cartItem->quantity += $request->quantity;
        $cartItem->save();
    } else {
        // Create new cart item
        Cart::create([
            'buyer_id' => $user->id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
        ]);
    }

    return response()->json(['message' => 'Added to cart']);
}

    public function getCartItems()
{
    $user = Auth::user();

    $cartItems = Cart::with('product')
        ->where('buyer_id', $user->id)
        ->get()
        ->map(function ($item) {
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'price' => $item->product->price,
                'quantity' => $item->quantity,
                'image' => $item->product->image,
            ];
        });

    return response()->json($cartItems);
}
    public function updateCart(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $user = Auth::user();

        $cartItem = Cart::where('id', $id)->where('buyer_id', $user->id)->firstOrFail();
        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json(['message' => 'Cart item updated']);
    }

    public function removeCartItem($id)
    {
        $user = Auth::user();

        $cartItem = Cart::where('id', $id)->where('buyer_id', $user->id)->firstOrFail();
        $cartItem->delete();

        return response()->json(['message' => 'Cart item removed']);
    }

    // Add to cart method as before...
}


