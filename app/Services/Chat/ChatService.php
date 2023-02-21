<?php

namespace App\Services\Chat;

use App\Models\Game;
use App\Models\User;
use App\Services\Chat\DTOs\SendMessageDto;
use App\Services\Chat\Enum\ExceptionEnum;
use App\Services\Chat\Repositories\ChatRepository;
use App\Services\ChatPartipicant\Repositories\ChatPartipicantRepository;
use App\Services\Game\Repositories\GameRepository;
use App\Services\Message\Repositories\MessageRepositories;
use App\Support\Exceptions\ApiException;
use App\Support\GameHelper;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;

readonly class ChatService
{
    /**
     * @param ChatRepository $chatRepository
     * @param GameRepository $gameRepository
     * @param ChatPartipicantRepository $chatPartipicantRepository
     * @param MessageRepositories $messageRepositories
     * @param User $user
     */
    public function __construct(
        private ChatRepository $chatRepository,
        private GameRepository $gameRepository,
        private ChatPartipicantRepository $chatPartipicantRepository,
        private MessageRepositories $messageRepositories,
        private Authenticatable $user,
    ) {
    }

    /**
     * @param int $gameId
     * @return int
     * @throws ApiException
     */
    final public function chatCreate(int $gameId): int
    {
        $userId = $this->user->id;
        $game = $this->gameRepository->findGameById($gameId);

        if (!$game) {
            throw new ApiException(ExceptionEnum::NO_ACTIVE_GAME->value);
        }

        $idSelectedUser = $this->getSelectedUserId($game, $userId);

        if (!$idSelectedUser) {
            throw new ApiException(ExceptionEnum::ID_DIDNT_MATCH->value);
        }

        return DB::transaction(function () use ($userId, $idSelectedUser) {
            $chatId = $this->chatRepository->chatCreate()->id;
            $this->chatPartipicantRepository->chatPartipicantCreate($chatId, $userId);
            $this->chatPartipicantRepository->chatPartipicantCreate($chatId, $idSelectedUser);

            return $chatId;
        });
    }

    /**
     * @param SendMessageDto $sendMessageDto
     * @return void
     * @throws ApiException
     */
    final public function sendMessage(SendMessageDto $sendMessageDto): void
    {
        $userId = $this->user->id;
        $isExistChatPartipicant = $this->chatPartipicantRepository->existChatPartipicant($userId, $sendMessageDto);

        if (!$isExistChatPartipicant) {
            throw new ApiException(ExceptionEnum::NO_ACTIVE_CHAT->value);
        }

        $this->messageRepositories->createNewMessage($sendMessageDto, $userId);
    }

    /**
     * @param Game $game
     * @param int $userId
     * @return int|null
     * @noinspection PhpIllegalStringOffsetInspection
     */
    private function getSelectedUserId(Game $game, int $userId): ?int
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
