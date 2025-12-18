<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\Admin\ReportController;
use Illuminate\Support\Facades\Route;

// Welcome page dengan splash screen
Route::get('/', function () {
    return view('layouts.pages.welcome');
})->name('welcome');

// Static pages - Kebijakan Privasi & Syarat Ketentuan
Route::get('/kebijakan-privasi', function () {
    return view('layouts.pages.privacy-policy');
})->name('privacy-policy');

Route::get('/syarat-ketentuan', function () {
    return view('layouts.pages.terms');
})->name('terms');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Home route (protected)
Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');

// Products page (protected)
Route::get('/products', [HomeController::class, 'products'])->name('products.index')->middleware('auth');

// Products filter API (public)
Route::get('/api/products/filter', [HomeController::class, 'filterProducts'])->name('products.filter');

// Cart routes (protected)
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add')
        ->middleware(\App\Http\Middleware\EnsureJsonRequest::class);
    Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update')
        ->middleware(\App\Http\Middleware\EnsureJsonRequest::class);
    Route::delete('/cart/{id}', [CartController::class, 'delete'])->name('cart.delete')
        ->middleware(\App\Http\Middleware\EnsureJsonRequest::class);
    Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count')
        ->middleware(\App\Http\Middleware\EnsureJsonRequest::class);
});

// Favorite routes (protected)
Route::middleware('auth')->group(function () {
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle')
        ->middleware(\App\Http\Middleware\EnsureJsonRequest::class);
    Route::delete('/favorites/{id}', [FavoriteController::class, 'remove'])->name('favorites.remove')
        ->middleware(\App\Http\Middleware\EnsureJsonRequest::class);
    Route::get('/favorites/count', [FavoriteController::class, 'count'])->name('favorites.count')
        ->middleware(\App\Http\Middleware\EnsureJsonRequest::class);
    Route::get('/favorites/list', [FavoriteController::class, 'list'])->name('favorites.list')
        ->middleware(\App\Http\Middleware\EnsureJsonRequest::class);
});

// Profile routes (protected)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Checkout routes (protected)
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/payment/{order}', [CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::get('/checkout/finish/{order}', [CheckoutController::class, 'finish'])->name('checkout.finish');
});

// Midtrans webhook callback (no auth required)
Route::post('/midtrans/callback', [CheckoutController::class, 'callback'])->name('midtrans.callback');

// Order routes (protected)
Route::middleware('auth')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/orders/{order}/retry-payment', [OrderController::class, 'retryPayment'])->name('orders.retry-payment');
});

// Midtrans callback (no auth required)
Route::post('/payment/callback', [CheckoutController::class, 'callback'])->name('payment.callback');

// Subscriber routes (no auth required)
Route::post('/subscribe', [SubscriberController::class, 'subscribe'])->name('subscribe');
Route::post('/unsubscribe', [SubscriberController::class, 'unsubscribe'])->name('unsubscribe');
Route::get('/check-subscription', [SubscriberController::class, 'checkSubscription'])->name('check.subscription');

// Admin Report Export routes (protected - admin only)
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    // PDF Exports
    Route::get('/orders/export-pdf', [ReportController::class, 'exportOrdersPdf'])->name('orders.export-pdf');
    Route::get('/products/export-pdf', [ReportController::class, 'exportProductsPdf'])->name('products.export-pdf');
    Route::get('/sales-summary/export-pdf', [ReportController::class, 'exportSalesSummaryPdf'])->name('sales-summary.export-pdf');
    
    // Excel Exports
    Route::get('/orders/export-excel', [ReportController::class, 'exportOrdersExcel'])->name('orders.export-excel');
    Route::get('/products/export-excel', [ReportController::class, 'exportProductsExcel'])->name('products.export-excel');
    Route::get('/sales-summary/export-excel', [ReportController::class, 'exportSalesSummaryExcel'])->name('sales-summary.export-excel');
});
