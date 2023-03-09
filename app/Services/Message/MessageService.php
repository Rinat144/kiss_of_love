<?php

namespace App\Services\Message;

use App\Services\Message\Enum\ExceptionEnum;
use App\Services\ChatParticipant\Repositories\ChatParticipantRepository;
use App\Services\Message\DTOs\SendMessageDto;
use App\Services\Message\Exceptions\MessageApiException;
use App\Services\Message\Repositories\MessageRepositories;
use Illuminate\Support\Facades\Auth;

class MessageService
{
    /**
     * @param MessageRepositories $messageRepositories
     * @param ChatParticipantRepository $chatParticipantRepository
     */
    public function __construct(
        public MessageRepositories $messageRepositories,
        public ChatParticipantRepository $chatParticipantRepository,
    ) {
    }

    /**
     * @param SendMessageDto $sendMessageDto
     * @return void
     * @throws MessageApiException
     */
    final public function sendMessage(SendMessageDto $sendMessageDto): void
    {
        $userId = Auth::id();
        $isExistChatParticipant = $this->chatParticipantRepository->existChatParticipant($userId, $sendMessageDto);

        if (!$isExistChatParticipant) {
            throw new MessageApiException(ExceptionEnum::NO_ACTIVE_CHAT);
        }

        $this->messageRepositories->createNewMessage($sendMessageDto, $userId);
    }
}
