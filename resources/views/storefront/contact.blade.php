@extends('layouts.app')

@section('title', 'Liên hệ')

@section('content')
<section class="section-card p-4 p-lg-5 mb-4 contact-hero">
    <div class="row g-4 align-items-center">
        <div class="col-lg-7">
            <span class="badge badge-soft rounded-pill px-3 py-2 mb-3">Liên hệ BabyMart Plus</span>
            <h1 class="h2 mb-3">Kết nối nhanh với đội ngũ tư vấn mua sắm mẹ và bé</h1>
            <p class="text-muted mb-0">Bạn cần tư vấn chọn sản phẩm, theo dõi đơn hàng hoặc hỗ trợ đổi trả? Chúng tôi luôn sẵn sàng hỗ trợ mỗi ngày.</p>
        </div>
        <div class="col-lg-5">
            <div class="contact-hero-media">
                <img src="/babymart-assets/img/hero/hero-thumb2-2.png" alt="Liên hệ">
            </div>
        </div>
    </div>
</section>

<div class="row g-4">
    <div class="col-lg-7">
        <section class="section-card p-4 mb-4">
            <h2 class="h4 mb-3">Gửi yêu cầu hỗ trợ</h2>
            <form class="row g-3" method="post" action="{{ route('contact.store') }}">
                @csrf
                <div class="col-md-6">
                    <label class="form-label">Họ và tên</label>
                    <input class="form-control" name="name" placeholder="Nhập họ và tên" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Số điện thoại</label>
                    <input class="form-control" name="phone" placeholder="Nhập số điện thoại" value="{{ old('phone') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input class="form-control" type="email" name="email" placeholder="Nhập email" value="{{ old('email') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Chủ đề</label>
                    <select class="form-select" name="topic" required>
                        <option value="Tư vấn sản phẩm" @selected(old('topic') === 'Tư vấn sản phẩm')>Tư vấn sản phẩm</option>
                        <option value="Hỗ trợ đơn hàng" @selected(old('topic') === 'Hỗ trợ đơn hàng')>Hỗ trợ đơn hàng</option>
                        <option value="Đổi trả - bảo hành" @selected(old('topic') === 'Đổi trả - bảo hành')>Đổi trả - bảo hành</option>
                        <option value="Hợp tác đối tác" @selected(old('topic') === 'Hợp tác đối tác')>Hợp tác đối tác</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Nội dung</label>
                    <textarea class="form-control" name="message" rows="5" placeholder="Nhập nội dung cần hỗ trợ" required>{{ old('message') }}</textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="theme-btn">Gửi thông tin</button>
                </div>
            </form>
        </section>

        <section class="section-card p-4">
            <h2 class="h4 mb-3">Kênh hỗ trợ nhanh</h2>
            <div class="contact-channels">
                <div><i class="far fa-phone me-2"></i>Hotline mua hàng: 0909 123 456</div>
                <div><i class="far fa-envelope me-2"></i>Email chăm sóc khách hàng: cskh@babymartplus.vn</div>
                <div><i class="far fa-comment-dots me-2"></i>Fanpage: facebook.com/babymartplus</div>
                <div><i class="far fa-clock me-2"></i>Thời gian hỗ trợ: 08:00 - 22:00 mỗi ngày</div>
            </div>
        </section>
    </div>

    <div class="col-lg-5">
        <section class="section-card p-4 mb-4">
            <h2 class="h5 mb-3">Hệ thống cửa hàng</h2>
            <div class="branch-list">
                @foreach($branches as $branch)
                    <article class="branch-card">
                        <h3>{{ $branch['name'] }}</h3>
                        <p class="mb-1">{{ $branch['address'] }}</p>
                        <p class="mb-1">Giờ mở cửa: {{ $branch['hours'] }}</p>
                        <p class="mb-0">Hotline: {{ $branch['phone'] }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="section-card p-4 mb-4">
            <h2 class="h5 mb-3">Danh mục được quan tâm</h2>
            @foreach($topCategories as $category)
                <a href="{{ route('shop.category', $category) }}" class="contact-category-link">
                    <img src="{{ $category->icon ?: $category->image ?: '/babymart-assets/img/category/category_card1_1.png' }}" alt="{{ $category->name }}">
                    <span>{{ $category->name }}</span>
                </a>
            @endforeach
        </section>

        <section class="section-card p-3 contact-map-wrap">
            <iframe
                title="Bản đồ cửa hàng"
                src="https://maps.google.com/maps?q=10.7769,106.7009&z=13&output=embed"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
            ></iframe>
        </section>
    </div>
</div>
@endsection

@push('styles')
<style>
    .contact-hero {
        background: linear-gradient(180deg, #fff, #f8fffd);
    }
    .contact-hero-media {
        border-radius: 20px;
        overflow: hidden;
        aspect-ratio: 16 / 11;
        background: #eaf8f5;
    }
    .contact-hero-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .contact-channels {
        display: grid;
        gap: 10px;
        color: #54757a;
    }
    .branch-list {
        display: grid;
        gap: 10px;
    }
    .branch-card {
        border: 1px solid rgba(42, 109, 101, 0.10);
        border-radius: 14px;
        padding: 12px;
        background: #fff;
    }
    .branch-card h3 {
        font-size: 18px;
        margin-bottom: 6px;
        font-family: "Quicksand", sans-serif;
    }
    .contact-category-link {
        display: grid;
        grid-template-columns: 48px 1fr;
        gap: 10px;
        align-items: center;
        text-decoration: none;
        color: var(--bm-title);
        padding: 8px 0;
        border-bottom: 1px solid rgba(42, 109, 101, 0.10);
    }
    .contact-category-link:last-child {
        border-bottom: 0;
    }
    .contact-category-link img {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        object-fit: contain;
        background: #eaf8f5;
        padding: 6px;
    }
    .contact-map-wrap iframe {
        width: 100%;
        border: 0;
        border-radius: 14px;
        min-height: 260px;
    }
</style>
@endpush
