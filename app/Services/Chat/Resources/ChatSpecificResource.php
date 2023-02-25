<?php

namespace App\Services\Chat\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

/**
 * @property mixed $id
 * @property mixed $chat_id
 * @property mixed $user_id
 * @property mixed $message
 * @property mixed $is_read
 * @property mixed $updated_at
 */
class ChatSpecificResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|JsonSerializable
     */
   final public function toArray($request): array|JsonSerializable|Arrayable
   {
        return [
            'id' => $this->id,
            'chat_id' => $this->chat_id,
            'user_id' => $this->user_id,
            'message' => $this->message,
            'is_read' => $this->is_read,
            'updated_at' => $this->updated_at
        ];
    }
}
