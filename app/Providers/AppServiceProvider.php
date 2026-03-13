<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Cart;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        View::composer('layouts.app', function ($view) {
            $request = request();
            $sessionId = $request->session()->get('tracking_session_id');

            $activeCart = Cart::query()
                ->when($request->user(), fn ($query) => $query->where('user_id', $request->user()->id))
                ->when(! $request->user() && $sessionId, fn ($query) => $query->where('session_id', $sessionId))
                ->where('status', 'active')
                ->latest()
                ->first();

            $cartItemsCount = $activeCart ? (int) $activeCart->items()->sum('quantity') : 0;

            $view->with('menuCategories', Category::query()
                ->whereNull('parent_id')
                ->where('is_active', true)
                ->with(['children' => fn ($query) => $query->where('is_active', true)->orderBy('sort_order')])
                ->orderBy('sort_order')
                ->get());
            $view->with('cartItemsCount', $cartItemsCount);
        });
    }
}
