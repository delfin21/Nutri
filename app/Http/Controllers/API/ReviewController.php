<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use App\Models\Product;

class ReviewController extends Controller
{
    public function index(Product $product)
    {
        $ratings = Rating::with('buyer')
            ->where('product_id', $product->id)
            ->get();

        return response()->json($ratings->map(function ($rating) {
            return [
                'buyer_name' => $rating->buyer->name ?? 'Anonymous',
                'date' => $rating->created_at->format('M d, Y'),
                'content' => $rating->comment,
                'rating' => $rating->rating,
            ];
        }));
    }
}

