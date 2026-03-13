<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartService
{
    public function current(Request $request): Cart
    {
        $sessionId = $request->session()->get('tracking_session_id');

        $cart = Cart::query()
            ->when($request->user(), fn ($query) => $query->where('user_id', $request->user()->id))
            ->when(! $request->user(), fn ($query) => $query->where('session_id', $sessionId))
            ->where('status', 'active')
            ->latest()
            ->first();

        if (! $cart) {
            $cart = Cart::create([
                'user_id' => optional($request->user())->id,
                'session_id' => $sessionId,
                'status' => 'active',
            ]);
        }

        if ($request->user() && ! $cart->user_id) {
            $cart->update(['user_id' => $request->user()->id]);
        }

        return $cart->load('items.product');
    }

    public function add(Request $request, Product $product, int $quantity = 1): Cart
    {
        $cart = $this->current($request);

        $item = $cart->items()->firstOrNew([
            'product_id' => $product->id,
        ]);

        $item->quantity = ($item->exists ? $item->quantity : 0) + max(1, $quantity);
        $item->unit_price = $product->final_price;
        $item->save();

        return $this->current($request);
    }

    public function totals(Cart $cart): array
    {
        $subtotal = $cart->items->sum(fn ($item) => $item->quantity * $item->unit_price);
        $shipping = $subtotal >= 800000 ? 0 : 30000;

        return [
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'grand_total' => $subtotal + $shipping,
        ];
    }
}
