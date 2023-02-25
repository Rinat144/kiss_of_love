<?php

namespace App\Http\Controllers;

use App\Services\Chat\ChatService;
use App\Services\Chat\Exceptions\ChatApiException;
use App\Services\Chat\Requests\ChatCreateRequest;
use App\Services\Chat\Requests\GetSpecificChatRequest;
use App\Services\Chat\Requests\SendMessageRequest;
use App\Services\Chat\Resources\ChatSpecificResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ChatController extends Controller
{
    public function __construct(
        private readonly ChatService $chatService,
    ) {
    }

    /**
     * @param ChatCreateRequest $chatCreateRequest
     * @return JsonResponse
     * @throws ChatApiException
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
     * @throws ChatApiException
     */
    final public function sendMessage(SendMessageRequest $sendMessageRequest): JsonResponse
    {
        $this->chatService->sendMessage($sendMessageRequest->getDto());

        return response()->json([
            'status' => true
        ]);
    }

    /**
     * @param GetSpecificChatRequest $specificChatRequest
     * @return AnonymousResourceCollection
     * @throws ChatApiException
     */
    final public function getSpecificChat(GetSpecificChatRequest $specificChatRequest): AnonymousResourceCollection
    {
       $chat = $this->chatService->getSpecificChat($specificChatRequest['chat_id']);

       return ChatSpecificResource::collection($chat);
    }

    /**
     * @return JsonResponse
     */
    final public function getAllChats(): JsonResponse
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
    final public function deleteChat(int $chatId): JsonResponse
    {
        $this->chatService->deleteChat($chatId);

        return response()->json([
            'status' => true
        ]);
    }
}
