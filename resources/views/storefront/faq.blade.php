@extends('layouts.app')

@section('title', 'Hỏi đáp')

@section('content')
<section class="section-card p-4 p-lg-5 mb-4 faq-hero">
    <div class="row g-4 align-items-center">
        <div class="col-lg-7">
            <span class="badge badge-soft rounded-pill px-3 py-2 mb-3">Trung tâm hỗ trợ</span>
            <h1 class="h2 mb-3">Câu hỏi thường gặp khi mua sắm tại BabyMart Plus</h1>
            <p class="text-muted mb-0">Tổng hợp các thắc mắc phổ biến về sản phẩm, giao hàng, đổi trả và tư vấn chăm sóc bé để bạn dễ tra cứu nhanh.</p>
        </div>
        <div class="col-lg-5">
            <div class="faq-hero-media">
                <img src="/babymart-assets/img/hero/hero-thumb2-3.png" alt="Hỏi đáp">
            </div>
        </div>
    </div>
</section>

<div class="row g-4">
    <div class="col-lg-8">
        <section class="section-card p-4">
            <div class="faq-accordion">
                @foreach($faqs as $faq)
                    <article class="faq-item {{ $loop->first ? 'is-open' : '' }}">
                        <button class="faq-question" type="button" aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                            <span>{{ $faq['question'] }}</span>
                            <i class="far fa-angle-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p class="mb-0">{{ $faq['answer'] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    </div>
    <div class="col-lg-4">
        <section class="section-card p-4 mb-4">
            <h2 class="h5 mb-3">Cần hỗ trợ nhanh?</h2>
            <div class="faq-support-list">
                <div><i class="far fa-phone me-2"></i>Hotline: 0909 123 456</div>
                <div><i class="far fa-envelope me-2"></i>Email: cskh@babymartplus.vn</div>
                <div><i class="far fa-clock me-2"></i>Hỗ trợ: 08:00 - 22:00</div>
            </div>
            <a href="{{ route('contact') }}" class="theme-btn mt-3">Đến trang liên hệ</a>
        </section>

        <section class="section-card p-4">
            <h2 class="h5 mb-3">Cẩm nang mới</h2>
            @foreach($hotPosts as $post)
                <a class="faq-post-link" href="{{ route('blog.show', $post) }}">
                    <img src="{{ $post->thumbnail ?: '/babymart-assets/img/hero/hero-thumb2-2.png' }}" alt="{{ $post->title }}">
                    <span>{{ $post->title }}</span>
                </a>
            @endforeach
        </section>
    </div>
</div>
@endsection

@push('styles')
<style>
    .faq-hero {
        background: linear-gradient(180deg, #fff, #f8fffd);
    }
    .faq-hero-media {
        border-radius: 20px;
        overflow: hidden;
        aspect-ratio: 4 / 3;
        background: #eaf8f5;
    }
    .faq-hero-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .faq-accordion {
        display: grid;
        gap: 10px;
    }
    .faq-item {
        border: 1px solid rgba(42, 109, 101, 0.14);
        border-radius: 16px;
        background: #fff;
        transition: border-color .25s ease, box-shadow .25s ease, transform .2s ease;
    }
    .faq-question {
        width: 100%;
        border: 0;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 16px 18px;
        font-family: "Quicksand", sans-serif;
        font-weight: 700;
        color: var(--bm-title);
        text-align: left;
        cursor: pointer;
        border-radius: 16px;
        transition: background-color .25s ease, color .25s ease, border-radius .2s ease;
    }
    .faq-question i {
        transition: transform .25s ease;
    }
    .faq-answer {
        max-height: 0;
        overflow: hidden;
        opacity: 0;
        transition: max-height .3s ease, padding .2s ease, opacity .2s ease;
        padding: 0 18px;
        color: #4e6f74;
        border-radius: 0 0 16px 16px;
    }
    .faq-item:hover {
        transform: translateY(-1px);
    }
    .faq-item.is-open .faq-question {
        background: #f2fcfa;
        color: #17796f;
        border-radius: 16px 16px 0 0;
        border-bottom: 1px solid rgba(42, 109, 101, 0.12);
    }
    .faq-item.is-open .faq-question i {
        transform: rotate(180deg);
    }
    .faq-item.is-open .faq-answer {
        max-height: 260px;
        opacity: 1;
        padding: 12px 18px 16px;
    }
    .faq-item.is-open {
        border-color: rgba(27, 170, 157, 0.35);
        box-shadow: 0 10px 24px rgba(28, 149, 137, 0.10);
    }
    .faq-support-list {
        display: grid;
        gap: 8px;
        color: #537378;
    }
    .faq-post-link {
        display: grid;
        grid-template-columns: 82px 1fr;
        gap: 10px;
        align-items: center;
        text-decoration: none;
        color: var(--bm-title);
        padding: 8px 0;
        border-bottom: 1px solid rgba(42, 109, 101, 0.10);
    }
    .faq-post-link:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }
    .faq-post-link img {
        width: 82px;
        height: 68px;
        object-fit: cover;
        border-radius: 10px;
        background: #eaf8f5;
    }
</style>
@endpush

@push('scripts')
<script>
    (function () {
        var items = document.querySelectorAll('.faq-item');
        if (!items.length) return;

        items.forEach(function (item) {
            var btn = item.querySelector('.faq-question');
            if (!btn) return;
            btn.addEventListener('click', function () {
                var isOpen = item.classList.contains('is-open');
                items.forEach(function (it) {
                    it.classList.remove('is-open');
                    var b = it.querySelector('.faq-question');
                    if (b) b.setAttribute('aria-expanded', 'false');
                });
                if (!isOpen) {
                    item.classList.add('is-open');
                    btn.setAttribute('aria-expanded', 'true');
                }
            });
        });
    })();
</script>
@endpush
