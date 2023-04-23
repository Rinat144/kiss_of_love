<?php

namespace App\Services\Product\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

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
     * @return Model
     */
    final public function getProduct(string $nameProduct): Model
    {
        return Product::query()
            ->where('name', '=', $nameProduct)
            ->select('id', 'name', 'amount')
            ->first();
    }
}
