<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Quản trị')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;600;700&family=Source+Sans+3:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --ad-black: #0f0f10;
            --ad-black-2: #171718;
            --ad-white: #ffffff;
            --ad-line: rgba(255, 255, 255, 0.14);
            --ad-text: #e9e9ea;
            --ad-muted: #9b9ba1;
        }
        body {
            font-family: "Source Sans 3", sans-serif;
            background: #141415;
            color: #111;
        }
        .admin-shell {
            min-height: 100vh;
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--ad-black), var(--ad-black-2));
            color: var(--ad-text);
            border-right: 1px solid var(--ad-line);
            padding: 24px 18px;
        }
        .sidebar h4 {
            font-family: "Quicksand", sans-serif;
            font-weight: 700;
            color: #fff;
            margin-bottom: 18px;
        }
        .sidebar a {
            color: #d8d8dc;
            text-decoration: none;
            display: block;
            padding: 11px 12px;
            border-radius: 12px;
            margin-bottom: 7px;
            transition: background .2s ease, color .2s ease;
        }
        .sidebar a:hover,
        .sidebar a.is-active {
            background: #242427;
            color: #fff;
        }
        .admin-content {
            padding: 26px;
            background: #f4f4f5;
        }
        .panel {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #ececef;
            box-shadow: 0 12px 26px rgba(0, 0, 0, 0.07);
        }
        .table > :not(caption) > * > * {
            padding-top: 0.8rem;
            padding-bottom: 0.8rem;
        }
        .table thead th {
            font-weight: 700;
            color: #18181b;
            border-bottom-color: #d8d8dd;
        }
        .btn-primary {
            background: #141416;
            border-color: #141416;
        }
        .btn-primary:hover {
            background: #000;
            border-color: #000;
        }
        .btn-outline-primary {
            color: #111;
            border-color: #191919;
        }
        .btn-outline-primary:hover {
            background: #191919;
            border-color: #191919;
        }
        .form-control, .form-select {
            border-radius: 12px;
            border-color: #d2d2d8;
        }
        .form-control:focus, .form-select:focus {
            border-color: #111;
            box-shadow: 0 0 0 .2rem rgba(17, 17, 17, .12);
        }
        .alert {
            border-radius: 12px;
        }
        @media (max-width: 991px) {
            .sidebar {
                min-height: auto;
            }
            .admin-content {
                padding: 18px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="container-fluid admin-shell">
    <div class="row">
        <aside class="col-lg-2 sidebar">
            <h4>Quản trị</h4>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">Dashboard</a>
            <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'is-active' : '' }}">Danh mục</a>
            <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'is-active' : '' }}">Sản phẩm</a>
            <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'is-active' : '' }}">Đơn hàng</a>
            <a href="{{ route('admin.posts.index') }}" class="{{ request()->routeIs('admin.posts.*') ? 'is-active' : '' }}">Bài viết</a>
            <a href="{{ route('admin.customers.index') }}" class="{{ request()->routeIs('admin.customers.*') ? 'is-active' : '' }}">Khách hàng</a>
            <a href="{{ route('home') }}">Ra website</a>
        </aside>
        <main class="col-lg-10 admin-content">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
