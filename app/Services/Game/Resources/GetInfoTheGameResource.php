<?php

namespace App\Services\Game\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

/**
 * @property mixed $id
 * @property mixed $first_user_id
 * @property mixed $second_user_id
 * @property mixed $third_user_id
 * @property mixed $fourth_user_id
 * @property mixed $fifth_user_id
 * @property mixed $sixth_user_id
 * @property mixed $first_user_info
 * @property mixed $second_user_info
 * @property mixed $third_user_info
 * @property mixed $fourth_user_info
 * @property mixed $fifth_user_info
 * @property mixed $sixth_user_info
 * @property mixed $created_at
 */
class GetInfoTheGameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|JsonSerializable
     */
    final public function toArray($request): array|JsonSerializable|Arrayable
    {
        return [
            "code" => 200,
            "message" => "ok",
            "data" => [
                'id' => $this->id,
                'first_user_id' => $this->first_user_id,
                'second_user_id' => $this->second_user_id,
                'third_user_id' => $this->third_user_id,
                'fourth_user_id' => $this->fourth_user_id,
                'fifth_user_id' => $this->fifth_user_id,
                'sixth_user_id' => $this->sixth_user_id,
                'first_user_info' => $this->first_user_info,
                'second_user_info' => $this->second_user_info,
                'third_user_info' => $this->third_user_info,
                'fourth_user_info' => $this->fourth_user_info,
                'fifth_user_info' => $this->fifth_user_info,
                'sixth_user_info' => $this->sixth_user_info,
                'created_at' => $this->created_at,
            ],
        ];
    }
}
