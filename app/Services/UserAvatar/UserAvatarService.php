<?php

namespace App\Services\UserAvatar;

use App\Http\Controllers\Controller;
use App\Services\UserAvatar\DTOs\UserAvatarDto;
use App\Services\UserAvatar\Repositories\UserAvatarRepository;
use App\Support\StorageDiskEnum;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserAvatarService extends Controller
{
    /**
     * @param UserAvatarRepository $userAvatarRepository
     */
    public function __construct(
        public UserAvatarRepository $userAvatarRepository,
    ) {
    }

    /**
     * @param UserAvatarDto $userAvatarDto
     * @return void
     */
    final public function store(UserAvatarDto $userAvatarDto): void
    {
        $userId = Auth::id();
        $infoFilePath = Storage::disk(StorageDiskEnum::PATH_AVATAR->value)->put($userId, $userAvatarDto->image);

        $this->userAvatarRepository->storeFilePath($userId, $infoFilePath);
    }

    /**
     * @param int $id
     * @return void
     */
    final public function destroy(int $id): void
    {
        $userId = Auth::id();
        $this->userAvatarRepository->destroyUserAvatar($id, $userId);
    }

    /**
     * @param int $userId
     * @return Collection
     */
    final public function index(int $userId): Collection
    {
        return $this->userAvatarRepository->showAll($userId);
    }
}
