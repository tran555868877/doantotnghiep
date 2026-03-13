@extends('layouts.app')

@section('title', $post->title)

@section('content')
<section class="section-card p-4 p-lg-5 mb-4 article-head">
    <div class="small text-muted mb-2">Cẩm nang mẹ và bé · {{ optional($post->published_at)->format('d/m/Y') }}</div>
    <h1 class="article-title mb-3">{{ $post->title }}</h1>
    <p class="text-muted mb-0">{{ $post->excerpt }}</p>
</section>

<div class="row g-4">
    <div class="col-lg-8">
        <article class="section-card p-4 article-content">
            <div class="article-cover mb-4">
                <img src="{{ $post->thumbnail ?: '/babymart-assets/img/hero/hero-thumb2-2.png' }}" alt="{{ $post->title }}">
            </div>
            <div class="article-body">{!! nl2br(e($post->content)) !!}</div>
        </article>
    </div>
    <div class="col-lg-4">
        <div class="section-card p-4 mb-4">
            <h2 class="h5 mb-3">Bài viết mới</h2>
            @foreach($latestPosts as $item)
                <a class="latest-post-link" href="{{ route('blog.show', $item) }}">
                    <img src="{{ $item->thumbnail ?: '/babymart-assets/img/hero/hero-thumb2-3.png' }}" alt="{{ $item->title }}">
                    <span>{{ $item->title }}</span>
                </a>
            @endforeach
        </div>

        <div class="section-card p-4">
            <h2 class="h5 mb-3">Gợi ý sản phẩm</h2>
            @foreach($recommendedProducts as $item)
                <a class="recommend-link" href="{{ route('shop.product', $item) }}">
                    <img src="{{ $item->thumbnail }}" alt="{{ $item->name }}">
                    <span>
                        <strong>{{ $item->name }}</strong>
                        <small>{{ number_format($item->final_price, 0, ',', '.') }}đ</small>
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .article-head {
        background: linear-gradient(180deg, #fff, #f8fffd);
    }
    .article-title {
        font-family: "Quicksand", sans-serif;
        font-size: clamp(28px, 4vw, 40px);
        line-height: 1.3;
        font-weight: 700;
    }
    .article-cover {
        border-radius: 18px;
        overflow: hidden;
        aspect-ratio: 16 / 9;
        background: #eaf8f5;
    }
    .article-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .article-content {
        line-height: 1.85;
    }
    .article-body {
        color: #355960;
        font-size: 17px;
    }
    .latest-post-link,
    .recommend-link {
        display: grid;
        grid-template-columns: 90px 1fr;
        gap: 10px;
        align-items: center;
        text-decoration: none;
        color: var(--bm-title);
        border-bottom: 1px solid rgba(42, 109, 101, 0.10);
        padding: 10px 0;
    }
    .latest-post-link:last-child,
    .recommend-link:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }
    .latest-post-link img,
    .recommend-link img {
        width: 90px;
        height: 72px;
        object-fit: cover;
        border-radius: 10px;
        background: #eaf8f5;
    }
    .recommend-link span {
        display: grid;
        gap: 4px;
    }
    .recommend-link small {
        color: #2f7f76;
        font-size: 13px;
        font-weight: 700;
    }
</style>
@endpush
