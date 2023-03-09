<?php

namespace App\Http\Controllers;

use App\Services\Chat\ChatService;
use App\Services\Chat\Exceptions\ChatApiException;
use App\Services\Chat\Requests\ChatCreateRequest;
use App\Services\Chat\Resources\ChatSpecificResource;
use App\Support\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ChatController extends Controller
{
    use ApiResponseTrait;

    /**
     * @param ChatService $chatService
     */
    public function __construct(
        private readonly ChatService $chatService,
    ) {
    }

    /**
     * @param ChatCreateRequest $chatCreateRequest
     * @return JsonResponse
     * @throws ChatApiException
     */
    final public function store(ChatCreateRequest $chatCreateRequest): JsonResponse
    {
        $chatId = $this->chatService->chatCreate($chatCreateRequest['game_id']);

        return response()->json([
            'chat_id' => $chatId
        ]);
    }

    /**
     * @param int $chatId
     * @return AnonymousResourceCollection
     * @throws ChatApiException
     */
    final public function getSpecificChat(int $chatId): AnonymousResourceCollection
    {
        $chat = $this->chatService->getSpecificChat($chatId);

        return ChatSpecificResource::collection($chat);
    }

    /**
     * @return JsonResponse
     */
    final public function index(): JsonResponse
    {
        $chats = $this->chatService->getAllChats();

        return response()->json([
            $chats,
        ]);
    }

    /**
     * @param int $chatId
     * @return JsonResponse
     */
    final public function destroy(int $chatId): JsonResponse
    {
        $this->chatService->destroy($chatId);

        return self::statusResponse(true);
    }
}
