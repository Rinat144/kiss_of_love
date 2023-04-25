<?php

namespace App\Services\Product;

use App\Services\Product\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
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
     * @return Collection
     */
    final public function getDonateProduct(): Collection
    {
        return $this->productRepository->getDonateProduct();
    }
}
