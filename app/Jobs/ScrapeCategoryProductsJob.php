<?php

namespace App\Jobs;

use App\Models\Category;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class ScrapeCategoryProductsJob implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Category $category;
    protected string $customUrl;

    public function __construct(Category $category, string $customUrl)
    {
        $this->category = $category;
        $this->customUrl = $customUrl;
    }

    public function handle(): void
    {
        logger()->info("Preuzimanje proizvoda za kategoriju: {$this->category->title}");

        $client = HttpClient::create();
        $page = 1;

        while (true) {
            $url = $this->customUrl . '?page=' . $page;
            logger()->info("Strana $page: $url");

            try {
                $response = $client->request('GET', $url);
                $html = $response->getContent();
                $crawler = new Crawler($html);
            } catch (\Throwable $e) {
                logger()->error("Greska na strani $page: " . $e->getMessage() .' '. $url);
                break;
            }

            $products = $crawler->filter('#pr_view_grid .b-paging-product');
            if ($products->count() === 0) {
                logger()->info("Nema vise proizvoda u kategoriji: {$this->category->title}");
                break;
            }

            $products->each(function (Crawler $node) {
                try {
                    if (
                        !$node->filter('.l3-product-title')->count() ||
                        !$node->filter('a[data-label-d="title"]')->count() ||
                        !$node->filter('.b-paging-product__price')->count() ||
                        !$node->filter('.image-cont img')->count()
                    ) {
                        return;
                    }

                    $title = trim($node->filter('.l3-product-title')->text());
                    $price = $this->extractPriceFromAttribute($node);
                    $url = Str::slug($title,'-');
                    $image = $node->filter('.image-cont img')->attr('src');

                    dispatch(new \App\Jobs\ProcessProductJob([
                        'title' => $title,
                        'url' => $url,
                        'price' => $price,
                        'image' => $image,
                    ], $this->category->id));

                } catch (\Throwable $e) {
                    logger()->error("Greška u parsiranju proizvoda: " . $e->getMessage() .  $e->getFile().':' . $e->getLine());
                }
            });

            $page++;

            sleep(1);
        }
    }

    function extractPriceFromAttribute($node): ?float
    {
        if (!$node->filter('.b-paging-product__price')->count()) {
            return null;
        }

        $raw = $node->filter('.b-paging-product__price')->attr('event-viewitem-price');

        return is_numeric($raw) ? (float) $raw : null;
    }
}
