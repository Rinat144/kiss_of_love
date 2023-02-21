<?php

namespace App\Http\Controllers;

use App\Services\Chat\ChatService;
use App\Services\Chat\Requests\ChatCreateRequest;
use App\Services\Chat\Requests\SendMessageRequest;
use App\Support\Exceptions\ApiException;
use Illuminate\Http\JsonResponse;

class ChatController extends Controller
{
    public function __construct(
        private readonly ChatService $chatService,
    ) {
    }

    /**
     * @param ChatCreateRequest $chatCreateRequest
     * @return JsonResponse
     * @throws ApiException
     */
    final public function chatCreate(ChatCreateRequest $chatCreateRequest): JsonResponse
    {
        $chatId = $this->chatService->chatCreate($chatCreateRequest['game_id']);

        return response()->json([
            'chat_id' => $chatId
        ]);
    }

    /**
     * @param SendMessageRequest $sendMessageRequest
     * @return JsonResponse
     * @throws ApiException
     */
    final public function sendMessage(SendMessageRequest $sendMessageRequest): JsonResponse
    {
        $this->chatService->sendMessage($sendMessageRequest->getDto());

        return response()->json([
            'status' => true
        ]);
    }
}
