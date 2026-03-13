<?php

namespace App\Http\Controllers;

use App\Mail\OrderPlacedAdminMail;
use App\Mail\OrderPlacedCustomerMail;
use App\Models\Order;
use App\Services\BehaviorAnalyticsService;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Throwable;

class CheckoutController extends Controller
{
    public function show(Request $request, CartService $cartService)
    {
        $cart = $cartService->current($request);

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('success', 'Giỏ hàng đang trống.');
        }

        return view('storefront.checkout', [
            'cart' => $cart,
            'totals' => $cartService->totals($cart),
        ]);
    }

    public function store(Request $request, CartService $cartService, BehaviorAnalyticsService $analytics)
    {
        $data = $request->validate(
            [
                'customer_name' => ['required', 'string', 'max:255'],
                'customer_email' => ['required', 'email:rfc,dns', 'max:255'],
                'customer_phone' => ['required', 'string', 'regex:/^(0|\+84)[0-9]{9,10}$/'],
                'shipping_address' => ['required', 'string'],
                'notes' => ['nullable', 'string'],
                'payment_method' => ['required', 'string'],
            ],
            [
                'customer_name.required' => 'Vui lòng nhập họ và tên người nhận.',
                'customer_email.required' => 'Vui lòng nhập email để nhận xác nhận đơn hàng.',
                'customer_email.email' => 'Email chưa đúng định dạng, vui lòng kiểm tra lại.',
                'customer_phone.required' => 'Vui lòng nhập số điện thoại người nhận.',
                'customer_phone.regex' => 'Số điện thoại chưa hợp lệ (ví dụ: 09xxxxxxxx hoặc +84xxxxxxxxx).',
                'shipping_address.required' => 'Vui lòng nhập địa chỉ giao hàng.',
                'payment_method.required' => 'Vui lòng chọn phương thức thanh toán.',
            ]
        );

        $cart = $cartService->current($request);
        $totals = $cartService->totals($cart);

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('success', 'Giỏ hàng đang trống.');
        }

        $order = DB::transaction(function () use ($request, $data, $cart, $totals) {
            $order = Order::create([
                'user_id' => optional($request->user())->id,
                'order_number' => 'OD'.now()->format('YmdHis').random_int(100, 999),
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
                'shipping_address' => $data['shipping_address'],
                'notes' => $data['notes'] ?? null,
                'subtotal' => $totals['subtotal'],
                'discount_total' => 0,
                'shipping_fee' => $totals['shipping'],
                'grand_total' => $totals['grand_total'],
                'payment_method' => $data['payment_method'],
                'payment_status' => 'pending',
                'status' => 'confirmed',
                'ordered_at' => now(),
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'sku' => $item->product->sku,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'line_total' => $item->quantity * $item->unit_price,
                ]);

                $item->product->decrement('stock', min($item->product->stock, $item->quantity));
                $item->product->increment('sold_count', $item->quantity);
            }

            $cart->items()->delete();
            $cart->update(['status' => 'converted']);

            return $order;
        });

        $analytics->record('purchase', [
            'user_id' => optional($request->user())->id,
            'session_id' => $request->session()->get('tracking_session_id'),
            'event_value' => $order->grand_total,
        ]);

        if ($request->user()) {
            $analytics->sendSuggestionEmail($request->user(), 'post_purchase');
        }

        $order->load('items');

        try {
            Mail::to($order->customer_email)->send(new OrderPlacedCustomerMail($order));

            $adminEmail = env('ADMIN_NOTIFICATION_EMAIL', 'admin@gmail.com');
            Mail::to($adminEmail)->send(new OrderPlacedAdminMail($order));
        } catch (Throwable $exception) {
            report($exception);
        }

        return redirect()->route('checkout.thankyou', $order);
    }

    public function thankYou(Order $order)
    {
        return view('storefront.thankyou', ['order' => $order->load('items')]);
    }
}
