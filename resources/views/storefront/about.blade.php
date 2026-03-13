@extends('layouts.app')

@section('title', 'Giới thiệu')

@section('content')
<section class="section-card p-4 p-lg-5 mb-4 about-hero">
    <div class="row g-4 align-items-center">
        <div class="col-lg-7">
            <span class="badge badge-soft rounded-pill px-3 py-2 mb-3">Về BabyMart Plus</span>
            <h1 class="h2 mb-3">Siêu thị mẹ và bé hiện đại, mua sắm thuận tiện và chăm sóc gia đình toàn diện</h1>
            <p class="mb-0 text-muted">Chúng tôi xây dựng trải nghiệm mua sắm tập trung vào chất lượng sản phẩm, tốc độ giao hàng và tư vấn đúng nhu cầu cho từng giai đoạn phát triển của bé.</p>
        </div>
        <div class="col-lg-5">
            <div class="about-hero-media">
                <img src="/babymart-assets/img/hero/hero-thumb3-1.png" alt="Giới thiệu BabyMart Plus">
            </div>
        </div>
    </div>
</section>

<section class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="about-stat-card"><strong>{{ number_format($stats['products']) }}+</strong><span>Sản phẩm chính hãng</span></div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="about-stat-card"><strong>{{ number_format($stats['categories']) }}</strong><span>Danh mục đa dạng</span></div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="about-stat-card"><strong>{{ number_format($stats['customers']) }}+</strong><span>Khách hàng tin dùng</span></div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="about-stat-card"><strong>{{ number_format($stats['orders']) }}+</strong><span>Đơn hàng đã phục vụ</span></div>
    </div>
</section>

<section class="section-card p-4 mb-4">
    <h2 class="h4 mb-3">Danh mục nổi bật</h2>
    <div class="row g-3">
        @foreach($featuredCategories as $category)
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('shop.category', $category) }}" class="about-category-card">
                    <img src="{{ $category->icon ?: $category->image ?: '/babymart-assets/img/category/category_card1_1.png' }}" alt="{{ $category->name }}">
                    <span>
                        <strong>{{ $category->name }}</strong>
                        <small>{{ $category->description }}</small>
                    </span>
                </a>
            </div>
        @endforeach
    </div>
</section>

<section class="section-card p-4 mb-4">
    <h2 class="h4 mb-3">Giá trị dịch vụ</h2>
    <div class="row g-3">
        <div class="col-md-4">
            <div class="about-value-card">
                <h3>Sản phẩm chọn lọc</h3>
                <p>Danh mục sản phẩm được chọn theo nhu cầu thật của mẹ và bé, ưu tiên thương hiệu uy tín và rõ nguồn gốc.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="about-value-card">
                <h3>Giao hàng nhanh</h3>
                <p>Đơn hàng được xử lý trong ngày với quy trình đóng gói chuẩn, đảm bảo sản phẩm đến tay khách hàng an toàn.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="about-value-card">
                <h3>Tư vấn tận tâm</h3>
                <p>Đội ngũ tư vấn hỗ trợ chọn sản phẩm theo độ tuổi, thể trạng và ngân sách để mua đúng, dùng hiệu quả.</p>
            </div>
        </div>
    </div>
</section>

<section class="section-card p-4">
    <h2 class="h4 mb-3">Bài viết mới từ cẩm nang</h2>
    <div class="row g-3">
        @foreach($latestPosts as $post)
            <div class="col-md-6 col-lg-4">
                <a class="about-post-card" href="{{ route('blog.show', $post) }}">
                    <img src="{{ $post->thumbnail ?: '/babymart-assets/img/hero/hero-thumb2-2.png' }}" alt="{{ $post->title }}">
                    <span>{{ $post->title }}</span>
                </a>
            </div>
        @endforeach
    </div>
</section>
@endsection

@push('styles')
<style>
    .about-hero {
        background: linear-gradient(180deg, #fff, #f8fffd);
    }
    .about-hero-media {
        border-radius: 24px;
        overflow: hidden;
        aspect-ratio: 4 / 3;
        background: #eaf8f5;
    }
    .about-hero-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .about-stat-card {
        border-radius: 18px;
        border: 1px solid rgba(42, 109, 101, 0.10);
        background: #fff;
        padding: 18px;
        box-shadow: 0 14px 24px rgba(42, 109, 101, 0.08);
        text-align: center;
    }
    .about-stat-card strong {
        display: block;
        font-size: 30px;
        color: #1a5e57;
        font-family: "Quicksand", sans-serif;
    }
    .about-stat-card span {
        color: #547278;
        font-weight: 600;
    }
    .about-category-card {
        display: grid;
        grid-template-columns: 64px 1fr;
        gap: 12px;
        align-items: center;
        padding: 14px;
        border-radius: 16px;
        border: 1px solid rgba(42, 109, 101, 0.10);
        text-decoration: none;
        color: var(--bm-title);
        background: #fff;
    }
    .about-category-card img {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        object-fit: contain;
        background: #edf9f6;
        padding: 8px;
    }
    .about-category-card span {
        display: grid;
        gap: 4px;
    }
    .about-category-card strong {
        font-family: "Quicksand", sans-serif;
    }
    .about-category-card small {
        color: #5d7b80;
    }
    .about-value-card {
        height: 100%;
        border-radius: 16px;
        padding: 18px;
        background: linear-gradient(180deg, #fff, #f8fffd);
        border: 1px solid rgba(42, 109, 101, 0.10);
    }
    .about-value-card h3 {
        font-size: 20px;
        font-family: "Quicksand", sans-serif;
        margin-bottom: 8px;
    }
    .about-value-card p {
        margin: 0;
        color: #58757a;
    }
    .about-post-card {
        display: block;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid rgba(42, 109, 101, 0.10);
        background: #fff;
        text-decoration: none;
        color: var(--bm-title);
    }
    .about-post-card img {
        width: 100%;
        aspect-ratio: 16 / 10;
        object-fit: cover;
        display: block;
    }
    .about-post-card span {
        display: block;
        padding: 12px;
        font-weight: 700;
        line-height: 1.35;
    }
</style>
@endpush
