<?php

namespace App\Services\Product\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository
{
    /**
     * @return Collection
     */
    final public function index(): Collection
    {
        return Product::query()
            ->select('id', 'name', 'amount', 'discount', 'donate_price', 'created_at')
            ->get();
    }

    /**
     * @param int $productId
     * @return int|float
     */
    final public function getAmount(int $productId): int|float
    {
        return Product::query()
            ->where('id', '=', $productId)
            ->value('amount');
    }

    /**
     * @param string $nameProduct
     * @return Product|null
     */
    final public function getProduct(string $nameProduct): ?Product
    {
        $product = Product::query()
            ->where('name', '=', $nameProduct)
            ->select('id', 'name', 'amount')
            ->first();

        if ($product instanceof Product) {
            return $product;
        }

        return null;
    }

    /**
     * @return Collection
     */
    final public function getDonateProduct(): Collection
    {
        return Product::query()
            ->where('is_show', '=', true)
            ->where('donate_price', '>', 0)
            ->get();
    }
}
