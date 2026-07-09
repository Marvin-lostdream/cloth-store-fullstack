<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::get('/products/{category}/{subcategory?}' , [ProductController::class , 'getProductsByCategory']);



