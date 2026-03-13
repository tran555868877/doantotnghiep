@extends('layouts.app')

@section('title', 'Đặt hàng thành công')

@section('content')
<section class="thankyou-wrap">
    <div class="section-card p-4 p-lg-5 thankyou-card">
        <div class="thankyou-icon">
            <i class="far fa-check"></i>
        </div>
        <h1 class="thankyou-title">Đặt hàng thành công</h1>
        <p class="thankyou-subtitle">Cảm ơn bạn đã tin tưởng mua sắm tại BabyMart Plus. Chúng tôi đã gửi email xác nhận đơn hàng cho bạn.</p>

        <div class="thankyou-meta">
            <div class="meta-item">
                <span>Mã đơn hàng</span>
                <strong>{{ $order->order_number }}</strong>
            </div>
            <div class="meta-item">
                <span>Tổng thanh toán</span>
                <strong>{{ number_format($order->grand_total, 0, ',', '.') }}đ</strong>
            </div>
            <div class="meta-item">
                <span>Trạng thái</span>
                <strong>Đã xác nhận</strong>
            </div>
        </div>

        <div class="thankyou-actions">
            <a href="{{ route('home') }}" class="theme-btn">Quay lại trang chủ</a>
            <a href="{{ route('shop.index') }}" class="action-pill">Tiếp tục mua sắm</a>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .thankyou-wrap {
        max-width: 880px;
        margin: 0 auto;
    }
    .thankyou-card {
        text-align: center;
        border-radius: 28px;
        background: linear-gradient(180deg, #ffffff 0%, #f4fffc 100%);
        box-shadow: 0 24px 46px rgba(42, 109, 101, 0.16);
    }
    .thankyou-icon {
        width: 86px;
        height: 86px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #00bba7, #33d1bf);
        color: #fff;
        font-size: 34px;
        margin-bottom: 16px;
        box-shadow: 0 14px 30px rgba(0, 187, 167, 0.26);
    }
    .thankyou-title {
        font-size: clamp(30px, 4vw, 40px);
        margin-bottom: 8px;
        color: #153b40;
    }
    .thankyou-subtitle {
        max-width: 640px;
        margin: 0 auto 20px;
        color: #5a7b7f;
        font-size: 17px;
    }
    .thankyou-meta {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 22px;
    }
    .meta-item {
        border-radius: 16px;
        border: 1px solid rgba(42, 109, 101, 0.14);
        background: #fff;
        padding: 14px 12px;
    }
    .meta-item span {
        display: block;
        color: #67888b;
        font-size: 14px;
        margin-bottom: 4px;
    }
    .meta-item strong {
        color: #173f44;
        font-size: 20px;
    }
    .thankyou-actions {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .thankyou-actions .action-pill {
        padding: 14px 22px;
        font-weight: 700;
    }
    @media (max-width: 767px) {
        .thankyou-meta {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush
