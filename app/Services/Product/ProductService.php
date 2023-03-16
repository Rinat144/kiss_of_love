<?php

namespace App\Services\Product;

use App\Services\Product\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    private const FULL_PERCENTAGE_AMOUNT = 100;

    /**
     * @param ProductRepository $productRepository
     */
    public function __construct(
        public ProductRepository $productRepository,
    ) {
    }

    /**
     * @return Collection
     */
    final public function index(): Collection
    {
        return $this->productRepository->index();
    }

    /**
     * @param int $productId
     * @return float|int
     */
    final public function getInfoProduct(int $productId): float|int
    {
        $infoProduct = $this->productRepository->getInfoProduct($productId);
        $discountPercentage = ($infoProduct->amount / self::FULL_PERCENTAGE_AMOUNT) * $infoProduct->discount;

        return ($infoProduct->amount - $discountPercentage);
    }
}
