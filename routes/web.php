<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;





// Global View

Route::view('/', 'front.index')->name('home');


// ======= Cart Page =======
Route::get('/cart', function () {
    return view('front.cartPage');
})->name('cart')->middleware('auth');
// ======= Categories Views =======

Route::view('/men', 'front.categoryMen')->name('men');

Route::view('/women', 'front.categoryWomen')->name('women');

Route::view('/kids', 'front.categoryKids')->name('kids');

Route::view('/accessories', 'front.categoryAccessories')->name('accessories');





// Start Authenticate Routes


Route::middleware('guest')->group(function () {

    Route::view('/login', 'auth.login')->name('login');
    Route::post('/login', [LoginController::class, 'login']);


    Route::view('/register', 'auth.register')->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});







// End Authenticate Routes


// Start Admin Views


Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [ProductController::class, 'dashboard'])->name('dashboard');
    Route::post('product/create', [ProductController::class, 'createProduct'])->name('product.create');
    Route::get('product/{id}/edit', [ProductController::class, 'getProductForEdit'])->name('product.get.edit');
    Route::put('product/{id}/edit', [ProductController::class, 'editProduct'])->name('product.edit');
    Route::delete('product/{id}/delete', [ProductController::class, 'deleteProduct'])->name('product.delete');
});


// End Admin Views




// Start User Views

Route::middleware(['auth', 'user'])->group(function () {
    Route::view('/cart', 'front.cartPage')->name('cart');
    Route::post('/logout', [loginController::class, 'logout'])->name('logout');
});
