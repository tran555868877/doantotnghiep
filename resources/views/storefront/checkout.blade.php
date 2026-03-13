@extends('layouts.app')

@section('title', 'Thanh toán')

@section('content')
<section class="checkout-shell">
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="section-card p-4 p-lg-5 checkout-form-card">
                <h1 class="h3 mb-3">Thông tin giao hàng</h1>
                <p class="text-muted mb-4">Điền chính xác thông tin để đơn hàng được xác nhận và giao nhanh hơn.</p>

                <form action="{{ route('checkout.store') }}" method="post" class="row g-3 checkout-form">
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label">Họ và tên</label>
                        <input
                            class="form-control rounded-pill"
                            name="customer_name"
                            placeholder="Nhập họ và tên"
                            value="{{ old('customer_name', auth()->user()->name ?? '') }}"
                            required
                        >
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Số điện thoại</label>
                        <input
                            class="form-control rounded-pill"
                            name="customer_phone"
                            type="tel"
                            placeholder="Ví dụ: 0912345678"
                            value="{{ old('customer_phone', auth()->user()->phone ?? '') }}"
                            required
                        >
                    </div>
                    <div class="col-12">
                        <label class="form-label">Email</label>
                        <input
                            class="form-control rounded-pill"
                            name="customer_email"
                            type="email"
                            placeholder="Nhập email để nhận xác nhận đơn hàng"
                            value="{{ old('customer_email', auth()->user()->email ?? '') }}"
                            required
                        >
                    </div>
                    <div class="col-12">
                        <label class="form-label">Địa chỉ giao hàng</label>
                        <textarea class="form-control rounded-4" name="shipping_address" rows="3" placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành" required>{{ old('shipping_address', auth()->user()->address ?? '') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Phương thức thanh toán</label>
                        <select class="form-select rounded-pill" name="payment_method" required>
                            <option value="cod" @selected(old('payment_method') === 'cod')>Thanh toán khi nhận hàng (COD)</option>
                            <option value="bank_transfer" @selected(old('payment_method') === 'bank_transfer')>Chuyển khoản ngân hàng</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Ghi chú</label>
                        <textarea class="form-control rounded-4" name="notes" rows="3" placeholder="Thời gian nhận hàng, lưu ý giao hàng...">{{ old('notes') }}</textarea>
                    </div>
                    <div class="col-12 pt-2">
                        <button class="theme-btn w-100 justify-content-center py-3 checkout-submit">Xác nhận đặt hàng</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="section-card p-4 checkout-summary-card">
                <h2 class="h4 mb-3">Đơn hàng của bạn</h2>

                <div class="checkout-item-list">
                    @foreach($cart->items as $item)
                        <div class="checkout-item">
                            <img src="{{ $item->product->thumbnail }}" alt="{{ $item->product->name }}">
                            <div class="checkout-item-info">
                                <strong>{{ $item->product->name }}</strong>
                                <small>{{ $item->quantity }} x {{ number_format($item->unit_price, 0, ',', '.') }}đ</small>
                            </div>
                            <div class="checkout-item-total">{{ number_format($item->quantity * $item->unit_price, 0, ',', '.') }}đ</div>
                        </div>
                    @endforeach
                </div>

                <div class="summary-row"><span>Tạm tính</span><strong>{{ number_format($totals['subtotal'], 0, ',', '.') }}đ</strong></div>
                <div class="summary-row"><span>Vận chuyển</span><strong>{{ number_format($totals['shipping'], 0, ',', '.') }}đ</strong></div>
                <div class="summary-row total"><span>Tổng cộng</span><strong>{{ number_format($totals['grand_total'], 0, ',', '.') }}đ</strong></div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .checkout-shell .checkout-form-card,
    .checkout-shell .checkout-summary-card {
        background: linear-gradient(180deg, #fff, #f8fffd);
        border-radius: 24px;
        box-shadow: 0 20px 44px rgba(42, 109, 101, 0.12);
    }
    .checkout-form .form-label {
        font-weight: 700;
        color: #2b5550;
        margin-bottom: 6px;
    }
    .checkout-form .form-control,
    .checkout-form .form-select {
        border: 1px solid rgba(42, 109, 101, 0.20);
        padding: 13px 16px;
        background: #fbfffe;
    }
    .checkout-form .form-control:focus,
    .checkout-form .form-select:focus {
        border-color: #23b7a8;
        box-shadow: 0 0 0 0.25rem rgba(35, 183, 168, 0.14);
        background: #fff;
    }
    .checkout-submit {
        font-size: 17px;
        box-shadow: 0 14px 28px rgba(0, 187, 167, 0.26);
    }
    .checkout-item-list {
        display: grid;
        gap: 12px;
        margin-bottom: 12px;
    }
    .checkout-item {
        display: grid;
        grid-template-columns: 62px 1fr auto;
        gap: 10px;
        align-items: center;
        padding: 10px;
        border-radius: 14px;
        border: 1px solid rgba(42, 109, 101, 0.10);
        background: #fbfffd;
    }
    .checkout-item img {
        width: 62px;
        height: 62px;
        border-radius: 12px;
        object-fit: cover;
        background: #eaf8f5;
    }
    .checkout-item-info {
        display: grid;
        gap: 2px;
    }
    .checkout-item-info strong {
        color: var(--bm-title);
        line-height: 1.25;
    }
    .checkout-item-info small {
        color: #5e8085;
    }
    .checkout-item-total {
        font-weight: 700;
        color: #1a6d64;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        padding: 10px 0;
        border-bottom: 1px dashed rgba(42, 109, 101, 0.16);
    }
    .summary-row.total {
        border-bottom: 0;
        padding-top: 12px;
        font-size: 22px;
        color: #154f48;
        font-weight: 700;
    }
</style>
@endpush
