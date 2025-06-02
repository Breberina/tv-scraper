<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    public static function downloadImage(string $url, string $title, string $type = 'products'): ?string
    {
        try {
            $response = Http::timeout(10)->get($url);

            if (!$response->ok()) {
                return null;
            }

            $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
            $slug = Str::slug($title);
            $filename = $slug . '-' . time() . '.' . $extension;

            $path = "images/{$type}/{$filename}";

            Storage::disk('public')->put($path, $response->body());

            return $path;
        } catch (\Throwable $e) {
            logger()->error("Image download failed for '{$title}': " . $e->getMessage());
            return null;
        }
    }
}
