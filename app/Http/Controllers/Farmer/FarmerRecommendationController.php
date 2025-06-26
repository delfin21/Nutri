<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;

class FarmerRecommendationController extends Controller
{
    public function index()
    {
        $farmerId = Auth::id();
        $cacheKey = 'recommendations_farmer_' . $farmerId;

        $recommendations = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($farmerId) {
            $now = now();
            $recommendations = [];

            // 🥇 Best-selling product (1-month)
            $sales = Order::where('farmer_id', $farmerId)
                ->where('created_at', '>=', $now->copy()->subMonth())
                ->select('product_id', DB::raw('SUM(quantity) as total_sold'))
                ->groupBy('product_id')
                ->orderByDesc('total_sold')
                ->get();

            if ($sales->count() > 0) {
                $topProduct = $sales->first();
                $productName = Product::find($topProduct->product_id)?->name ?? 'Unknown Product';
                $recommendations[] = [
                    'type' => 'success',
                    'message' => "🌟 Your <strong>{$productName}</strong> sold very well this month! Consider increasing production by 20% next month."
                ];
            }

            // 🟢 Mid-sellers (10+ sales)
            foreach ($sales->slice(1) as $midProduct) {
                if ($midProduct->total_sold >= 10) {
                    $productName = Product::find($midProduct->product_id)?->name ?? 'Unknown Product';
                    $recommendations[] = [
                        'type' => 'info',
                        'message' => "🟢 Your <strong>{$productName}</strong> had steady sales this month ({$midProduct->total_sold} sold). Maintain or slightly increase production."
                    ];
                }
            }

            // ⚠️ Frequently sold-out
            $soldOutProduct = Product::where('farmer_id', $farmerId)
                ->where('stock', 0)
                ->orderBy('updated_at', 'desc')
                ->first();

            if ($soldOutProduct) {
                $recommendations[] = [
                    'type' => 'warning',
                    'message' => "⚠️ You frequently run out of <strong>{$soldOutProduct->name}</strong>. Consider stocking more to meet demand."
                ];
            }

            // 📉 Low-selling products
            $lowSellingProducts = $sales->filter(fn($item) => $item->total_sold < 5);
            foreach ($lowSellingProducts as $lowProduct) {
                $name = Product::find($lowProduct->product_id)?->name ?? 'Unknown Product';
                $recommendations[] = [
                    'type' => 'danger',
                    'message' => "📉 Your <strong>{$name}</strong> had low sales this month ({$lowProduct->total_sold} sold). Consider reducing its cultivation next cycle."
                ];
            }

            // 📦 Low stock alert (≤ 25 kilos)
            $lowStockProducts = Product::where('farmer_id', $farmerId)
                ->where('stock', '<=', 25)
                ->get();

            foreach ($lowStockProducts as $p) {
                $recommendations[] = [
                    'type' => 'warning',
                    'message' => "📦 Low stock alert: <strong>{$p->name}</strong> only has <strong>{$p->stock} kilos</strong> left. Consider replenishing soon."
                ];
            }

            // 🔁 Repeat buyer alert
            $repeatBuyers = Order::where('farmer_id', $farmerId)
                ->select('product_id', 'buyer_id', DB::raw('COUNT(*) as order_count'))
                ->groupBy('product_id', 'buyer_id')
                ->having('order_count', '>', 1)
                ->get()
                ->groupBy('product_id');

            foreach ($repeatBuyers as $productId => $buyers) {
                $productName = Product::find($productId)?->name ?? 'Unknown Product';
                $count = $buyers->count();
                $recommendations[] = [
                    'type' => 'info',
                    'message' => "🔁 <strong>{$count}</strong> buyer(s) reordered <strong>{$productName}</strong>. This indicates strong customer interest — consider promoting it."
                ];
            }

            // 📉 Declining trend (MoM drop)
            $lastMonth = now()->subMonth();
            $twoMonthsAgo = now()->subMonths(2);

            $products = Product::where('farmer_id', $farmerId)->get();

            foreach ($products as $product) {
                $salesLast = Order::where('product_id', $product->id)
                    ->whereMonth('created_at', $lastMonth->month)
                    ->sum('quantity');

                $salesPrev = Order::where('product_id', $product->id)
                    ->whereMonth('created_at', $twoMonthsAgo->month)
                    ->sum('quantity');

                if ($salesPrev > 0 && $salesLast < $salesPrev) {
                    $drop = $salesPrev - $salesLast;
                    $recommendations[] = [
                        'type' => 'danger',
                        'message' => "📉 Sales for <strong>{$product->name}</strong> dropped by <strong>{$drop}</strong> units compared to the previous month. Consider investigating."
                    ];
                }
            }

            // 💰 Revenue per product (top 3)
            $revenues = Order::where('farmer_id', $farmerId)
                ->select('product_id', DB::raw('SUM(total_price) as revenue'))
                ->groupBy('product_id')
                ->orderByDesc('revenue')
                ->take(3)
                ->get();

            foreach ($revenues as $rev) {
                $productName = Product::find($rev->product_id)?->name ?? 'Unknown Product';
                $recommendations[] = [
                    'type' => 'info',
                    'message' => "💰 <strong>{$productName}</strong> earned <strong>₱" . number_format($rev->revenue, 2) . "</strong> in total revenue."
                ];
            }

            if (empty($recommendations)) {
                $recommendations[] = [
                    'type' => 'info',
                    'message' => "📊 No specific recommendations available yet due to limited recent data."
                ];
            }

            return $recommendations;
        });

        return view('farmer.recommendations.index', compact('recommendations'));
    }

    public function clearCache()
    {
        $farmerId = Auth::id();
        $cacheKey = 'recommendations_farmer_' . $farmerId;

        Cache::forget($cacheKey);

        return redirect()->route('farmer.recommendations.index')
            ->with('success', 'Recommendations refreshed successfully!');
    }

    public function downloadPdf()
    {
        $farmerId = Auth::id();
        $cacheKey = 'recommendations_farmer_' . $farmerId;

        $recommendations = Cache::get($cacheKey) ?? [];

        if (empty($recommendations)) {
            $recommendations[] = [
                'type' => 'info',
                'message' => "📊 No specific recommendations available yet due to limited recent data."
            ];
        }

        $pdf = Pdf::loadView('farmer.recommendations.pdf', compact('recommendations'));
        return $pdf->download('prescriptive-recommendations.pdf');
    }
}
