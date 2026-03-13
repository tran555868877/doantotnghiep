<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\BehaviorAnalyticsService;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request, CartService $cartService)
    {
        $cart = $cartService->current($request);

        return view('storefront.cart', [
            'cart' => $cart,
            'totals' => $cartService->totals($cart),
        ]);
    }

    public function store(Request $request, Product $product, CartService $cartService, BehaviorAnalyticsService $analytics)
    {
        $quantity = (int) $request->integer('quantity', 1);
        $cartService->add($request, $product, $quantity);

        $analytics->record('add_to_cart', [
            'user_id' => optional($request->user())->id,
            'product_id' => $product->id,
            'category_id' => $product->category_id,
            'session_id' => $request->session()->get('tracking_session_id'),
            'event_value' => $product->final_price * $quantity,
        ]);

        if ($request->user()) {
            $analytics->calculateForUser($request->user());
        }

        return redirect()->route('cart.index')->with('success', 'Đã thêm sản phẩm vào giỏ hàng.');
    }

    public function update(Request $request, int $itemId, CartService $cartService)
    {
        $cart = $cartService->current($request);
        $item = $cart->items()->findOrFail($itemId);
        $item->update([
            'quantity' => max(1, (int) $request->integer('quantity', 1)),
        ]);

        return back()->with('success', 'Đã cập nhật giỏ hàng.');
    }

    public function destroy(Request $request, int $itemId, CartService $cartService)
    {
        $cart = $cartService->current($request);
        $cart->items()->whereKey($itemId)->delete();

        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }
}
