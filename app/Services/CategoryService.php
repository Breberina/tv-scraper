<?php

namespace App\Services;
use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CategoryService
{

    public function storeFromScraper(array $data): Category
    {
        return Category::firstOrCreate(
            ['verbose_id' => $data['verbose_id']],
            [
                'url' => $data['url'],
                'title' => $data['title'],
                'image' => $data['image'],
            ]
        );
    }

    public function getAllCategories(): Collection
    {
        return Cache::remember('all_categories', now()->addHours(2), function () {
            return Category::orderBy('title')->get();
        });
    }
}
