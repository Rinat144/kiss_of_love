<?php

namespace App\Http\Controllers;

use App\Services\Product\ProductService;
use App\Services\Product\Resources\ProductIndexResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    /**
     * @param ProductService $productService
     */
    public function __construct(
        public ProductService $productService,
    ) {
    }

    /**
     * @return AnonymousResourceCollection
     */
    final public function index(): AnonymousResourceCollection
    {
        $allProducts = $this->productService->index();

        return ProductIndexResource::collection($allProducts);
    }
}
