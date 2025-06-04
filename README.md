# TV Scraper

This Laravel 12 + Sail project scrapes product and category data from shoptok.si and displays it via a Bootstrap + Vue 3 frontend. Data is stored in a MySQL database, and image assets are downloaded locally.

## Features

- Scrapes all TV-related categories and paginated products.
- Saves categories, products, and product images in the database.
- Queued job system for efficient scraping.
- Vue 3 + Bootstrap 5 frontend with pagination.
- Laravel API backend.

## Requirements

- Laravel Sail

## Getting Started

### 1. Clone the repository and install dependencies

```bash
git clone <https://github.com/Breberina/tv-scraper.git>
cd tv-scraper
composer install
cp .env.example .env
```

### 2. Start the development environment with Sail

```bash
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
```

### 3. Run database migrations

```bash
./vendor/bin/sail artisan migrate
```

## Running the Scraper

The scraping system runs in two layers of jobs:

### Run the queue worker:

```bash
./vendor/bin/sail artisan queue:work
```

### Run the scraper:

```bash
./vendor/bin/sail artisan app:get-data-from-external-website
```

This will dispatch queued jobs. Make sure the queue worker is running.

## Storage

```bash
./vendor/bin/sail artisan storage:link
```


## Frontend
`To run the frontend, you need to install Node.js dependencies and build the assets.`

```bash
./vendor/bin/sail npm install
```

Run the Vue front end

```bash
./vendor/bin/sail npm run dev
```

There are 2 main routes:

/products-vue - for Vue front end

/ for blade - for Laravel Blade front end

You will have access to both from nav bar


## Running Tests

```bash
./vendor/bin/sail artisan test --env=testing
```
