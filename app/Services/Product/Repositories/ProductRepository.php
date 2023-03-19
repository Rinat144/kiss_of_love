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
    final public function getProduct(int $productId): int|float
    {
        return Product::query()
            ->where('id', '=', $productId)
            ->value('amount');
    }
}
