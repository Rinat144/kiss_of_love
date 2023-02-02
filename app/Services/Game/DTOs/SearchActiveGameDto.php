<?php

namespace App\Services\Game\DTOs;

class SearchActiveGameDto
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
