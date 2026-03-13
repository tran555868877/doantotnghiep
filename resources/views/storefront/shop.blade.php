@extends('layouts.app')

@section('title', 'Cửa hàng')

@section('content')
<section class="section-card p-4 p-lg-5 mb-4">
    <div class="row align-items-center g-4">
        <div class="col-lg-7">
            <span class="badge badge-soft rounded-pill px-3 py-2 mb-3">Cửa hàng mẹ và bé</span>
            <h1 class="h2 mb-3">Mua sắm sản phẩm chính hãng cho bé yêu và mẹ sau sinh</h1>
            <p class="mb-0 text-muted">Lọc theo danh mục, thương hiệu, độ tuổi và mức giá để tìm nhanh đúng sản phẩm gia đình bạn đang cần.</p>
        </div>
        <div class="col-lg-5">
            <form class="shop-search-box" method="get">
                <input class="form-control" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Tìm sữa, tã, xe đẩy, khăn ướt...">
                @foreach($filters['categories'] ?? [] as $slug)
                    <input type="hidden" name="categories[]" value="{{ $slug }}">
                @endforeach
                @foreach($filters['brands'] ?? [] as $brand)
                    <input type="hidden" name="brands[]" value="{{ $brand }}">
                @endforeach
                @foreach($filters['ages'] ?? [] as $age)
                    <input type="hidden" name="ages[]" value="{{ $age }}">
                @endforeach
                @foreach($filters['prices'] ?? [] as $price)
                    <input type="hidden" name="prices[]" value="{{ $price }}">
                @endforeach
                <button class="theme-btn w-100 justify-content-center" type="submit">Tìm sản phẩm</button>
            </form>
        </div>
    </div>
</section>

<div class="row g-4">
    <div class="col-lg-3">
        <form class="filter-sidebar section-card p-4" method="get">
            <div class="filter-block">
                <div class="filter-title">Từ khóa</div>
                <input class="form-control" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Nhập tên sản phẩm">
            </div>

            <div class="filter-block">
                <div class="filter-title">Danh mục sản phẩm</div>
                @foreach($categories as $cat)
                    <label class="filter-check parent-check">
                        <input type="checkbox" name="categories[]" value="{{ $cat->slug }}" @checked(in_array($cat->slug, $filters['categories'] ?? [], true))>
                        <span>{{ $cat->name }}</span>
                    </label>
                    @foreach($cat->children as $child)
                        <label class="filter-check child-check">
                            <input type="checkbox" name="categories[]" value="{{ $child->slug }}" @checked(in_array($child->slug, $filters['categories'] ?? [], true))>
                            <span>{{ $child->name }}</span>
                        </label>
                    @endforeach
                @endforeach
            </div>

            <div class="filter-block">
                <div class="filter-title">Thương hiệu</div>
                @foreach($brands as $brand)
                    <label class="filter-check">
                        <input type="checkbox" name="brands[]" value="{{ $brand }}" @checked(in_array($brand, $filters['brands'] ?? [], true))>
                        <span>{{ $brand }}</span>
                    </label>
                @endforeach
            </div>

            <div class="filter-block">
                <div class="filter-title">Độ tuổi</div>
                @foreach($ageGroups as $ageGroup)
                    <label class="filter-check">
                        <input type="checkbox" name="ages[]" value="{{ $ageGroup }}" @checked(in_array($ageGroup, $filters['ages'] ?? [], true))>
                        <span>{{ $ageGroup }}</span>
                    </label>
                @endforeach
            </div>

            <div class="filter-block">
                <div class="filter-title">Khoảng giá</div>
                @foreach($priceRanges as $key => $label)
                    <label class="filter-check">
                        <input type="checkbox" name="prices[]" value="{{ $key }}" @checked(in_array($key, $filters['prices'] ?? [], true))>
                        <span>{{ $label }}</span>
                    </label>
                @endforeach
            </div>

            <div class="d-grid gap-2">
                <button class="theme-btn justify-content-center" type="submit">Lọc sản phẩm</button>
                <a class="btn btn-outline-secondary rounded-pill" href="{{ route('shop.index') }}">Xóa bộ lọc</a>
            </div>
        </form>
    </div>

    <div class="col-lg-9">
        <div class="section-card p-4 mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <div class="small text-muted">Kết quả tìm thấy</div>
                <div class="h5 mb-0">{{ $products->total() }} sản phẩm</div>
            </div>
            @if(!empty($currentCategory))
                <div class="shop-current-category">Đang xem: {{ $currentCategory->name }}</div>
            @endif
        </div>

        <div class="row g-3">
            @forelse($products as $product)
                <div class="col-md-6 col-xl-4">
                    <div class="product-card section-card h-100">
                        <div class="product-thumb">
                            <a href="{{ route('shop.product', $product) }}" class="d-block h-100">
                                <img src="{{ $product->thumbnail ?: 'https://images.unsplash.com/photo-1544126592-807ade215a0b?q=80&w=900&auto=format&fit=crop' }}" alt="{{ $product->name }}">
                            </a>
                        </div>
                        <div class="p-3">
                            <div class="small text-muted">{{ $product->category->name }}</div>
                            <a class="fw-semibold text-decoration-none d-block mb-2" href="{{ route('shop.product', $product) }}">{{ $product->name }}</a>
                            <div class="small text-muted mb-2">{{ $product->brand }} | {{ $product->age_group }}</div>
                            <div class="mb-3">
                                <span class="price">{{ number_format($product->final_price, 0, ',', '.') }}đ</span>
                                @if($product->sale_price)
                                    <span class="old-price ms-2">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                @endif
                            </div>
                            <form action="{{ route('cart.store', $product) }}" method="post">
                                @csrf
                                <button class="btn btn-outline-warning w-100">Thêm vào giỏ</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning">Không tìm thấy sản phẩm phù hợp với bộ lọc hiện tại.</div>
                </div>
            @endforelse
        </div>

        <div class="mt-4">{{ $products->links() }}</div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .shop-search-box {
        display: grid;
        gap: 14px;
    }
    .filter-sidebar {
        position: sticky;
        top: 150px;
    }
    .filter-block + .filter-block {
        margin-top: 22px;
        padding-top: 22px;
        border-top: 1px solid rgba(32, 52, 58, 0.08);
    }
    .filter-title {
        font-family: "Quicksand", sans-serif;
        font-size: 18px;
        font-weight: 700;
        color: var(--bm-title);
        margin-bottom: 14px;
    }
    .filter-check {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
        color: var(--bm-title);
        cursor: pointer;
    }
    .filter-check input {
        width: 16px;
        height: 16px;
        accent-color: var(--bm-theme);
    }
    .child-check {
        padding-left: 18px;
        color: var(--bm-text);
    }
    .shop-current-category {
        display: inline-flex;
        align-items: center;
        padding: 10px 16px;
        border-radius: 999px;
        background: #eefaf7;
        color: #2b6d65;
        font-weight: 600;
    }
</style>
@endpush
