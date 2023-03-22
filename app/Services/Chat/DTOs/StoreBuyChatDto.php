<?php

namespace App\Services\Chat\DTOs;

class StoreBuyChatDto
{
    /**
     * @param int $selectedUserId
     * @param int $gameId
     */
    public function __construct(
        public int $selectedUserId,
        public int $gameId,
    ) {
    }
}
