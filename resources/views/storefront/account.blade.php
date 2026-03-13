@extends('layouts.app')

@section('title', 'Tài khoản')

@section('content')
@php
    $activeTab = request('tab', 'overview');
@endphp

<section class="account-shell">
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="section-card p-4 account-summary-card">
                <h1 class="h4 mb-1">{{ auth()->user()->name }}</h1>
                <div class="text-muted">{{ auth()->user()->email }}</div>
                <div class="text-muted mb-3">{{ auth()->user()->phone ?: 'Chưa cập nhật số điện thoại' }}</div>

                <div class="score-item">
                    <span>Phân khúc</span>
                    <strong>{{ strtoupper($score->segment) }}</strong>
                </div>
                <div class="score-item">
                    <span>Khả năng quay lại</span>
                    <strong>{{ $score->retention_probability }}%</strong>
                </div>
                <div class="score-item">
                    <span>Mức độ tương tác</span>
                    <strong>{{ number_format($score->engagement_score, 2, ',', '.') }}</strong>
                </div>
                <div class="score-item">
                    <span>Danh mục yêu thích</span>
                    <strong>{{ $score->favoriteCategory->name ?? 'Chưa xác định' }}</strong>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="section-card p-4 p-lg-5 account-main-card">
                <ul class="nav nav-pills account-tabs mb-4">
                    <li class="nav-item"><a class="nav-link {{ $activeTab === 'overview' ? 'active' : '' }}" href="{{ route('account', ['tab' => 'overview']) }}">Tổng quan</a></li>
                    <li class="nav-item"><a class="nav-link {{ $activeTab === 'orders' ? 'active' : '' }}" href="{{ route('account', ['tab' => 'orders']) }}">Đơn hàng</a></li>
                    <li class="nav-item"><a class="nav-link {{ $activeTab === 'profile' ? 'active' : '' }}" href="{{ route('account', ['tab' => 'profile']) }}">Thông tin cá nhân</a></li>
                    <li class="nav-item"><a class="nav-link {{ $activeTab === 'password' ? 'active' : '' }}" href="{{ route('account', ['tab' => 'password']) }}">Đổi mật khẩu</a></li>
                </ul>

                @if($activeTab === 'orders')
                    <h2 class="h5 mb-3">Danh sách đơn hàng</h2>
                    @forelse($orders as $order)
                        <div class="order-card mb-3">
                            <div class="order-head">
                                <div>
                                    <strong>{{ $order->order_number }}</strong>
                                    <div class="text-muted small">{{ optional($order->ordered_at)->format('d/m/Y H:i') }}</div>
                                </div>
                                <div class="text-end">
                                    <div class="badge text-bg-light">{{ $order->status }}</div>
                                    <div class="fw-bold mt-1">{{ number_format($order->grand_total, 0, ',', '.') }}đ</div>
                                </div>
                            </div>
                            <div class="table-responsive mt-3">
                                <table class="table table-sm align-middle mb-0">
                                    <thead>
                                        <tr><th>Sản phẩm</th><th>SL</th><th>Đơn giá</th><th>Thành tiền</th></tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $item)
                                            <tr>
                                                <td>{{ $item->product_name }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>{{ number_format($item->unit_price, 0, ',', '.') }}đ</td>
                                                <td>{{ number_format($item->line_total, 0, ',', '.') }}đ</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">Bạn chưa có đơn hàng nào.</div>
                    @endforelse

                    {{ $orders->appends(['tab' => 'orders'])->links() }}
                @elseif($activeTab === 'profile')
                    <h2 class="h5 mb-3">Cập nhật thông tin cá nhân</h2>
                    <form method="post" action="{{ route('account.profile.update') }}" class="row g-3">
                        @csrf
                        <div class="col-md-6">
                            <label class="form-label">Họ và tên</label>
                            <input class="form-control account-input" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Số điện thoại</label>
                            <input class="form-control account-input" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Giới tính</label>
                            <select class="form-select account-input" name="gender">
                                <option value="">Chưa chọn</option>
                                <option value="male" @selected(old('gender', auth()->user()->gender) === 'male')>Nam</option>
                                <option value="female" @selected(old('gender', auth()->user()->gender) === 'female')>Nữ</option>
                                <option value="other" @selected(old('gender', auth()->user()->gender) === 'other')>Khác</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ngày sinh</label>
                            <input type="date" class="form-control account-input" name="date_of_birth" value="{{ old('date_of_birth', optional(auth()->user()->date_of_birth)->format('Y-m-d')) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Địa chỉ</label>
                            <textarea class="form-control account-input" name="address" rows="3">{{ old('address', auth()->user()->address) }}</textarea>
                        </div>
                        <div class="col-12">
                            <button class="theme-btn">Lưu thông tin</button>
                        </div>
                    </form>
                @elseif($activeTab === 'password')
                    <h2 class="h5 mb-3">Đổi mật khẩu</h2>
                    <form method="post" action="{{ route('account.password.update') }}" class="row g-3">
                        @csrf
                        <div class="col-12">
                            <label class="form-label">Mật khẩu hiện tại</label>
                            <input type="password" class="form-control account-input" name="current_password" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mật khẩu mới</label>
                            <input type="password" class="form-control account-input" name="password" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Xác nhận mật khẩu mới</label>
                            <input type="password" class="form-control account-input" name="password_confirmation" required>
                        </div>
                        <div class="col-12">
                            <button class="theme-btn">Cập nhật mật khẩu</button>
                        </div>
                    </form>
                @else
                    <h2 class="h5 mb-3">Gợi ý dành cho bạn</h2>
                    <div class="row g-3">
                        @foreach($recommendedProducts as $product)
                            <div class="col-md-6">
                                <div class="recommend-card h-100">
                                    <a class="recommend-name" href="{{ route('shop.product', $product) }}">{{ $product->name }}</a>
                                    <div class="recommend-meta">{{ $product->brand ?: 'Hàng chính hãng' }}</div>
                                    <div class="recommend-price">{{ number_format($product->final_price, 0, ',', '.') }}đ</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .account-shell .account-summary-card,
    .account-shell .account-main-card {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 16px 40px rgba(30, 113, 104, 0.12);
        border: 1px solid rgba(42, 109, 101, 0.08);
    }
    .account-tabs .nav-link {
        border-radius: 999px;
        color: #38656a;
        font-weight: 700;
        border: 1px solid rgba(42, 109, 101, 0.12);
        margin-right: 8px;
        margin-bottom: 8px;
    }
    .account-tabs .nav-link.active {
        background: linear-gradient(135deg, #00bba7, #33d1bf);
        border-color: transparent;
    }
    .score-item {
        border: 1px solid rgba(42, 109, 101, 0.1);
        border-radius: 14px;
        padding: 10px 12px;
        margin-bottom: 10px;
        background: #f8fffd;
    }
    .score-item span {
        display: block;
        color: #597b80;
        font-size: 13px;
    }
    .score-item strong {
        color: #1b4f55;
    }
    .account-input {
        border-radius: 14px;
        border: 1px solid rgba(42, 109, 101, 0.20);
        padding: 11px 14px;
    }
    .account-input:focus {
        border-color: #20b7a9;
        box-shadow: 0 0 0 0.24rem rgba(32, 183, 169, 0.14);
    }
    .order-card {
        border: 1px solid rgba(42, 109, 101, 0.14);
        border-radius: 16px;
        padding: 14px;
        background: #fcfffe;
    }
    .order-head {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: center;
    }
    .recommend-card {
        border: 1px solid rgba(42, 109, 101, 0.14);
        border-radius: 16px;
        padding: 14px;
        background: #fcfffe;
    }
    .recommend-name {
        font-weight: 700;
        color: var(--bm-title);
        text-decoration: none;
        display: block;
        margin-bottom: 4px;
    }
    .recommend-meta {
        color: #608086;
        font-size: 14px;
    }
    .recommend-price {
        margin-top: 8px;
        color: #10685f;
        font-size: 20px;
        font-weight: 700;
    }
</style>
@endpush
