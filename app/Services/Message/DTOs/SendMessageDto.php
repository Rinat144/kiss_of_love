<?php

namespace App\Services\Message\DTOs;

class SendMessageDto
{
    /**
     * @param int $chatId
     * @param string $message
     */
    public function __construct(
        public int $chatId,
        public string $message,
    )
    {
    }
}
