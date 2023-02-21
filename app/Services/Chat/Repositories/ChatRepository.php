<?php

namespace App\Services\Chat\Repositories;

use App\Models\Chat;
use App\Models\ChatPartipicant;
use App\Models\Game;
use App\Models\Message;
use App\Services\Chat\DTOs\SendMessageDto;
use Illuminate\Database\Eloquent\Model;

class ChatRepository
{
    /**
     * @param int $gameId
     * @return Model|null
     */
    final public function searchGame(int $gameId): ?Game
    {
        $game = Game::query()
            ->where('id', '=', $gameId)
            ->first();

        if ($game instanceof Game) {
            return $game;
        }

        return null;
    }

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
     * @return true
     */
    final public function chatPartipicantsCreate(int $chatId, int $userId): true
    {
        $chatPartipicants = new ChatPartipicant();

        $chatPartipicants->chat_id = $chatId;
        $chatPartipicants->user_id = $userId;

        $chatPartipicants->save();

        return true;
    }

    /**
     * @param int $userId
     * @param SendMessageDto $sendMessageDto
     * @return bool
     */
    final public function searchRecordInPartipicants(int $userId, SendMessageDto $sendMessageDto): bool
    {
        return ChatPartipicant::query()
            ->where('chat_id', '=', $sendMessageDto->chatId)
            ->where('user_id', '=', $userId)
            ->exists();
    }

    /**
     * @param SendMessageDto $sendMessageDto
     * @param int $userId
     * @return true
     */
    final public function createNewMessage(SendMessageDto $sendMessageDto, int $userId): true
    {
        $newMessage = new Message();

        $newMessage->chat_id = $sendMessageDto->chatId;
        $newMessage->user_id = $userId;
        $newMessage->message = $sendMessageDto->message;

        $newMessage->save();

        return true;
    }
}
