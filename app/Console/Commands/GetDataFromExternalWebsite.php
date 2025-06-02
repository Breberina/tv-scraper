<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GetDataFromExternalWebsite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-data-from-external-website';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        app(\App\Services\Scraper\CategoryScraperService::class)->scrapeCategories();
    }
}
