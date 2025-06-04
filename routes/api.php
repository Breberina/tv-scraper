<?php

use App\Http\Controllers\Api\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;


Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/category/{id}', [ProductController::class, 'category']);
Route::get('/categories', [CategoryController::class, 'index']);
