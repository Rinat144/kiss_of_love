<?php

namespace App\Http\Controllers;

use App\Services\Product\ProductService;
use App\Services\Product\Resources\ProductDonateResource;
use App\Services\Product\Resources\ProductIndexResource;
use App\Services\Product\Resources\ProductsPurchasedResource;
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

    /**
     * @return AnonymousResourceCollection
     */
    final public function getDonateProducts(): AnonymousResourceCollection
    {
        $donateProducts = $this->productService->getDonateProducts();

        return ProductDonateResource::collection($donateProducts);
    }

    /**
     * @return AnonymousResourceCollection
     */
    final public function getPurchasedProducts(): AnonymousResourceCollection
    {
        $purchasedProducts = $this->productService->getPurchasedProducts();

        return ProductsPurchasedResource::collection($purchasedProducts);
    }
}
