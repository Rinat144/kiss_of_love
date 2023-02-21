<?php

namespace App\Http\Controllers;

use App\Services\Chat\ChatService;
use App\Services\Chat\Exception\NotFoundChatException;
use App\Services\Chat\Requests\ChatCreateRequest;
use App\Services\Chat\Requests\SendMessageRequest;
use Illuminate\Http\JsonResponse;

class ChatController extends Controller
{
    public function __construct(
        private readonly ChatService $chatService,
    )
    {
    }

    /**
     * @param ChatCreateRequest $chatCreateRequest
     * @return JsonResponse
     * @throws NotFoundChatException
     */
    final public function chatCreate(ChatCreateRequest $chatCreateRequest): JsonResponse
    {
       $chatInfo = $this->chatService->chatCreate($chatCreateRequest['game_id']);

       return response()->json([
          'status' => $chatInfo
       ]);
    }

    /**
     * @param SendMessageRequest $sendMessageRequest
     * @return JsonResponse
     * @throws NotFoundChatException
     */
    final public function sendMessage(SendMessageRequest $sendMessageRequest): JsonResponse
    {
       $infoSendMessage = $this->chatService->sendMessage($sendMessageRequest->getDto());

       return response()->json([
          'status' => $infoSendMessage
       ]);
    }
}
