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
 */
class ProductIndexResource extends JsonResource
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
            'donate_price' => $this->donate_price,
            'created_at' => $this->created_at,
        ];
    }
}
