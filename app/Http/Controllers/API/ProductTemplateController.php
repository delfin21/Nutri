<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ProductTemplateController extends Controller
{
    public function getByCategory($category)
    {
        $templates = DB::table('product_templates')
            ->where('category', $category)
            ->orderBy('name')
            ->pluck('name');

        return response()->json($templates);
    }
}
