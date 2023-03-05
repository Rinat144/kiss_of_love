<?php

namespace App\Http\Controllers;

use App\Services\UserAvatar\Requests\UserAvatarRequest;
use App\Services\UserAvatar\Resources\UserAvatarAllResource;
use App\Services\UserAvatar\UserAvatarService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserAvatarController extends Controller
{
    /**
     * @param UserAvatarService $userAvatarService
     */
    public function __construct(
        public UserAvatarService $userAvatarService,
    ) {
    }

    /**
     * @param UserAvatarRequest $userAvatarRequest
     * @return JsonResponse
     */
    final public function store(UserAvatarRequest $userAvatarRequest): JsonResponse
    {
        $this->userAvatarService->store($userAvatarRequest->getDto());

        return response()->json([
            'status' => true,
        ]);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    final public function destroy(int $id): JsonResponse
    {
        $this->userAvatarService->destroy($id);

        return response()->json([
            'status' => true,
        ]);
    }

    /**
     * @param int $userId
     * @return AnonymousResourceCollection
     */
    final public function showAll(int $userId): AnonymousResourceCollection
    {
        $dataUserAvatar = $this->userAvatarService->showAll($userId);

        return UserAvatarAllResource::collection($dataUserAvatar);
    }
}
