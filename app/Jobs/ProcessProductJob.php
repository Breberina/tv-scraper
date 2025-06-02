<?php

namespace App\Jobs;

use App\Models\Product;
use App\Services\ImageService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class ProcessProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $data;
    protected int $categoryId;

    public function __construct(array $data, int $categoryId)
    {
        $this->data = $data;
        $this->categoryId = $categoryId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $title = $this->data['title'];
            $slug = Str::slug($title);
            $url = $this->data['url'];
            $price = $this->data['price'];
            $imageUrl = $this->data['image'];

            $imagePath = ImageService::downloadImage(
                $imageUrl,
                $title,
                'products');

            Product::updateOrCreate(
                ['url' => $url],
                [
                    'title' => $title,
                    'price' => $price,
                    'image_path' => $imagePath,
                    'category_id' => $this->categoryId,
                    'active' => true,
                ]
            );

            logger()->info("Proizvod sačuvan: {$title}");

        } catch (\Throwable $e) {
            logger()->error("Greška prilikom obrade proizvoda: {$e->getMessage()}" .  $e->getFile().':' . $e->getLine());
        }
    }
}
