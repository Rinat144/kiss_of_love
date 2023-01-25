<?php

namespace App\Services\City\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $id
 * @property mixed $city
 */
class CityResource extends JsonResource
{
    /**
     * @param $request
     * @return array
     */
    final public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'city' => $this->city,
        ];
    }
}
