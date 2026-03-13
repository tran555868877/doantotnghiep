<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\StorefrontController;
use Illuminate\Support\Facades\Route;

Route::middleware('track.behavior')->group(function () {
    Route::get('/', [StorefrontController::class, 'index'])->name('home');
    Route::get('/shop', [StorefrontController::class, 'shop'])->name('shop.index');
    Route::get('/category/{category:slug}', [StorefrontController::class, 'category'])->name('shop.category');
    Route::get('/product/{product:slug}', [StorefrontController::class, 'product'])->name('shop.product');
    Route::get('/about', [StorefrontController::class, 'about'])->name('about');
    Route::get('/contact', [StorefrontController::class, 'contact'])->name('contact');
    Route::post('/contact', [StorefrontController::class, 'contactStore'])->name('contact.store');
    Route::get('/faq', [StorefrontController::class, 'faq'])->name('faq');

    Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/{product}', [CartController::class, 'store'])->name('cart.store');
    Route::post('/cart/item/{itemId}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/item/{itemId}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/thank-you/{order}', [CheckoutController::class, 'thankYou'])->name('checkout.thankyou');

    Route::middleware('auth')->group(function () {
        Route::get('/account', [StorefrontController::class, 'account'])->name('account');
        Route::post('/account/profile', [StorefrontController::class, 'updateAccountProfile'])->name('account.profile.update');
        Route::post('/account/password', [StorefrontController::class, 'updateAccountPassword'])->name('account.password.update');
    });
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', AdminDashboardController::class)->name('dashboard');
    Route::resource('categories', AdminCategoryController::class)->except(['show']);
    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::resource('orders', AdminOrderController::class)->except(['create', 'store']);
    Route::resource('posts', AdminPostController::class)->except(['show']);
    Route::resource('customers', AdminCustomerController::class)->only(['index', 'show']);
});
