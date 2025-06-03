<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class ProductService
{
    public function getPaginatedProducts(int $perPage = 20, int $page = 1): LengthAwarePaginator
    {
        $cacheKey = "products_page_{$page}_{$perPage}";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($perPage, $page) {
            return Product::where('active', 1)->orderBy('title')->paginate($perPage, ['*'], 'page', $page);
        });
    }

    public function getPaginatedProductsByCategory(int $categoryId, int $perPage = 20, int $page = 1): LengthAwarePaginator
    {
        $cacheKey = "products_category_{$categoryId}_page_{$page}_{$perPage}";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($categoryId, $perPage, $page) {
            return Product::where('category_id', $categoryId)
                ->where('active')
                ->orderBy('title')
                ->paginate($perPage, ['*'], 'page', $page);
        });
    }
}
