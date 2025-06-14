<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    // Valid categories for dropdowns & validation
    protected $validCategories = ['Fruits', 'Vegetable', 'Grains', 'Spices', 'Beverages'];

    /**
     * Get all products (basic listing, for admin or debug)
     */
    public function index()
    {
        $products = Product::all();

        return response()->json([
            'products' => $products,
        ]);
    }

    /**
     * Show a product by ID with relationships
     */
    public function show($id)
    {
        $product = Product::with('farmer', 'reviews')->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json([
            'product' => $product,
        ]);
    }

    /**
     * Get product names grouped by category ID (used in Flutter dropdown)
     */
    public function getByCategory($id)
    {
        $products = Product::where('category_id', $id)
            ->select('name')
            ->groupBy('name')
            ->orderBy('name')
            ->get();

        return response()->json($products);
    }

    /**
     * Get list of valid category names (used in mobile/web dropdowns)
     */
    public function getValidCategories()
    {
        return response()->json($this->validCategories);
    }
}
