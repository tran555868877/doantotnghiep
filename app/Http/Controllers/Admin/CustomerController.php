<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use App\Services\BehaviorAnalyticsService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request, BehaviorAnalyticsService $analytics)
    {
        $analytics->calculateAll();

        $query = User::query()
            ->where('role', 'customer')
            ->with(['customerScore.favoriteCategory'])
            ->withCount(['orders', 'behaviorEvents']);

        if ($keyword = trim((string) $request->string('q'))) {
            $query->where(function ($subQuery) use ($keyword) {
                $subQuery->where('name', 'like', '%'.$keyword.'%')
                    ->orWhere('email', 'like', '%'.$keyword.'%')
                    ->orWhere('phone', 'like', '%'.$keyword.'%');
            });
        }

        if ($segment = $request->string('segment')->toString()) {
            $query->whereHas('customerScore', fn ($subQuery) => $subQuery->where('segment', $segment));
        }

        if ($favoriteCategoryId = $request->integer('favorite_category_id')) {
            $query->whereHas('customerScore', fn ($subQuery) => $subQuery->where('favorite_category_id', $favoriteCategoryId));
        }

        if ($interestLevel = $request->string('interest_level')->toString()) {
            $query->whereHas('customerScore', function ($subQuery) use ($interestLevel) {
                if ($interestLevel === 'high') {
                    $subQuery->where('retention_probability', '>=', 70);
                } elseif ($interestLevel === 'medium') {
                    $subQuery->whereBetween('retention_probability', [40, 69.99]);
                } elseif ($interestLevel === 'low') {
                    $subQuery->where('retention_probability', '<', 40);
                }
            });
        }

        $sortBy = $request->string('sort_by')->toString();
        if ($sortBy === 'retention_desc') {
            $query->leftJoin('customer_scores', 'users.id', '=', 'customer_scores.user_id')
                ->orderByDesc('customer_scores.retention_probability')
                ->select('users.*');
        } elseif ($sortBy === 'engagement_desc') {
            $query->leftJoin('customer_scores', 'users.id', '=', 'customer_scores.user_id')
                ->orderByDesc('customer_scores.engagement_score')
                ->select('users.*');
        } elseif ($sortBy === 'purchase_desc') {
            $query->leftJoin('customer_scores', 'users.id', '=', 'customer_scores.user_id')
                ->orderByDesc('customer_scores.purchase_score')
                ->select('users.*');
        } else {
            $query->latest();
        }

        return view('admin.customers.index', [
            'customers' => $query->paginate(20)->withQueryString(),
            'favoriteCategories' => Category::query()->whereNull('parent_id')->orderBy('name')->get(),
            'segments' => ['vip', 'warm', 'potential', 'cold'],
        ]);
    }

    public function show(User $customer)
    {
        abort_unless($customer->role === 'customer', 404);

        return view('admin.customers.show', [
            'customer' => $customer->load(['orders.items', 'behaviorEvents', 'customerScore.favoriteCategory']),
        ]);
    }
}
