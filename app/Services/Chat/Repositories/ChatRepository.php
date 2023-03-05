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
            ->where('id', '=', $chatId)
            ->with([
                'chatParticipants' => function ($q) use ($userId) {
                    $q->where('user_id', '=', $userId);
                }
            ])
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
