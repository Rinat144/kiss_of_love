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
    final public function updateOutlayUserBalance(int $userId, int $newBalance): void
    {
        User::query()
            ->where('id', '=', $userId)
            ->update(['balance' => $newBalance]);
    }

    /**
     * @param int $userId
     * @return int
     */
    final public function getValueBalance(int $userId): int
    {
       return User::query()
            ->where('id', '=', $userId)
            ->value('balance');
    }
}
