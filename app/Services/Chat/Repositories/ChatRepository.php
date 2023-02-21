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
}
