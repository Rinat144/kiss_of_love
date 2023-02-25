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
     * @return bool
     */
    final public function searchChat(int $chatId): bool
    {
       return Chat::query()
            ->where('id', '=', $chatId)
            ->with('chatPartipicants')
            ->exists();
    }

    /**
     * @param int $chatId
     * @return void
     */
    final public function deleteChat(int $chatId): void
    {
        Chat::query()
            ->where('id', '=', $chatId)
            ->delete();
    }
}
