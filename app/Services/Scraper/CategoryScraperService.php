<?php

namespace App\Services\Scraper;

use App\Models\Category;
use App\Services\CategoryService;
use App\Services\ImageService;
use App\Services\SlugService;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class CategoryScraperService
{

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function scrapeCategories(): void
    {
        logger()->info('start');

        $client = HttpClient::create();
        $url = 'https://www.shoptok.si/tv-prijamnici/cene/56';

        $response = $client->request('GET', $url);
        $html = $response->getContent();
        $crawler = new Crawler($html);

        $crawler->filter('.category_desktop_subcategories_l3_module .col-4')->each(function (Crawler $node, $index) {
            try {
                $titleNode = $node->filter('h3');
                $imageNode = $node->filter('picture source')->first();

                if (
                    !$titleNode->count() ||
                    !$imageNode->count()
                ) {
                    logger()->warning("Kategorija #$index preskočena – nedostaje podatak.");
                    return;
                }

                $title = trim($titleNode->text());
                $imageExternalUrl = $imageNode->attr('srcset');
                $image = ImageService::downloadImage($imageExternalUrl, $title, 'categories');
                $verboseId = Str::slug($title, '-');
                $url = SlugService::generateUniqueSlug($title, Category::class, 'url');

                if (empty($title) || empty($image)) {
                    logger()->warning("Nepotpuni podaci kategorija #$index preskočena.");
                    return;
                }

                $category = $this->categoryService->storeFromScraper([
                    'verbose_id' => $verboseId,
                    'url' => $url,
                    'title' => $title,
                    'image' => $image,
                ]);

                if ($category) {
                    logger()->info("Sačuvana kategorija: {$category->title}");
                } else {
                    logger()->warning("Kategorija nije sačuvana: $title");
                }

            } catch (\Throwable $e) {
                logger()->error("Greška u parsiranju kategorije #$index: {$e->getMessage()}");
            }
        });
    }
}
