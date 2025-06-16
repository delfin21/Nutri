<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class FarmerProductController extends Controller
{
public function index(Request $request)
{
    $user = $request->user();

    $products = Product::where('farmer_id', $user->id)
        ->when($request->q, function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->q . '%');
        })
        ->orderBy('created_at', 'desc') // Optional: makes ordering consistent
        ->get();

    return response()->json($products);
}
public function store(Request $request)
{
    $validated = $request->validate([
    'name' => 'required|string',
    'category' => 'required|string',
    'price' => 'required|numeric',
    'stock' => 'required|integer',
    'province' => 'required|string',
    'city' => 'required|string',
    'harvested_at' => 'required|date',
    'ripeness' => 'required|string',
    'shelf_life' => 'nullable|string',
    'storage_instructions' => 'nullable|string',
    'description' => 'nullable|string',
    'image' => 'nullable|image|max:2048',
    ]);

    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('products', 'public');
        $validated['image'] = $path;
    }

    $validated['farmer_id'] = $request->user()->id;
    Product::create($validated);

    return response()->json(['message' => 'Product created'], 201);
}


public function update(Request $request, $id)
{
    $product = Product::where('farmer_id', $request->user()->id)->findOrFail($id);

    $product->update($request->only(['name', 'price', 'stock']));

    return response()->json(['message' => 'Product updated successfully']);
}
public function destroy($id)
{
    $product = Product::where('farmer_id', auth()->id())->findOrFail($id);
    $product->delete();

    return response()->json(['message' => 'Product deleted successfully']);
}
}
