<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/products/{category}/{subcategory?}', [ProductController::class, 'getProductsByCategory']);

Route::post('/checkout', [CartController::class, 'checkout'])
    ->middleware('auth')
    ->name('api.checkout');
