<?php

namespace App\Services\Chat;

use App\Models\Game;
use App\Services\Chat\DTOs\StoreBuyChatDto;
use App\Services\Chat\Enum\ExceptionEnum;
use App\Services\Chat\Exceptions\ChatApiException;
use App\Services\Chat\Repositories\ChatRepository;
use App\Services\ChatParticipant\Repositories\ChatParticipantRepository;
use App\Services\Game\Repositories\GameRepository;
use App\Services\Message\Repositories\MessageRepositories;
use App\Services\Product\Repositories\ProductRepository;
use App\Services\Transaction\Repositories\TransactionRepository;
use App\Services\User\Exceptions\UserApiException;
use App\Services\User\UserService;
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
     * @param TransactionRepository $transactionRepository
     * @param UserService $userService
     * @param ProductRepository $productRepository
     */
    public function __construct(
        private ChatRepository $chatRepository,
        private GameRepository $gameRepository,
        private ChatParticipantRepository $chatParticipantRepository,
        private MessageRepositories $messageRepositories,
        private TransactionRepository $transactionRepository,
        private UserService $userService,
        private ProductRepository $productRepository,
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
     * @param StoreBuyChatDto $dto
     * @return int
     * @throws UserApiException
     * @throws ChatApiException
     */
    final public function storeBuyChat(StoreBuyChatDto $dto): int
    {
        $userId = Auth::id();

        $amountProduct = $this->productRepository->getAmount(config('product.buy_chat'));
        $userBalance = $this->userService->getCheckBalance($amountProduct);
        $this->checkIdParticipationGame($dto, $userId);

        return DB::transaction(function () use ($dto, $userId, $userBalance, $amountProduct) {
            $this->userService->deductUserBalance($userId, $userBalance, $amountProduct);
            $this->transactionRepository->storeOutlayTransaction(
                $userId,
                config('product.buy_chat'),
                $userBalance,
                $amountProduct
            );
            $chatId = $this->chatRepository->chatCreate()->id;
            $this->chatParticipantRepository->chatParticipantCreate($chatId, $userId);
            $this->chatParticipantRepository->chatParticipantCreate($chatId, $dto->selectedUserId);

            return $chatId;
        });
    }

    /**
     * @param StoreBuyChatDto $dto
     * @param int $userId
     * @return void
     * @throws ChatApiException
     * @noinspection PhpIllegalStringOffsetInspection
     */
    private function checkIdParticipationGame(StoreBuyChatDto $dto, int $userId): void
    {
        $game = $this->gameRepository->findGameById($dto->gameId);

        if (!$game) {
            throw new ChatApiException(ExceptionEnum::NO_ACTIVE_GAME);
        }

        $infoFieldUser = GameHelper::getFieldInfoUser($game, $dto->selectedUserId);
        $idTheSelectedUser = $game->$infoFieldUser['select_user_id'];

        if ($userId !== $idTheSelectedUser) {
            throw new ChatApiException(ExceptionEnum::NO_ID_GAME_USER);
        }
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
