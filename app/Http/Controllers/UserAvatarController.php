<?php

namespace App\Http\Controllers;

use App\Services\UserAvatar\Requests\UserAvatarRequest;
use App\Services\UserAvatar\Resources\UserAvatarAllResource;
use App\Services\UserAvatar\UserAvatarService;
use App\Support\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserAvatarController extends Controller
{
    use ApiResponseTrait;

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

        return self::statusResponse(true);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    final public function destroy(int $id): JsonResponse
    {
        $this->userAvatarService->destroy($id);

        return self::statusResponse(true);
    }

    /**
     * @param int $userId
     * @return array
     */
    final public function index(int $userId): array
    {
        $dataUserAvatar = $this->userAvatarService->index($userId);

        return [
            "code" => 200,
            "message" => "ok",
            "data" => UserAvatarAllResource::collection($dataUserAvatar)
        ];
    }
}
