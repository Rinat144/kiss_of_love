<?php

namespace App\Services\Chat\Repositories;

use App\Models\Chat;

class ChatRepository
{
    /**
     * @return Chat
     */
    final public function chatCreate(): Chat
    {
        $chat = new Chat();
        $chat->save();

        return $chat;
    }

    /**
     * @param int $chatId
     * @param int $userId
     * @return bool
     */
    final public function chatExists(int $chatId, int $userId): bool
    {
        return Chat::query()
            ->join('chat_participants', 'chat_participants.chat_id', '=', 'chats.id')
            ->where('chats.id', '=', $chatId)
            ->where('chat_participants.user_id', '=', $userId)
            ->exists();
    }

    /**
     * @param int $chatId
     * @return void
     */
    final public function destroy(int $chatId): void
    {
        Chat::query()
            ->where('id', '=', $chatId)
            ->delete();
    }
}
