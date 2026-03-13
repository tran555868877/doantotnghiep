<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BehaviorEvent;
use App\Models\Category;
use App\Models\CustomerScore;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\BehaviorAnalyticsService;

class DashboardController extends Controller
{
    public function __invoke(BehaviorAnalyticsService $analytics)
    {
        $analytics->calculateAll();

        $period = collect(range(29, 0))
            ->map(fn ($days) => now()->subDays($days)->toDateString());

        $eventByDay = BehaviorEvent::query()
            ->selectRaw('DATE(occurred_at) as event_date, COUNT(*) as total')
            ->whereDate('occurred_at', '>=', now()->subDays(29))
            ->groupBy('event_date')
            ->pluck('total', 'event_date');

        $ordersByDay = Order::query()
            ->selectRaw('DATE(ordered_at) as order_date, COUNT(*) as total')
            ->whereDate('ordered_at', '>=', now()->subDays(29))
            ->groupBy('order_date')
            ->pluck('total', 'order_date');

        $revenueByDay = Order::query()
            ->selectRaw('DATE(ordered_at) as order_date, SUM(grand_total) as total')
            ->whereDate('ordered_at', '>=', now()->subDays(29))
            ->groupBy('order_date')
            ->pluck('total', 'order_date');

        $segmentDistribution = CustomerScore::query()
            ->selectRaw('segment, COUNT(*) as total')
            ->groupBy('segment')
            ->pluck('total', 'segment');

        $retentionBuckets = [
            '0-30%' => CustomerScore::query()->where('retention_probability', '<=', 30)->count(),
            '31-60%' => CustomerScore::query()->whereBetween('retention_probability', [31, 60])->count(),
            '61-80%' => CustomerScore::query()->whereBetween('retention_probability', [61, 80])->count(),
            '81-100%' => CustomerScore::query()->where('retention_probability', '>=', 81)->count(),
        ];

        return view('admin.dashboard', [
            'stats' => [
                'products' => Product::count(),
                'orders' => Order::count(),
                'customers' => User::where('role', 'customer')->count(),
                'revenue' => Order::sum('grand_total'),
            ],
            'topCategories' => Category::withCount('products')->orderByDesc('products_count')->limit(8)->get(),
            'topProducts' => Product::orderByDesc('sold_count')->limit(8)->get(),
            'latestOrders' => Order::latest()->limit(8)->get(),
            'customerScores' => CustomerScore::with(['user', 'favoriteCategory'])->latest('calculated_at')->limit(10)->get(),
            'eventBreakdown' => BehaviorEvent::selectRaw('event_type, count(*) as total')->groupBy('event_type')->pluck('total', 'event_type'),
            'chartData' => [
                'labels' => $period->map(fn ($date) => \Carbon\Carbon::parse($date)->format('d/m'))->values(),
                'eventsByDay' => $period->map(fn ($date) => (int) ($eventByDay[$date] ?? 0))->values(),
                'ordersByDay' => $period->map(fn ($date) => (int) ($ordersByDay[$date] ?? 0))->values(),
                'revenueByDay' => $period->map(fn ($date) => (float) ($revenueByDay[$date] ?? 0))->values(),
                'segmentLabels' => $segmentDistribution->keys()->map(fn ($segment) => strtoupper((string) $segment))->values(),
                'segmentValues' => $segmentDistribution->values(),
                'retentionLabels' => array_keys($retentionBuckets),
                'retentionValues' => array_values($retentionBuckets),
                'eventLabels' => BehaviorEvent::selectRaw('event_type, count(*) as total')
                    ->groupBy('event_type')
                    ->pluck('event_type')
                    ->values(),
                'eventValues' => BehaviorEvent::selectRaw('event_type, count(*) as total')
                    ->groupBy('event_type')
                    ->pluck('total')
                    ->values(),
                'topCategoryLabels' => Category::withCount('products')
                    ->orderByDesc('products_count')
                    ->limit(6)
                    ->pluck('name')
                    ->values(),
                'topCategoryValues' => Category::withCount('products')
                    ->orderByDesc('products_count')
                    ->limit(6)
                    ->pluck('products_count')
                    ->values(),
            ],
        ]);
    }
}
