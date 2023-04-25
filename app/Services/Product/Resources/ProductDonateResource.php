<?php

namespace App\Services\Product\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $name
 * @property mixed $amount
 * @property mixed $discount
 * @property mixed $donate_price
 * @property mixed $id
 * @property mixed $created_at
 * @property mixed $is_active
 * @property mixed $app_store_product_id
 * @property mixed $google_play_product_id
 * @property mixed $is_show
 */
class ProductDonateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    final public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'amount' => $this->amount,
            'discount' => $this->discount,
            'is_active' => $this->is_active,
            'app_store_product_id' => $this->app_store_product_id,
            'google_play_product_id' => $this->google_play_product_id,
            'is_show' => $this->is_show,
            'donate_price' => $this->donate_price,
            'created_at' => $this->created_at,
        ];
    }
}
