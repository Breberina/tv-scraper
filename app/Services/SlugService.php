<?php

namespace App\Services;

use Illuminate\Support\Str;

class SlugService
{
    public static function generateUniqueSlug(string $title, string $modelClass, string $column = 'slug'): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        while ($modelClass::where($column, $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
