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
     * @return Model
     */
    final public function getInfoProduct(int $productId): Model
    {
        return Product::query()
            ->where('id', '=', $productId)
            ->first();
    }
}
