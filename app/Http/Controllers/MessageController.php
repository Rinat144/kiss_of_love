<?php

namespace App\Http\Controllers;

use App\Services\Message\Exceptions\MessageApiException;
use App\Services\Message\MessageService;
use App\Services\Message\Requests\SendMessageRequest;
use Illuminate\Http\JsonResponse;

class MessageController extends Controller
{
    public function __construct(
        public MessageService $messageService,
    ) {
    }

    /**
     * @param SendMessageRequest $sendMessageRequest
     * @return JsonResponse
     * @throws MessageApiException
     */
    final public function sendMessage(SendMessageRequest $sendMessageRequest): JsonResponse
    {
        $this->messageService->sendMessage($sendMessageRequest->getDto());

        return response()->json([
            'status' => true
        ]);
    }
}
