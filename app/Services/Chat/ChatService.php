<?php

namespace App\Services\Chat;

use App\Models\Game;
use App\Models\User;
use App\Services\Chat\DTOs\SendMessageDto;
use App\Services\Chat\Enum\ExceptionEnum;
use App\Services\Chat\Repositories\ChatRepository;
use App\Services\ChatParticipant\Repositories\ChatParticipantRepository;
use App\Services\Game\Repositories\GameRepository;
use App\Services\Message\Repositories\MessageRepositories;
use App\Services\Chat\Exceptions\ChatApiException;
use App\Support\GameHelper;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

readonly class ChatService
{
    /**
     * @param ChatRepository $chatRepository
     * @param GameRepository $gameRepository
     * @param ChatParticipantRepository $chatParticipantRepository
     * @param MessageRepositories $messageRepositories
     * @param User $user
     */
    public function __construct(
        private ChatRepository $chatRepository,
        private GameRepository $gameRepository,
        private ChatParticipantRepository $chatParticipantRepository,
        private MessageRepositories $messageRepositories,
        private Authenticatable $user,
    ) {
    }

    /**
     * @param int $gameId
     * @return int
     * @throws ChatApiException
     */
    final public function chatCreate(int $gameId): int
    {
        $userId = $this->user->id;
        $game = $this->gameRepository->findGameById($gameId);

        if (!$game) {
            throw new ChatApiException(ExceptionEnum::NO_ACTIVE_GAME);
        }

        $idSelectedUser = $this->getSelectedUserId($game, $userId);

        if (!$idSelectedUser) {
            throw new ChatApiException(ExceptionEnum::ID_DIDNT_MATCH);
        }

        return DB::transaction(function () use ($userId, $idSelectedUser) {
            $chatId = $this->chatRepository->chatCreate()->id;
            $this->chatParticipantRepository->chatParticipantCreate($chatId, $userId);
            $this->chatParticipantRepository->chatParticipantCreate($chatId, $idSelectedUser);

            return $chatId;
        });
    }

    /**
     * @param SendMessageDto $sendMessageDto
     * @return void
     * @throws ChatApiException
     */
    final public function sendMessage(SendMessageDto $sendMessageDto): void
    {
        $userId = $this->user->id;
        $isExistChatParticipant = $this->chatParticipantRepository->existChatParticipant($userId, $sendMessageDto);

        if (!$isExistChatParticipant) {
            throw new ChatApiException(ExceptionEnum::NO_ACTIVE_CHAT);
        }

        $this->messageRepositories->createNewMessage($sendMessageDto, $userId);
    }

    /**
     * @param int $chatId
     * @return Collection
     * @throws ChatApiException
     */
    final public function getSpecificChat(int $chatId): Collection
    {
        $chatWithChatParticipant = $this->chatRepository->searchChat($chatId);

        if (!$chatWithChatParticipant) {
            throw new ChatApiException(ExceptionEnum::NO_ACTIVE_CHAT);
        }

        $allMessage = $this->messageRepositories->searchMessage($chatId);

        $userid = $this->user->id;
        $idOfAnotherParticipant = $allMessage->where('user_id', '!=', $userid)->value('user_id');
        $this->messageRepositories->changeMessageStatus($chatId, $idOfAnotherParticipant);

        return $allMessage->sortByDesc('updated_at');
    }

    /**
     * @param int $chatId
     * @return void
     */
    final public function deleteChat(int $chatId): void
    {
        $this->chatRepository->deleteChat($chatId);
    }

    /**
     * @return array
     */
    final public function getAllChats(): array
    {
        $userId = $this->user->id;

        $allIdChats = $this->chatParticipantRepository->gatAllIdChats($userId);

        $allChats = $this->messageRepositories->searchAllMessage($allIdChats, $userId);

        return $this->getGroupedChats($allChats);
    }

    /**
     * @param Collection $allChats
     * @return array
     */
    private function getGroupedChats(Collection $allChats): array
    {
        $idChatsToPars = $allChats->pluck('chat_id')->flip();

        $groupedChats = [];

        foreach ($allChats as $value) {
            if ($value['is_read'] === false) {
                $groupedChats[] = [
                    'chat_id' => $value['chat_id'],
                    'user_id' => $value['user_id'],
                    'count_unread_message' => $value['count_messages'],
                ];
                unset($idChatsToPars[$value['chat_id']]);
            }
        }

        foreach ($allChats as $value) {
            if (isset($idChatsToPars[$value['chat_id']])) {
                $groupedChats[] = [
                    'chat_id' => $value['chat_id'],
                    'user_id' => $value['user_id'],
                    'count_unread_message' => 0,
                ];
            }
        }

        return $groupedChats;
    }

    /**
     * @param Game $game
     * @param int $userId
     * @return int|false
     * @noinspection PhpIllegalStringOffsetInspection
     */
    private function getSelectedUserId(Game $game, int $userId): int|false
    {
        $infoFieldAuthUser = GameHelper::getFieldInfoUser($game, $userId);
        $idTheSelectedAuthUser = $game->$infoFieldAuthUser['select_user_id'];

        $infoFieldUser = GameHelper::getFieldInfoUser($game, $idTheSelectedAuthUser);
        $idTheSelectedUser = $game->$infoFieldUser['select_user_id'];

        if ($userId === $idTheSelectedUser) {
            return $idTheSelectedAuthUser;
        }

        return false;
    }
}
