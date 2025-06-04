<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request): JsonResponse
    {
        $page = $request->get('page', 1);
        $products = $this->productService->getPaginatedProducts(20, $page);

        return response()->json($products);
    }

    public function category(Request $request, int $categoryId): JsonResponse
    {
        $page = $request->get('page', 1);
        $products = $this->productService->getPaginatedProductsByCategory($categoryId, 20, $page);

        return response()->json($products);
    }

}
