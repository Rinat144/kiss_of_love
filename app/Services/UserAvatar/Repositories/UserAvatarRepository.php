<?php

namespace App\Services\UserAvatar\Repositories;

use App\Models\UserAvatar;
use Illuminate\Database\Eloquent\Collection;

class UserAvatarRepository
{
    /**
     * @param int $userId
     * @param string $filePath
     * @return void
     */
    final public function storeFilePath(int $userId, string $filePath): void
    {
        $userAvatar = new UserAvatar();

        $userAvatar->user_id = $userId;
        $userAvatar->image = $filePath;

        $userAvatar->save();
    }

    /**
     * @param int $id
     * @param int $userId
     * @return void
     */
    final public function destroyUserAvatar(int $id, int $userId): void
    {
        UserAvatar::query()
            ->where('user_id', '=', $userId)
            ->where('id', '=', $id)
            ->delete();
    }

    /**
     * @param int $userId
     * @return Collection
     */
    final public function showAll(int $userId): Collection
    {
        return UserAvatar::query()
            ->where('user_id', '=', $userId)
            ->select('id', 'image', 'created_at')
            ->latest()
            ->get();
    }
}
