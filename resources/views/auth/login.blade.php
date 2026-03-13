@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')
<section class="auth-shell">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="section-card p-4 p-lg-5 auth-card">
                <h1 class="h3 mb-2 auth-title">Đăng nhập</h1>
                <p class="text-muted mb-4">Đăng nhập để theo dõi đơn hàng và nhận gợi ý sản phẩm phù hợp.</p>

                <form method="post" action="{{ route('login.store') }}" class="row g-3 auth-form">
                    @csrf
                    <div class="col-12">
                        <label class="form-label">Email</label>
                        <input class="form-control auth-input" type="email" name="email" placeholder="Nhập email của bạn" value="{{ old('email') }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Mật khẩu</label>
                        <input class="form-control auth-input" type="password" name="password" placeholder="Nhập mật khẩu" required>
                    </div>
                    <div class="col-12 pt-2">
                        <button class="auth-btn w-100">Đăng nhập</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .auth-shell {
        padding: 18px 0 8px;
    }
    .auth-card {
        background: #fff;
        border-radius: 28px;
        box-shadow: 0 20px 44px rgba(28, 102, 94, 0.14);
        border: 1px solid rgba(42, 109, 101, 0.08);
    }
    .auth-title {
        font-family: "Quicksand", sans-serif;
        font-weight: 700;
        color: #173f44;
    }
    .auth-form .form-label {
        font-weight: 700;
        color: #2b5550;
        margin-bottom: 7px;
    }
    .auth-input {
        border-radius: 16px;
        border: 1px solid rgba(42, 109, 101, 0.22);
        min-height: 50px;
        padding: 12px 16px;
        background: #fbfffe;
    }
    .auth-input:focus {
        border-color: #1fb7a9;
        box-shadow: 0 0 0 0.24rem rgba(31, 183, 169, 0.16);
        background: #fff;
    }
    .auth-btn {
        border: 0;
        border-radius: 18px;
        min-height: 50px;
        font-weight: 700;
        color: #fff;
        background: linear-gradient(135deg, #00bba7, #33d1bf);
        box-shadow: 0 14px 28px rgba(0, 187, 167, 0.25);
    }
</style>
@endpush
