<?php

namespace App\Services\ChatParticipant\Repositories;

use App\Models\ChatParticipant;
use App\Services\Chat\DTOs\SendMessageDto;
use Illuminate\Database\Eloquent\Collection;

class ChatParticipantRepository
{
    /**
     * @param int $chatId
     * @param int $userId
     * @return void
     */
    final public function chatParticipantCreate(int $chatId, int $userId): void
    {
        $chatParticipant = new ChatParticipant();

        $chatParticipant->chat_id = $chatId;
        $chatParticipant->user_id = $userId;

        $chatParticipant->save();
    }

    /**
     * @param int $userId
     * @param SendMessageDto $sendMessageDto
     * @return bool
     */
    final public function existChatParticipant(int $userId, SendMessageDto $sendMessageDto): bool
    {
        return ChatParticipant::query()
            ->where('chat_id', '=', $sendMessageDto->chatId)
            ->where('user_id', '=', $userId)
            ->exists();
    }

    /**
     * @param int $userId
     * @return Collection
     */
    final public function gatAllIdChats(int $userId): Collection
    {
        return ChatParticipant::query()
            ->where('user_id', '=', $userId)
            ->get(['chat_id']);
    }
}
