<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'index'])->name('products.index');

Route::get('/product/:id', [ProductController::class, 'index'])->name('products.show');

Route::get('/category/{category_name}', [ProductController::class, 'getCategoryProducts'])->name('products-category.show');

Route::get('products-vue/', [ProductController::class, 'productsVue'])->name('products.index-vue');
