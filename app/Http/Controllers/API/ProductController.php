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
     * Get all products (for mobile/web)
     */
    public function index()
    {
        $imageBaseUrl = env('APP_MOBILE_IMAGE_URL', 'http://127.0.0.1:8000');

        $products = Product::with('farmer')->get()->map(function ($product) use ($imageBaseUrl) {
            $imagePath = $product->image;

            if ($imagePath && str_starts_with($imagePath, 'http')) {
                $imagePath = preg_replace('/^https?:\/\/(127\.0\.0\.1|localhost|192\.168\.\d+\.\d+)/', $imageBaseUrl, $imagePath);
            } else if ($imagePath) {
                $imagePath = $imageBaseUrl . '/storage/' . $imagePath;
            } else {
                $imagePath = $imageBaseUrl . '/img/default-product.jpg';
            }

            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'stock' => $product->stock,
                'rating' => $product->rating ?? 0,
                'category' => $product->category,
                'image' => $imagePath,
                'farmer_name' => optional($product->farmer)->business_name ?? 'Unknown',
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ];
        });

        return response()->json(['products' => $products]);
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
