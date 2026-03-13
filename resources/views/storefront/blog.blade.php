@extends('layouts.app')

@section('title', 'Cẩm nang mẹ và bé')

@section('content')
<section class="section-card p-4 p-lg-5 mb-4 blog-hero">
    <div class="row g-4 align-items-center">
        <div class="col-lg-7">
            <span class="badge badge-soft rounded-pill px-3 py-2 mb-3">Chuyên mục tư vấn</span>
            <h1 class="h2 mb-3">Cẩm nang nuôi con, chăm mẹ và chọn sản phẩm đúng nhu cầu</h1>
            <p class="text-muted mb-0">Các bài viết được biên tập từ kinh nghiệm thực tế, giúp ba mẹ mua sắm thông minh hơn và chăm sóc bé khoa học hơn mỗi ngày.</p>
        </div>
        <div class="col-lg-5">
            <div class="blog-hero-media">
                <img src="{{ $featuredPost?->thumbnail ?: '/babymart-assets/img/hero/hero-thumb2-2.png' }}" alt="Cẩm nang">
            </div>
        </div>
    </div>
</section>

@if($featuredPost)
    <section class="section-card p-4 mb-4 featured-post-wrap">
        <div class="row g-4 align-items-center">
            <div class="col-lg-5">
                <a href="{{ route('blog.show', $featuredPost) }}" class="featured-post-media">
                    <img src="{{ $featuredPost->thumbnail ?: '/babymart-assets/img/hero/hero-thumb2-2.png' }}" alt="{{ $featuredPost->title }}">
                </a>
            </div>
            <div class="col-lg-7">
                <div class="small text-muted mb-2">Bài viết nổi bật · {{ optional($featuredPost->published_at)->format('d/m/Y') }}</div>
                <a href="{{ route('blog.show', $featuredPost) }}" class="featured-post-title">{{ $featuredPost->title }}</a>
                <p class="text-muted mt-3 mb-4">{{ $featuredPost->excerpt }}</p>
                <a class="theme-btn" href="{{ route('blog.show', $featuredPost) }}">Đọc bài viết</a>
            </div>
        </div>
    </section>
@endif

<section class="mb-4">
    <h2 class="h4 mb-3">Bài viết mới nhất</h2>
    <div class="row g-3">
        @foreach($posts as $post)
            <div class="col-md-6 col-lg-4">
                <article class="blog-card h-100">
                    <a href="{{ route('blog.show', $post) }}" class="blog-card-media">
                        <img src="{{ $post->thumbnail ?: '/babymart-assets/img/hero/hero-thumb2-3.png' }}" alt="{{ $post->title }}">
                    </a>
                    <div class="blog-card-body">
                        <div class="small text-muted mb-1">{{ optional($post->published_at)->format('d/m/Y') }}</div>
                        <a href="{{ route('blog.show', $post) }}" class="blog-card-title">{{ $post->title }}</a>
                        <p class="text-muted mb-0">{{ $post->excerpt }}</p>
                    </div>
                </article>
            </div>
        @endforeach
    </div>
</section>

<section class="section-card p-4 mb-4">
    <h2 class="h5 mb-3">Sản phẩm được quan tâm nhiều</h2>
    <div class="row g-3">
        @foreach($recommendedProducts as $item)
            <div class="col-md-6 col-lg-3">
                <div class="mini-product h-100">
                    <a href="{{ route('shop.product', $item) }}" class="mini-product-media">
                        <img src="{{ $item->thumbnail }}" alt="{{ $item->name }}">
                    </a>
                    <a href="{{ route('shop.product', $item) }}" class="mini-product-title">{{ $item->name }}</a>
                    <div class="price">{{ number_format($item->final_price, 0, ',', '.') }}đ</div>
                </div>
            </div>
        @endforeach
    </div>
</section>

<div class="mt-4">{{ $posts->links() }}</div>
@endsection

@push('styles')
<style>
    .blog-hero {
        background: linear-gradient(180deg, #fff, #f7fffd);
    }
    .blog-hero-media,
    .featured-post-media,
    .blog-card-media {
        display: block;
        border-radius: 20px;
        overflow: hidden;
        background: #eaf8f5;
    }
    .blog-hero-media {
        aspect-ratio: 16 / 10;
    }
    .featured-post-media,
    .blog-card-media {
        aspect-ratio: 4 / 3;
    }
    .blog-hero-media img,
    .featured-post-media img,
    .blog-card-media img,
    .mini-product-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .featured-post-wrap {
        background: linear-gradient(180deg, #ffffff, #f8fffd);
    }
    .featured-post-title {
        font-family: "Quicksand", sans-serif;
        font-size: 30px;
        font-weight: 700;
        line-height: 1.3;
        color: var(--bm-title);
        text-decoration: none;
    }
    .blog-card {
        overflow: hidden;
        border-radius: 20px;
        border: 1px solid rgba(42, 109, 101, 0.10);
        box-shadow: 0 14px 26px rgba(42, 109, 101, 0.08);
        background: #fff;
    }
    .blog-card-body {
        padding: 14px;
    }
    .blog-card-title {
        display: block;
        color: var(--bm-title);
        font-family: "Quicksand", sans-serif;
        font-weight: 700;
        line-height: 1.35;
        text-decoration: none;
        margin-bottom: 10px;
        min-height: 52px;
    }
    .mini-product {
        border: 1px solid rgba(42, 109, 101, 0.10);
        border-radius: 16px;
        padding: 10px;
        background: #fff;
    }
    .mini-product-media {
        display: block;
        border-radius: 12px;
        overflow: hidden;
        aspect-ratio: 1 / 1;
        margin-bottom: 10px;
        background: #eaf8f5;
    }
    .mini-product-title {
        color: var(--bm-title);
        font-weight: 700;
        line-height: 1.3;
        text-decoration: none;
        min-height: 42px;
        display: block;
        margin-bottom: 8px;
    }
</style>
@endpush
