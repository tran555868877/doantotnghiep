@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
<section class="section-card p-4 p-lg-5 cart-shell">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <h1 class="h3 mb-0">Giỏ hàng của bạn</h1>
        <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Tiếp tục mua sắm</a>
    </div>

    @if($cart->items->isEmpty())
        <div class="alert alert-warning rounded-4">Giỏ hàng đang trống.</div>
    @else
        <div class="table-responsive cart-table-wrap mb-4">
            <table class="table align-middle cart-table">
                <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Đơn giá</th>
                    <th>Số lượng</th>
                    <th>Tổng</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($cart->items as $item)
                    <tr>
                        <td>
                            <a href="{{ route('shop.product', $item->product) }}" class="cart-product-link">
                                <img src="{{ $item->product->thumbnail }}" alt="{{ $item->product->name }}">
                                <span>
                                    <strong>{{ $item->product->name }}</strong>
                                    <small>{{ $item->product->brand }} · {{ $item->product->age_group }}</small>
                                </span>
                            </a>
                        </td>
                        <td class="fw-semibold">{{ number_format($item->unit_price, 0, ',', '.') }}đ</td>
                        <td>
                            <form action="{{ route('cart.update', $item->id) }}" method="post" class="qty-form">
                                @csrf
                                <input class="form-control rounded-pill" type="number" min="1" name="quantity" value="{{ $item->quantity }}">
                                <button class="btn btn-outline-secondary rounded-pill px-3">Cập nhật</button>
                            </form>
                        </td>
                        <td class="fw-bold text-success">{{ number_format($item->quantity * $item->unit_price, 0, ',', '.') }}đ</td>
                        <td class="text-end">
                            <form action="{{ route('cart.destroy', $item->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger rounded-pill px-3">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="row justify-content-end">
            <div class="col-lg-5 col-xl-4">
                <div class="cart-summary-card">
                    <h2 class="h5 mb-3">Tổng thanh toán</h2>
                    <div class="summary-row"><span>Tạm tính</span><strong>{{ number_format($totals['subtotal'], 0, ',', '.') }}đ</strong></div>
                    <div class="summary-row"><span>Vận chuyển</span><strong>{{ number_format($totals['shipping'], 0, ',', '.') }}đ</strong></div>
                    <div class="summary-row total"><span>Thành tiền</span><strong>{{ number_format($totals['grand_total'], 0, ',', '.') }}đ</strong></div>
                    <a href="{{ route('checkout.show') }}" class="theme-btn w-100 justify-content-center mt-3">Tiến hành thanh toán</a>
                </div>
            </div>
        </div>
    @endif
</section>
@endsection

@push('styles')
<style>
    .cart-shell {
        background: linear-gradient(180deg, #fff, #f8fffd);
    }
    .cart-table-wrap {
        border: 1px solid rgba(42, 109, 101, 0.10);
        border-radius: 16px;
        overflow: hidden;
        background: #fff;
    }
    .cart-table thead th {
        background: #f2fbf9;
        color: #2b5953;
        font-weight: 700;
        border-bottom: 1px solid rgba(42, 109, 101, 0.10);
    }
    .cart-table td, .cart-table th {
        padding: 14px 12px;
        border-bottom-color: rgba(42, 109, 101, 0.10);
    }
    .cart-product-link {
        display: grid;
        grid-template-columns: 64px 1fr;
        gap: 12px;
        align-items: center;
        text-decoration: none;
        color: var(--bm-title);
    }
    .cart-product-link img {
        width: 64px;
        height: 64px;
        border-radius: 12px;
        object-fit: cover;
        background: #eaf8f5;
    }
    .cart-product-link span {
        display: grid;
        gap: 2px;
    }
    .cart-product-link small {
        color: #608186;
    }
    .qty-form {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    .qty-form input {
        width: 92px;
    }
    .cart-summary-card {
        border: 1px solid rgba(42, 109, 101, 0.12);
        border-radius: 16px;
        background: #fff;
        padding: 18px;
        box-shadow: 0 12px 24px rgba(42, 109, 101, 0.08);
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        padding: 8px 0;
        border-bottom: 1px dashed rgba(42, 109, 101, 0.16);
        color: #47686d;
    }
    .summary-row.total {
        border-bottom: 0;
        font-size: 20px;
        font-weight: 700;
        color: #154f48;
        padding-top: 12px;
    }
</style>
@endpush
