@extends('layouts.app')

@section('title', $product->name)

@php
    $gallery = collect([$product->thumbnail])
        ->merge($product->gallery ?? [])
        ->filter()
        ->unique()
        ->values();
    $specs = collect($product->attributes ?? []);
@endphp

@section('content')
<section class="section-card p-4 p-lg-5 mb-4 product-detail-shell">
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="product-main-media mb-3">
                <img
                    id="product-main-image"
                    src="{{ $gallery->first() ?: 'https://images.unsplash.com/photo-1544126592-807ade215a0b?q=80&w=900&auto=format&fit=crop' }}"
                    alt="{{ $product->name }}"
                >
            </div>
            <div class="row g-2">
                @foreach($gallery->take(6) as $image)
                    <div class="col-4 col-md-3">
                        <button
                            type="button"
                            class="product-thumb-mini {{ $loop->first ? 'is-active' : '' }}"
                            data-gallery-thumb
                            data-image-src="{{ $image }}"
                            aria-label="Xem ảnh {{ $loop->iteration }}"
                        >
                            <img src="{{ $image }}" alt="{{ $product->name }}">
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-lg-6">
            <div class="detail-category mb-2">{{ $product->category->name }}</div>
            <h1 class="detail-title">{{ $product->name }}</h1>

            <div class="d-flex align-items-end gap-2 mb-3">
                <div class="price fs-2">{{ number_format($product->final_price, 0, ',', '.') }}đ</div>
                @if($product->sale_price)
                    <div class="old-price mb-1">{{ number_format($product->price, 0, ',', '.') }}đ</div>
                @endif
            </div>

            <p class="text-muted mb-3">{{ $product->short_description }}</p>

            <div class="row g-2 mb-4 detail-meta">
                <div class="col-6"><strong>Thương hiệu:</strong> {{ $product->brand }}</div>
                <div class="col-6"><strong>Mã SKU:</strong> {{ $product->sku }}</div>
                <div class="col-6"><strong>Model:</strong> {{ $specs->get('Model', 'Đang cập nhật') }}</div>
                <div class="col-6"><strong>Nhà sản xuất:</strong> {{ $specs->get('Nhà sản xuất', 'Đang cập nhật') }}</div>
                <div class="col-6"><strong>Xuất xứ:</strong> {{ $product->origin_country }}</div>
                <div class="col-6"><strong>Độ tuổi phù hợp:</strong> {{ $product->age_group }}</div>
                <div class="col-6"><strong>Đơn vị:</strong> {{ $product->unit }}</div>
                <div class="col-6"><strong>Tồn kho:</strong> {{ $product->stock }}</div>
            </div>

            <form action="{{ route('cart.store', $product) }}" method="post" class="row g-2 mb-3">
                @csrf
                <div class="col-md-3">
                    <input class="form-control" type="number" min="1" name="quantity" value="1">
                </div>
                <div class="col-md-9">
                    <button class="theme-btn w-100 justify-content-center">Thêm vào giỏ hàng</button>
                </div>
            </form>

            <div class="detail-policy">
                <div><i class="far fa-shield-check me-2"></i>Hàng chính hãng, hoàn tiền nếu phát hiện hàng giả</div>
                <div><i class="far fa-truck-fast me-2"></i>Giao nhanh nội thành 2h - 4h, toàn quốc 1 - 3 ngày</div>
                <div><i class="far fa-rotate-left me-2"></i>Đổi trả trong 7 ngày nếu lỗi do nhà sản xuất</div>
            </div>
        </div>
    </div>
</section>

<section class="section-card p-4 mb-4">
    <h2 class="h4 mb-3">Mô tả sản phẩm</h2>
    <p class="mb-0">{!! nl2br(e($product->description)) !!}</p>
</section>

<section class="section-card p-4 mb-4">
    <h2 class="h4 mb-3">Thông số kỹ thuật</h2>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <tbody>
                <tr><th style="width:220px;">Danh mục</th><td>{{ $product->category->name }}</td></tr>
                <tr><th>Thương hiệu</th><td>{{ $product->brand }}</td></tr>
                <tr><th>Model</th><td>{{ $specs->get('Model', 'Đang cập nhật') }}</td></tr>
                <tr><th>Nhà sản xuất</th><td>{{ $specs->get('Nhà sản xuất', 'Đang cập nhật') }}</td></tr>
                <tr><th>Xuất xứ</th><td>{{ $product->origin_country }}</td></tr>
                <tr><th>Độ tuổi</th><td>{{ $product->age_group }}</td></tr>
                <tr><th>Bảo hành</th><td>{{ $specs->get('Bảo hành', 'Không áp dụng') }}</td></tr>
                <tr><th>Bảo quản</th><td>{{ $specs->get('Bảo quản', 'Nơi khô ráo, thoáng mát') }}</td></tr>
            </tbody>
        </table>
    </div>
</section>

<section>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="h4 mb-0">Sản phẩm liên quan</h2>
        <a class="btn btn-sm btn-outline-dark rounded-pill px-3" href="{{ route('shop.index', ['category' => $product->category->slug]) }}">
            Xem thêm cùng danh mục
        </a>
    </div>
    <div class="row g-3">
        @foreach($relatedProducts as $item)
            <div class="col-md-6 col-lg-3">
                <article class="related-product-card h-100">
                    <a href="{{ route('shop.product', $item) }}" class="related-product-media">
                        <img src="{{ $item->thumbnail ?: 'https://images.unsplash.com/photo-1544126592-807ade215a0b?q=80&w=900&auto=format&fit=crop' }}" alt="{{ $item->name }}">
                        @if($item->sale_price)
                            <span class="related-badge">
                                -{{ max(1, round((($item->price - $item->sale_price) / max(1, $item->price)) * 100)) }}%
                            </span>
                        @endif
                    </a>
                    <div class="related-product-body">
                        <div class="small text-muted mb-1">{{ $item->brand }} · {{ $item->age_group }}</div>
                        <a href="{{ route('shop.product', $item) }}" class="related-product-title">{{ $item->name }}</a>
                        <div class="related-product-price">
                            <span class="price">{{ number_format($item->final_price, 0, ',', '.') }}đ</span>
                            @if($item->sale_price)
                                <span class="old-price ms-2">{{ number_format($item->price, 0, ',', '.') }}đ</span>
                            @endif
                        </div>
                        <form action="{{ route('cart.store', $item) }}" method="post">
                            @csrf
                            <button class="btn btn-outline-warning w-100 rounded-pill">Thêm vào giỏ</button>
                        </form>
                    </div>
                </article>
            </div>
        @endforeach
    </div>
</section>
@endsection

@push('styles')
<style>
    .product-detail-shell {
        background: linear-gradient(180deg, #fff, #f8fffd);
    }
    .detail-category {
        font-weight: 700;
        color: #2e7e75;
    }
    .detail-title {
        font-family: "Quicksand", sans-serif;
        font-weight: 700;
        line-height: 1.25;
        margin-bottom: 14px;
    }
    .product-main-media {
        border-radius: 20px;
        overflow: hidden;
        aspect-ratio: 1 / 1;
        background: #edf9f6;
    }
    .product-main-media img,
    .product-thumb-mini img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .product-thumb-mini {
        border: 0;
        width: 100%;
        padding: 0;
        cursor: pointer;
        border-radius: 14px;
        overflow: hidden;
        aspect-ratio: 1 / 1;
        background: #edf9f6;
        transition: transform .2s ease, box-shadow .2s ease, outline-color .2s ease;
        outline: 2px solid transparent;
    }
    .product-thumb-mini:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 18px rgba(42, 109, 101, 0.15);
    }
    .product-thumb-mini.is-active {
        outline-color: #25bfb0;
    }
    .detail-meta {
        padding: 14px;
        background: #f4fffc;
        border: 1px solid rgba(42, 109, 101, 0.10);
        border-radius: 16px;
    }
    .detail-policy {
        display: grid;
        gap: 8px;
        color: #436f69;
        font-size: 14px;
    }
    .related-product-card {
        display: flex;
        flex-direction: column;
        overflow: hidden;
        border-radius: 20px;
        border: 1px solid rgba(42, 109, 101, 0.10);
        box-shadow: 0 16px 30px rgba(42, 109, 101, 0.08);
        background: #fff;
        transition: transform .2s ease, box-shadow .2s ease;
    }
    .related-product-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 22px 36px rgba(42, 109, 101, 0.14);
    }
    .related-product-media {
        position: relative;
        display: block;
        aspect-ratio: 1 / 1;
        background: #edf9f6;
    }
    .related-product-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .related-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: #ff5f8f;
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        border-radius: 999px;
        padding: 5px 9px;
    }
    .related-product-body {
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding: 14px;
        height: 100%;
    }
    .related-product-title {
        min-height: 44px;
        color: var(--bm-title);
        font-weight: 700;
        line-height: 1.35;
        text-decoration: none;
    }
    .related-product-price {
        margin-top: auto;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var mainImage = document.getElementById('product-main-image');
        if (!mainImage) return;

        var thumbs = document.querySelectorAll('[data-gallery-thumb]');
        thumbs.forEach(function (thumb) {
            thumb.addEventListener('click', function () {
                var src = thumb.getAttribute('data-image-src');
                if (!src) return;
                mainImage.setAttribute('src', src);
                thumbs.forEach(function (item) { item.classList.remove('is-active'); });
                thumb.classList.add('is-active');
            });
        });
    });
</script>
@endpush
