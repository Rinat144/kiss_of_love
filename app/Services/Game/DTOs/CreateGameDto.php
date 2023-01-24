<?php

namespace App\Services\Game\DTOs;

class CreateGameDto
{
    /**
     * @param string $question
     */
    public function __construct(
        public string $question,
    )
    {
    }
}
