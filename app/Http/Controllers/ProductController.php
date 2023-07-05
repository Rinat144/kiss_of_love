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
     * @return array
     */
    final public function index(): array
    {
        $allProducts = $this->productService->index();

        return [
            "code" => 200,
            "message" => "ok",
            "data" => ProductIndexResource::collection($allProducts)
        ];
    }

    /**
     * @return array
     */
    final public function getDonateProducts(): array
    {
        $donateProducts = $this->productService->getDonateProducts();

        return [
            "code" => 200,
            "message" => "ok",
            "data" => ProductDonateResource::collection($donateProducts)
        ];
    }

    /**
     * @return array
     */
    final public function getPurchasedProducts(): array
    {
        $purchasedProducts = $this->productService->getPurchasedProducts();

        return [
            "code" => 200,
            "message" => "ok",
            "data" => ProductsPurchasedResource::collection($purchasedProducts)
        ];
    }
}
