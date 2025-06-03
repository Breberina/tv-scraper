<?php

namespace App\Http\Controllers;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\Http\Request;


class ProductController
{
    protected ProductService $productService;
    protected CategoryService $categoryService;

    public function __construct(ProductService $productService, CategoryService $categoryService)
    {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        $categories = $this->categoryService->getAllCategories();
        $page = $request->get('page', 1);

        $products = $this->productService->getPaginatedProducts(20, $page);

        return view('products.index', compact('products', 'categories'));
    }
}
