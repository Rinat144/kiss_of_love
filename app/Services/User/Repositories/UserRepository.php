<?php

namespace App\Services\User\Repositories;

use App\Models\User;

class UserRepository
{
    /**
     * @param int $userId
     * @param int $sum
     * @return int|null
     */
    final public function getValueBalance(int $userId, int $sum): int|null
    {
        return User::query()
            ->where('id', '=', $userId)
            ->where('balance', '>=', $sum)
            ->value('balance');
    }

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
