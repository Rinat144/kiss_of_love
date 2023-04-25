<?php

namespace App\Services\User\Repositories;

use App\Models\User;

class UserRepository
{
    /**
     * @param int $userId
     * @param int $newBalance
     * @return void
     */
    final public function updateUserBalance(int $userId, int $newBalance): void
    {
        User::query()
            ->where('id', '=', $userId)
            ->update(['balance' => $newBalance]);
    }
}
