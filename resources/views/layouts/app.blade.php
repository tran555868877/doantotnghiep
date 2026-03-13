<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'BabyMart Plus')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&family=Source+Sans+3:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('babymart-assets/css/app.min.css') }}">
    <link rel="stylesheet" href="{{ asset('babymart-assets/css/fontawesome.min.css') }}">
    <style>
        :root {
            --bm-theme: #00bba7;
            --bm-theme-2: #33d1bf;
            --bm-theme-3: #fe5a86;
            --bm-title: #20343a;
            --bm-text: #61767b;
            --bm-bg: #effaf8;
            --bm-bg-2: #e0f5f1;
            --bm-card: #ffffff;
            --bm-line: rgba(32, 52, 58, 0.08);
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Source Sans 3", sans-serif;
            background:
                radial-gradient(circle at top right, rgba(0, 187, 167, 0.14), transparent 24%),
                radial-gradient(circle at left center, rgba(51, 209, 191, 0.10), transparent 20%),
                linear-gradient(180deg, #f7fffd 0%, var(--bm-bg) 100%);
            color: var(--bm-text);
        }

        h1, h2, h3, h4, h5, h6, .site-brand, .menu-link { font-family: "Quicksand", sans-serif; color: var(--bm-title); }
        a { color: inherit; text-decoration: none; }
        .page-shell { min-height: 100vh; }
        .topbar {
            background: linear-gradient(90deg, var(--bm-theme), #53d8cb);
            color: #fff;
            font-size: 14px;
        }
        .topbar-inner, .header-inner, .footer-inner, .content-container {
            width: min(1280px, calc(100% - 32px));
            margin: 0 auto;
        }
        .topbar-inner {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            padding: 10px 0;
        }
        .header-wrap {
            position: sticky;
            top: 0;
            z-index: 30;
            background: rgba(255,255,255,0.94);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--bm-line);
        }
        .header-inner {
            display: grid;
            grid-template-columns: 138px 1fr auto;
            align-items: center;
            gap: 20px;
            padding: 18px 0;
        }
        .site-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        }
        .site-brand img {
            width: 96px;
            height: 96px;
            object-fit: contain;
            display: block;
            filter: drop-shadow(0 8px 18px rgba(42, 109, 101, 0.16));
        }
        .search-row {
            display: grid;
            grid-template-columns: 230px 1fr 60px;
            background: #fff;
            border: 2px solid #ccefe8;
            border-radius: 999px;
            overflow: hidden;
        }
        .search-row select, .search-row input {
            border: 0;
            padding: 14px 18px;
            background: transparent;
            outline: none;
            color: var(--bm-title);
        }
        .search-row select { border-right: 1px solid #def4ef; }
        .search-row button {
            border: 0;
            background: linear-gradient(135deg, var(--bm-theme), var(--bm-theme-2));
            color: #fff;
        }
        .header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .icon-pill, .action-pill {
            border: 1px solid #d4efea;
            background: #fff;
            color: var(--bm-title);
            border-radius: 999px;
            padding: 12px 16px;
            transition: .2s ease;
        }
        .cart-pill {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 48px;
            height: 48px;
        }
        .cart-badge {
            position: absolute;
            top: -7px;
            right: -6px;
            min-width: 22px;
            height: 22px;
            padding: 0 6px;
            border-radius: 999px;
            background: #fe5a86;
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #fff;
            box-shadow: 0 8px 16px rgba(254, 90, 134, 0.28);
            line-height: 1;
        }
        .icon-pill:hover, .action-pill:hover, .menu-link:hover { color: var(--bm-theme); }
        .theme-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 22px;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--bm-theme), var(--bm-theme-2));
            color: #fff;
            border: 0;
            font-weight: 700;
        }
        .nav-band {
            width: min(1280px, calc(100% - 32px));
            margin: 0 auto 22px;
            background: #fff;
            border: 1px solid var(--bm-line);
            border-radius: 18px;
            box-shadow: 0 14px 40px rgba(42, 109, 101, 0.10);
            display: grid;
            grid-template-columns: 280px 1fr;
        }
        .catalog-trigger {
            position: relative;
            padding: 18px 20px;
            background: linear-gradient(135deg, #e7faf5, #f4fffc);
            border-right: 1px solid var(--bm-line);
        }
        .catalog-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-weight: 700;
            color: var(--bm-title);
        }
        .catalog-menu {
            display: none;
            position: absolute;
            left: 12px;
            width: 256px;
            top: calc(100% - 4px);
            background: #fff;
            border-radius: 20px;
            border: 1px solid var(--bm-line);
            box-shadow: 0 22px 50px rgba(42, 109, 101, 0.16);
            padding: 10px;
        }
        .catalog-trigger:hover .catalog-menu { display: block; }
        .catalog-item {
            position: relative;
            border-radius: 14px;
            transition: background .2s ease;
        }
        .catalog-item:hover {
            background: #f2fdf9;
        }
        .catalog-parent {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 14px;
            font-weight: 700;
            color: var(--bm-title);
        }
        .catalog-parent-main {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }
        .catalog-icon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            object-fit: contain;
            background: #eefcf8;
            padding: 5px;
            flex: 0 0 auto;
        }
        .catalog-submenu {
            display: none;
            position: absolute;
            left: calc(100% + 10px);
            top: 0;
            width: 300px;
            background: #fff;
            border-radius: 20px;
            border: 1px solid var(--bm-line);
            box-shadow: 0 22px 50px rgba(42, 109, 101, 0.16);
            padding: 14px;
        }
        .catalog-item:hover .catalog-submenu { display: block; }
        .catalog-submenu-title {
            font-family: "Quicksand", sans-serif;
            font-size: 18px;
            font-weight: 700;
            color: var(--bm-title);
            margin-bottom: 12px;
        }
        .catalog-submenu-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }
        .catalog-submenu-grid a {
            padding: 10px 12px;
            border-radius: 12px;
            background: #f4fffc;
            color: var(--bm-text);
            font-size: 14px;
        }
        .primary-menu {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 28px;
            padding: 0 28px;
        }
        .menu-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 18px 0;
            font-weight: 700;
        }
        .content-container { padding-bottom: 60px; }
        .featured-category-card {
            position: relative;
            padding: 24px 22px;
            border-radius: 24px;
            background: linear-gradient(180deg, #ffffff 0%, #f4fffc 100%);
            box-shadow: 0 18px 36px rgba(42, 109, 101, 0.10);
            border: 1px solid rgba(42, 109, 101, 0.08);
            overflow: hidden;
            transition: transform .2s ease, box-shadow .2s ease;
            min-height: 320px;
            display: flex;
            flex-direction: column;
        }
        .featured-category-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 24px 42px rgba(42, 109, 101, 0.14);
        }
        .featured-category-card::after {
            content: "";
            position: absolute;
            width: 120px;
            height: 120px;
            right: -30px;
            top: -35px;
            border-radius: 999px;
            background: radial-gradient(circle, rgba(51, 209, 191, 0.18), transparent 68%);
        }
        .featured-category-icon {
            width: 82px;
            height: 82px;
            border-radius: 24px;
            object-fit: contain;
            background: linear-gradient(180deg, #e8fbf6 0%, #f9fffd 100%);
            padding: 14px;
            margin-bottom: 18px;
            position: relative;
            z-index: 1;
        }
        .featured-category-name {
            font-family: "Quicksand", sans-serif;
            font-size: 24px;
            font-weight: 700;
            color: var(--bm-title);
            line-height: 1.2;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
            min-height: 58px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .featured-category-age {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            border-radius: 999px;
            background: #edf9f6;
            color: #418078;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 14px;
            position: relative;
            z-index: 1;
        }
        .featured-category-children {
            color: var(--bm-text);
            min-height: 48px;
            margin-bottom: 18px;
            position: relative;
            z-index: 1;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .featured-category-link {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 18px;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--bm-theme), var(--bm-theme-2));
            color: #fff;
            font-weight: 700;
            position: relative;
            z-index: 1;
            margin-top: auto;
        }
        .hero-media {
            position: relative;
            overflow: hidden;
            border-radius: 28px;
            aspect-ratio: 4 / 3;
            background: #dff8f1;
            box-shadow: 0 18px 45px rgba(42, 109, 101, 0.14);
        }
        .hero-media img,
        .product-card img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
            object-position: center;
        }
        .product-card {
            overflow: hidden;
        }
        .product-card .product-thumb {
            aspect-ratio: 1 / 1;
            overflow: hidden;
            background: #eefcf8;
        }
        .alert {
            border-radius: 16px;
            border: 0;
            box-shadow: 0 10px 30px rgba(42, 109, 101, 0.08);
        }
        .footer-shell {
            background: linear-gradient(180deg, #f9fffd, #ebfaf6);
            border-top: 1px solid var(--bm-line);
            padding: 28px 0 40px;
        }
        .footer-inner {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            align-items: center;
        }
        .footer-brand {
            display: flex;
            align-items: center;
            gap: 14px;
            font-family: "Quicksand", sans-serif;
            font-weight: 700;
            color: var(--bm-title);
        }
        .footer-brand img { width: 40px; height: 40px; }
        @media (max-width: 1100px) {
            .catalog-submenu {
                position: static;
                width: auto;
                margin: 0 10px 10px;
                display: none;
            }
            .catalog-item:hover .catalog-submenu { display: block; }
        }
        @media (max-width: 992px) {
            .header-inner { grid-template-columns: 1fr; }
            .site-brand { justify-content: flex-start; }
            .search-row { grid-template-columns: 1fr; border-radius: 24px; }
            .search-row select { border-right: 0; border-bottom: 1px solid #def4ef; }
            .nav-band { grid-template-columns: 1fr; }
            .primary-menu { padding: 0 20px 18px; gap: 18px; }
            .footer-inner, .topbar-inner { flex-direction: column; align-items: flex-start; }
            .catalog-menu {
                width: auto;
                right: 12px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="page-shell">
    <div class="topbar">
        <div class="topbar-inner">
            <div>Flash sale mẹ và bé, ưu đãi mỗi ngày đến 30%</div>
            <div>Hotline: 0909 123 456 | Email: cskh@babymartplus.vn</div>
        </div>
    </div>

    <header class="header-wrap">
        <div class="header-inner">
            <a class="site-brand" href="{{ route('home') }}">
                <img src="{{ asset('babymart-assets/img/logo.svg') }}" alt="logo">
            </a>

            <form class="search-row" action="{{ route('shop.index') }}" method="get">
                <select name="category">
                    <option value="">Tất cả danh mục</option>
                    @foreach($menuCategories as $menuCategory)
                        <option value="{{ $menuCategory->slug }}" @selected(request('category') === $menuCategory->slug)>{{ $menuCategory->name }}</option>
                        @foreach($menuCategory->children as $child)
                            <option value="{{ $child->slug }}" @selected(request('category') === $child->slug)>- {{ $child->name }}</option>
                        @endforeach
                    @endforeach
                </select>
                <input type="text" name="q" placeholder="Tìm sữa, tã, xe đẩy, đồ chơi..." value="{{ request('q') }}">
                <button type="submit"><i class="far fa-search"></i></button>
            </form>

            <div class="header-actions">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a class="action-pill" href="{{ route('admin.dashboard') }}">Quản trị</a>
                    @endif
                    <a class="icon-pill" href="{{ route('account') }}"><i class="far fa-user"></i></a>
                    <form action="{{ route('logout') }}" method="post">@csrf<button class="action-pill">Đăng xuất</button></form>
                @else
                    <a class="action-pill" href="{{ route('login') }}">Đăng nhập</a>
                    <a class="theme-btn" href="{{ route('register') }}">Đăng ký</a>
                @endauth
                <a class="icon-pill cart-pill" href="{{ route('cart.index') }}" title="Giỏ hàng">
                    <i class="far fa-basket-shopping"></i>
                    <span class="cart-badge">{{ $cartItemsCount ?? 0 }}</span>
                </a>
            </div>
        </div>

        <div class="nav-band">
            <div class="catalog-trigger">
                <div class="catalog-title">
                    <span><i class="far fa-grid-2 me-2"></i>Danh mục sản phẩm</span>
                    <i class="far fa-angle-down"></i>
                </div>
                <div class="catalog-menu">
                    @foreach($menuCategories as $menuCategory)
                        <div class="catalog-item">
                            <a class="catalog-parent" href="{{ route('shop.category', $menuCategory) }}">
                                <span class="catalog-parent-main">
                                    <img class="catalog-icon" src="{{ $menuCategory->icon ?: $menuCategory->image ?: asset('babymart-assets/img/category/category_card1_1.png') }}" alt="{{ $menuCategory->name }}">
                                    <span>{{ $menuCategory->name }}</span>
                                </span>
                                @if($menuCategory->children->isNotEmpty())
                                    <i class="far fa-angle-right"></i>
                                @endif
                            </a>
                            @if($menuCategory->children->isNotEmpty())
                                <div class="catalog-submenu">
                                    <div class="catalog-submenu-title">{{ $menuCategory->name }}</div>
                                    <div class="catalog-submenu-grid">
                                        @foreach($menuCategory->children as $child)
                                            <a href="{{ route('shop.category', $child) }}">
                                                <img class="catalog-icon" style="width:28px;height:28px;margin-right:8px;" src="{{ $child->icon ?: $child->image ?: asset('babymart-assets/img/category/category_card1_2.png') }}" alt="{{ $child->name }}">
                                                {{ $child->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            <nav class="primary-menu">
                <a class="menu-link" href="{{ route('home') }}">Trang chủ</a>
                <a class="menu-link" href="{{ route('shop.index') }}">Cửa hàng</a>
                <a class="menu-link" href="{{ route('blog.index') }}">Cẩm nang</a>
                <a class="menu-link" href="{{ route('about') }}">Giới thiệu</a>
                <a class="menu-link" href="{{ route('faq') }}">Hỏi đáp</a>
                <a class="menu-link" href="{{ route('contact') }}">Liên hệ</a>
            </nav>
        </div>
    </header>

    <main class="content-container pt-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </main>

    <footer class="footer-shell">
        <div class="footer-inner">
            <div class="footer-brand">
                <img src="{{ asset('babymart-assets/img/logo-footer.svg') }}" alt="logo footer">
                <div>
                    <div>BabyMart Plus</div>
                    <div style="font-family:'Source Sans 3',sans-serif;font-weight:400;color:var(--bm-text);">Nền tảng thương mại điện tử mẹ và bé tích hợp phân tích hành vi.</div>
                </div>
            </div>
            <div style="text-align:right;">
                <div style="color:var(--bm-title);font-weight:700;">Liên hệ</div>
                <div>0909 123 456</div>
                <div>cskh@babymartplus.vn</div>
            </div>
        </div>
    </footer>
</div>
@stack('scripts')
</body>
</html>
