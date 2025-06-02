<?php

namespace App\Services;
use App\Models\Category;

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
}
