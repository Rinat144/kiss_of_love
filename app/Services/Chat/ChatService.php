<?php

namespace App\Services\Chat;

use App\Models\Game;
use App\Services\Chat\Enum\ExceptionEnum;
use App\Services\Chat\Exceptions\ChatApiException;
use App\Services\Chat\Repositories\ChatRepository;
use App\Services\ChatParticipant\Repositories\ChatParticipantRepository;
use App\Services\Game\Repositories\GameRepository;
use App\Services\Message\Repositories\MessageRepositories;
use App\Support\GameHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

readonly class ChatService
{
    /**
     * @param ChatRepository $chatRepository
     * @param GameRepository $gameRepository
     * @param ChatParticipantRepository $chatParticipantRepository
     * @param MessageRepositories $messageRepositories
     */
    public function __construct(
        private ChatRepository $chatRepository,
        private GameRepository $gameRepository,
        private ChatParticipantRepository $chatParticipantRepository,
        private MessageRepositories $messageRepositories,
    ) {
    }

    /**
     * @param int $gameId
     * @return int
     * @throws ChatApiException
     */
    final public function chatCreate(int $gameId): int
    {
        $userId = Auth::id();
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
     * @param int $chatId
     * @return Collection
     * @throws ChatApiException
     */
    final public function getSpecificChat(int $chatId): Collection
    {
        $userid = Auth::id();
        $chatExist = $this->chatRepository->chatExists($chatId, $userid);

        if (!$chatExist) {
            throw new ChatApiException(ExceptionEnum::NO_ACTIVE_CHAT);
        }

        $allMessages = $this->messageRepositories->searchMessagesByChatId($chatId);

        $idOfAnotherParticipant = $allMessages->where('user_id', '!=', $userid)->value('user_id');
        $this->messageRepositories->changeMessageStatus($chatId, $idOfAnotherParticipant);

        return $allMessages->sortByDesc('created_at');
    }

    /**
     * @param int $chatId
     * @return void
     */
    final public function destroy(int $chatId): void
    {
        $this->chatRepository->destroy($chatId);
    }

    /**
     * @return array
     */
    final public function getAllChats(): array
    {
        $userId = Auth::id();
        $allIdChats = $this->chatParticipantRepository->gatAllIdChats($userId);

        return $this->messageRepositories->searchAllMessage($allIdChats, $userId)->toArray();
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
