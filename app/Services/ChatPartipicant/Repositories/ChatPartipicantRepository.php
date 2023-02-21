<?php

namespace App\Services\ChatPartipicant\Repositories;

use App\Models\ChatPartipicant;
use App\Services\Chat\DTOs\SendMessageDto;

class ChatPartipicantRepository
{
    /**
     * @param int $chatId
     * @param int $userId
     * @return void
     */
    final public function chatPartipicantCreate(int $chatId, int $userId): void
    {
        $chatPartipicant = new ChatPartipicant();

        $chatPartipicant->chat_id = $chatId;
        $chatPartipicant->user_id = $userId;

        $chatPartipicant->save();
    }

    /**
     * @param int $userId
     * @param SendMessageDto $sendMessageDto
     * @return bool
     */
    final public function existChatPartipicant(int $userId, SendMessageDto $sendMessageDto): bool
    {
        return ChatPartipicant::query()
            ->where('chat_id', '=', $sendMessageDto->chatId)
            ->where('user_id', '=', $userId)
            ->exists();
    }
}
