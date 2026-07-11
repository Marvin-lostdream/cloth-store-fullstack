<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

// ===== Global View =====
Route::view('/', 'front.index')->name('home');

// ===== Cart Routes =====
Route::get('/cart', function () {
    return view('front.cartPage');
})->name('cart')->middleware('auth');

Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');

// ===== Categories Views =====
Route::view('/men', 'front.categoryMen')->name('men');
Route::view('/women', 'front.categoryWomen')->name('women');
Route::view('/kids', 'front.categoryKids')->name('kids');
Route::view('/accessories', 'front.categoryAccessories')->name('accessories');

// ===== Authenticate Routes (Guest) =====
Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::view('/register', 'auth.register')->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// ===== Admin Routes =====
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [ProductController::class, 'dashboard'])->name('dashboard');
    Route::post('product/create', [ProductController::class, 'createProduct'])->name('product.create');
    Route::get('product/{id}/edit', [ProductController::class, 'getProductForEdit'])->name('product.get.edit');
    Route::put('product/{id}/edit', [ProductController::class, 'editProduct'])->name('product.edit');
    Route::delete('product/{id}/delete', [ProductController::class, 'deleteProduct'])->name('product.delete');
});

// ===== User Routes (Authenticated) =====
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
