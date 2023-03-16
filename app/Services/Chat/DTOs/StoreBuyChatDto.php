<?php

namespace App\Services\Chat\DTOs;

class StoreBuyChatDto
{
    /**
     * @param int $selected_user_id
     * @param int $product_id
     */
    public function __construct(
        public int $selected_user_id,
        public int $product_id,
    ) {
    }
}
