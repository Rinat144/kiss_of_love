<?php

namespace App\Services\Chat;

use App\Models\Game;
use App\Models\User;
use App\Services\Chat\DTOs\SendMessageDto;
use App\Services\Chat\Enum\ExceptionEnum;
use App\Services\Chat\Exception\NotFoundChatException;
use App\Services\Chat\Repositories\ChatRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Auth\Authenticatable;

class ChatService
{
    /**
     * @param ChatRepository $chatRepository
     * @param User $user
     */
    public function __construct(
        private readonly ChatRepository $chatRepository,
        private Authenticatable $user,
    ) {
        $this->user = auth()->user();
    }

    /**
     * @param int $gameId
     * @return true
     * @throws NotFoundChatException
     */
    final public function chatCreate(int $gameId): true
    {
        $userId = $this->user->id;

        $game = $this->chatRepository->searchGame($gameId);

        if (!$game) {
            throw new NotFoundChatException(ExceptionEnum::NO_ACTIVE_GAME->value);
        }

        $idSelectedUser = $this->getCompatibilityInfo($game, $userId);

        if (!$idSelectedUser) {
            throw new NotFoundChatException(ExceptionEnum::ID_DIDNT_MATCH->value);
        }

        $chat = $this->chatRepository->chatCreate();

        $chatId = $chat->id;

        DB::transaction(function () use ($chatId, $userId, $idSelectedUser) {
            $this->chatRepository->chatPartipicantsCreate($chatId, $userId);
            $this->chatRepository->chatPartipicantsCreate($chatId, $idSelectedUser);
        });

        return true;
    }

    /**
     * @param SendMessageDto $sendMessageDto
     * @return true
     * @throws NotFoundChatException
     */
    final public function sendMessage(SendMessageDto $sendMessageDto): true
    {
        $userId = $this->user->id;

        $booleanValueOfTheRecord = $this->chatRepository->searchRecordInPartipicants($userId, $sendMessageDto);

        if (!$booleanValueOfTheRecord) {
            throw new NotFoundChatException(ExceptionEnum::NO_ACTIVE_CHAT->value);
        }

        return $this->chatRepository->createNewMessage($sendMessageDto, $userId);
    }

    /**
     * @param Game $game
     * @param int $userId
     * @return int|null
     * @noinspection PhpIllegalStringOffsetInspection
     */
    private function getCompatibilityInfo(Game $game, int $userId): ?int
    {
        $infoFieldAuthUser = $this->getFieldInfoUser($game, $userId);

        $idTheSelectedAuthUser = $game->$infoFieldAuthUser['select_user_id'];

        $infoFieldUser = $this->getFieldInfoUser($game, $idTheSelectedAuthUser);

        $idTheSelectedUser = $game->$infoFieldUser['select_user_id'];

        if ($userId === $idTheSelectedUser) {
            return $idTheSelectedAuthUser;
        }

        return false;
    }

    /**
     * @param Game $game
     * @param int $userId
     * @return string
     */
    private function getFieldInfoUser(Game $game, int $userId): string
    {
        return match ($userId) {
            $game->first_user_id => 'first_user_info',
            $game->second_user_id => 'second_user_info',
            $game->third_user_id => 'third_user_info',
            $game->fourth_user_id => 'fourth_user_info',
            $game->fifth_user_id => 'fifth_user_info',
            $game->sixth_user_id => 'sixth_user_info',
        };
    }
}
