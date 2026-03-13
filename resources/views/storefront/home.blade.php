@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
<section class="hero p-4 p-lg-5 mb-4">
    <div class="row align-items-center">
        <div class="col-lg-6">
            <span class="badge badge-soft rounded-pill px-3 py-2 mb-3">Mua sắm tiện hơn cho mẹ và bé</span>
            <h1 class="display-5 fw-bold">Từ sữa, tã đến xe đẩy và đồ chơi, mọi thứ cần thiết cho bé đều có tại đây</h1>
            <p class="lead text-muted">Chọn nhanh sản phẩm chính hãng, giá tốt mỗi ngày và dễ dàng tìm đúng món phù hợp với từng giai đoạn phát triển của bé.</p>
            <div class="d-flex gap-2 flex-wrap">
                <a class="btn btn-warning btn-lg" href="{{ route('shop.index') }}">Mua sắm ngay</a>
                <a class="btn btn-outline-dark btn-lg" href="{{ route('blog.index') }}">Xem cẩm nang</a>
            </div>
        </div>
        <div class="col-lg-6 mt-4 mt-lg-0">
            <div class="hero-media">
                <img class="banner-img" src="{{ $banners->first()->image ?? 'https://images.unsplash.com/photo-1515488042361-ee00e0ddd4e4?q=80&w=1200&auto=format&fit=crop' }}" alt="Banner trang chủ">
            </div>
        </div>
    </div>
</section>

<section class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h3 mb-0">Danh mục nổi bật</h2>
        <a href="{{ route('shop.index') }}" class="btn btn-sm btn-outline-dark">Xem tất cả</a>
    </div>
    <div class="row g-3">
        @foreach($featuredCategories as $category)
            <div class="col-md-6 col-lg-3">
                <div class="featured-category-card h-100">
                    <img
                        class="featured-category-icon"
                        src="{{ $category->icon ?: $category->image ?: asset('babymart-assets/img/category/category_card1_1.png') }}"
                        alt="{{ $category->name }}"
                    >
                    <div class="featured-category-name">{{ $category->name }}</div>
                    <div class="featured-category-age">{{ $category->age_group ?: 'Đa dạng độ tuổi' }}</div>
                    <div class="featured-category-children">
                        {{ $category->description ?: $category->children->pluck('name')->take(3)->implode(', ') }}
                    </div>
                    <a href="{{ route('shop.category', $category) }}" class="featured-category-link">
                        Mua ngay
                        <i class="far fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</section>

@foreach([
    'Sản phẩm nổi bật' => $featuredProducts,
    'Bán chạy nhất' => $bestSellers,
    'Gợi ý cho mẹ' => $recommendedProducts,
] as $title => $items)
<section class="mb-4">
    <h2 class="h3 mb-3">{{ $title }}</h2>
    <div class="row g-3">
        @foreach($items as $product)
            <div class="col-md-6 col-lg-3">
                <div class="product-card section-card h-100">
                    <div class="product-thumb">
                        <a href="{{ route('shop.product', $product) }}" class="d-block h-100">
                            <img src="{{ $product->thumbnail ?: 'https://images.unsplash.com/photo-1544126592-807ade215a0b?q=80&w=900&auto=format&fit=crop' }}" alt="{{ $product->name }}">
                        </a>
                    </div>
                    <div class="p-3">
                        <div class="small text-muted">{{ $product->brand }}</div>
                        <a class="fw-semibold text-decoration-none d-block mb-2" href="{{ route('shop.product', $product) }}">{{ $product->name }}</a>
                        <div class="mb-2">
                            <span class="price">{{ number_format($product->final_price, 0, ',', '.') }}đ</span>
                            @if($product->sale_price)
                                <span class="old-price ms-2">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                            @endif
                        </div>
                        <form action="{{ route('cart.store', $product) }}" method="post">
                            @csrf
                            <button class="btn btn-warning w-100">Thêm vào giỏ</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endforeach
@endsection
