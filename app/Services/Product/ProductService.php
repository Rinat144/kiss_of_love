<?php

namespace App\Services\Product;

use App\Services\Payment\Repositories\PaymentRepository;
use App\Services\Product\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductService
{
    /**
     * @param ProductRepository $productRepository
     * @param PaymentRepository $paymentRepository
     */
    public function __construct(
        public ProductRepository $productRepository,
        public PaymentRepository $paymentRepository,
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
    final public function getDonateProducts(): Collection
    {
        return $this->productRepository->getDonateProducts();
    }

    /**
     * @return Collection
     */
    final public function getPurchasedProducts(): Collection
    {
        $authUserId = Auth::id();

        return DB::transaction(function () use ($authUserId) {
            $builder = $this->paymentRepository->getBuilderPurchasedProducts($authUserId);
            $products = $this->productRepository->getPurchasedProducts($builder);
            $this->paymentRepository->updateStatusPurchasedProducts($builder);

            return $products;
        });
    }
}
