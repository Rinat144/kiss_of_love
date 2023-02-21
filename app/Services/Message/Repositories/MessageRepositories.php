<?php

namespace App\Services\Message\Repositories;

use App\Models\Message;
use App\Services\Chat\DTOs\SendMessageDto;

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
}
