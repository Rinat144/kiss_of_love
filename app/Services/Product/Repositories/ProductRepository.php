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
}
