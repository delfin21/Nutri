<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesExport;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date') ?? Carbon::now()->subMonth()->toDateString();
        $endDate = $request->input('end_date') ?? Carbon::now()->toDateString();
        $category = $request->input('category');
        $product = $request->input('product');

        // Fetch unique category names from the products table
        $categories = Product::select('category')
            ->distinct()
            ->whereNotNull('category')
            ->orderBy('category')
            ->pluck('category');

        $ordersQuery = Order::with(['product.farmer', 'buyer'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed');

        if ($category) {
            $ordersQuery->whereHas('product', function ($q) use ($category) {
                $q->where('category', $category);
            });
        }

        if ($product) {
            $ordersQuery->whereHas('product', function ($q) use ($product) {
                $q->where('name', 'like', '%' . $product . '%');
            });
        }

        $orders = $ordersQuery->paginate(10);

        return view('admin.reports.index', compact('orders', 'startDate', 'endDate', 'categories', 'category', 'product'));
    }

    public function export(Request $request)
    {
        $startDate = $request->input('start_date') ?? Carbon::now()->subMonth()->toDateString();
        $endDate = $request->input('end_date') ?? Carbon::now()->toDateString();
        $category = $request->input('category');
        $product = $request->input('product');

        $ordersQuery = Order::with(['product.farmer', 'buyer'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed');

        if ($category) {
            $ordersQuery->whereHas('product', function ($q) use ($category) {
                $q->where('category', $category);
            });
        }

        if ($product) {
            $ordersQuery->whereHas('product', function ($q) use ($product) {
                $q->where('name', 'like', '%' . $product . '%');
            });
        }

        $orders = $ordersQuery->get();

        return Excel::download(new SalesExport($orders), 'sales_report.xlsx');
    }
}
