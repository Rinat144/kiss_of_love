<?php

namespace App\Services\UserAvatar\Resources;

use App\Support\StorageDiskEnum;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

/**
 * @property mixed $id
 * @property mixed $image
 * @property mixed $created_at
 */
class UserAvatarAllResource extends JsonResource
{
    private const PATH_AVATAR = 'storage/';

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|JsonSerializable
     */
    final public function toArray($request): array|JsonSerializable|Arrayable
    {
        return [
            'id' => $this->id,
            'image' => asset(self::PATH_AVATAR . StorageDiskEnum::PATH_AVATAR->value . '/' . $this->image),
            'created_at' => $this->created_at,
        ];
    }
}
