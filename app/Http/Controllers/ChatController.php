<?php

namespace App\Http\Controllers;

use App\Services\Chat\ChatService;
use App\Services\Chat\Exceptions\ChatApiException;
use App\Services\Chat\Requests\ChatCreateRequest;
use App\Services\Chat\Requests\StoreBuyChatRequest;
use App\Services\Chat\Resources\ChatSpecificResource;
use App\Services\User\Exceptions\UserApiException;
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
            "code" => 200,
            "message" => "ok",
            "data" => [
                'chat_id' => $chatId
            ],
        ]);
    }


    /**
     * @param int $chatId
     * @return array
     * @throws ChatApiException
     */
    final public function getSpecificChat(int $chatId): array
    {
        $chat = $this->chatService->getSpecificChat($chatId);

        return [
            "code" => 200,
            "message" => "ok",
            "data" => ChatSpecificResource::collection($chat)
        ];
    }

    /**
     * @return JsonResponse
     */
    final public function index(): JsonResponse
    {
        $chats = $this->chatService->getAllChats();

        return response()->json([
            "code" => 200,
            "message" => "ok",
            "data" => [
                $chats
            ],
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

    /**
     * @param StoreBuyChatRequest $storeBuyChatRequest
     * @return JsonResponse
     * @throws UserApiException
     * @throws ChatApiException
     */
    final public function storeBuyChat(StoreBuyChatRequest $storeBuyChatRequest): JsonResponse
    {
        $chatId = $this->chatService->storeBuyChat($storeBuyChatRequest->getDto());

        return self::dataResponse(['chat_id' => $chatId]);
    }
}
