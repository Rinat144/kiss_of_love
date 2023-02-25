<?php

namespace App\Services\Message\Repositories;

use App\Models\Message;
use App\Services\Chat\DTOs\SendMessageDto;
use Illuminate\Database\Eloquent\Collection;

class MessageRepositories
{
    /**
     * @param SendMessageDto $sendMessageDto
     * @param int $userId
     * @return void
     */
    final public function createNewMessage(SendMessageDto $sendMessageDto, int $userId): void
    {
        $newMessage = new Message();

        $newMessage->chat_id = $sendMessageDto->chatId;
        $newMessage->user_id = $userId;
        $newMessage->message = $sendMessageDto->message;

        $newMessage->save();
    }

    /**
     * @param int $chatId
     * @return Collection
     */
    final public function searchMessage(int $chatId): Collection
    {
        return Message::query()
            ->where('chat_id', '=', $chatId)
            ->get();
    }

    /**
     * @param int $chatId
     * @param int $idTheSecondParticipant
     * @return void
     */
    final public function changeMessageStatus(int $chatId, int $idTheSecondParticipant): void
    {
        Message::query()
            ->where('chat_id', '=', $chatId)
            ->where('user_id', '=', $idTheSecondParticipant)
            ->update(['is_read' => true]);
    }

    /**
     * @param Collection $allIdChats
     * @param int $userId
     * @return Collection
     */
    final public function searchAllMessage(Collection $allIdChats, int $userId): Collection
    {
        return Message::query()
            ->select('user_id', 'chat_id', 'is_read')
            ->whereIn('chat_id', $allIdChats)
            ->where('user_id', '!=', $userId)
            ->selectRaw('count(is_read) as count_messages, is_read, chat_id, user_id')
            ->groupBy('is_read', 'chat_id', 'user_id')
            ->get();
    }
}
