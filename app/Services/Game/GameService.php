<?php

namespace App\Services\Game;

use App\Models\Game;
use App\Models\User;
use App\Services\Auth\Enum\GenderSelectionEnum;
use App\Services\Game\DTOs\AddAnswerTheQuestionsDto;
use App\Services\Game\DTOs\CreateGameDto;
use App\Services\Game\DTOs\SearchActiveGameDto;
use App\Services\Game\Enum\ExceptionEnum;
use App\Services\Game\Repositories\GameRepository;
use App\Support\Exceptions\ApiException;
use App\Support\GameHelper;
use Illuminate\Contracts\Auth\Authenticatable;

readonly class GameService
{
    /**
     * @param GameRepository $gameRepository
     * @param User $user
     */
    public function __construct(
        private GameRepository $gameRepository,
        private Authenticatable $user,
    ) {
    }

    /**
     * @param CreateGameDto $createGameDto
     * @return Game
     */
    final public function createGame(CreateGameDto $createGameDto): Game
    {
        if ($this->user->gender === GenderSelectionEnum::MAN) {
            return $this->gameRepository->createGameMan($createGameDto);
        }

        return $this->gameRepository->createGameWoman($createGameDto);
    }

    /**
     * @param SearchActiveGameDto $searchActiveGameDto
     * @return Game|null
     * @throws ApiException
     */
    final public function searchActiveGame(SearchActiveGameDto $searchActiveGameDto): ?Game
    {
        $game = $this->getAnActiveGameForPlayer();

        if ($game) {
            return $game;
        }

        $game = match ($this->user->gender) {
            GenderSelectionEnum::MAN => $this->gameRepository->findGameForMan(),
            GenderSelectionEnum::WOMAN => $this->gameRepository->findGameForWoman(),
        };

        if (!$game) {
            throw new ApiException(ExceptionEnum::NO_ACTIVE_GAME->value);
        }

        return $this->distributeTheGameByGender($game, $searchActiveGameDto);
    }

    /**
     * @param int $gameId
     * @return Game|null
     * @throws ApiException
     */
    final public function getInfoAboutTheMatchPlayed(int $gameId): ?Game
    {
        $userId = $this->user->id;
        $infoAboutMatch = $this->gameRepository->getInfoAboutTheMatchPlayed($gameId, $userId);

        if (!$infoAboutMatch) {
            throw new ApiException(ExceptionEnum::NOT_FOUND_GAME->value);
        }

        return $infoAboutMatch;
    }

    /**
     * @param AddAnswerTheQuestionsDto $answerTheQuestionsDto
     * @return void
     * @throws ApiException
     */
    final public function addAnswerTheQuestions(AddAnswerTheQuestionsDto $answerTheQuestionsDto): void
    {
        $userId = $this->user->id;

        $gameActive = $this->getAnActiveGameForPlayer();

        if (!$gameActive) {
            throw new ApiException(ExceptionEnum::NO_ACTIVE_GAME->value);
        }

        match ($this->user->gender) {
            GenderSelectionEnum::MAN => $this->gameRepository->updateAnswersToWoman(
                $answerTheQuestionsDto,
                $gameActive,
                $userId
            ),
            GenderSelectionEnum::WOMAN => $this->gameRepository->updateAnswersToMen(
                $answerTheQuestionsDto,
                $gameActive,
                $userId
            ),
        };
    }

    /**
     * @param int $selectLikeUserRequest
     * @return void
     * @throws ApiException
     */
    final public function selectLikeUser(int $selectLikeUserRequest): void
    {
        $userId = $this->user->id;

        $activeGame = $this->getAnActiveGameForPlayer();

        if (!$activeGame) {
            throw new ApiException(ExceptionEnum::NO_ACTIVE_GAME->value);
        }

        $infoTheFieldUser = GameHelper::getFieldInfoUser($activeGame, $userId);

        $this->gameRepository->selectLikeUser($selectLikeUserRequest, $infoTheFieldUser, $activeGame);
    }

    /**
     * @return Game|null
     */
    private function getAnActiveGameForPlayer(): ?Game
    {
        return match ($this->user->gender) {
            GenderSelectionEnum::MAN => $this->gameRepository->getAnActiveGameForMan($this->user->id),
            GenderSelectionEnum::WOMAN => $this->gameRepository->getAnActiveGameForWoman($this->user->id),
        };
    }

    /**
     * @param Game $game
     * @param SearchActiveGameDto $searchActiveGameDto
     * @return Game|null
     */
    private function distributeTheGameByGender(Game $game, SearchActiveGameDto $searchActiveGameDto): ?Game
    {
        if ($this->user->gender === GenderSelectionEnum::MAN) {
            return $this->includeUserGameForMan($game, $searchActiveGameDto);
        }

        return $this->includeUserGameForWoman($game, $searchActiveGameDto);
    }

    /**
     * @param Game $game
     * @param SearchActiveGameDto $searchActiveGameDto
     * @return Game|null
     */
    private function includeUserGameForMan(Game $game, SearchActiveGameDto $searchActiveGameDto): ?Game
    {
        if (!$game->fourth_user_id) {
            $game = $this->gameRepository->updateDataThePlayer(
                'fourth_user_id',
                'fourth_user_info',
                $searchActiveGameDto->question,
                $game
            );
        } elseif (!$game->fifth_user_id) {
            $game = $this->gameRepository->updateDataThePlayer(
                'fifth_user_id',
                'fifth_user_info',
                $searchActiveGameDto->question,
                $game
            );
        } elseif (!$game->sixth_user_id) {
            $game = $this->gameRepository->updateDataThePlayer(
                'sixth_user_id',
                'sixth_user_info',
                $searchActiveGameDto->question,
                $game
            );
            $this->updateStatusGameForMan($game);
        }

        return $game;
    }

    /**
     * @param Game $game
     * @param SearchActiveGameDto $searchActiveGameDto
     * @return Game|null
     */
    private function includeUserGameForWoman(Game $game, SearchActiveGameDto $searchActiveGameDto): ?Game
    {
        if (!$game->first_user_id) {
            $game = $this->gameRepository->updateDataThePlayer(
                'first_user_id',
                'first_user_info',
                $searchActiveGameDto->question,
                $game
            );
        } elseif (!$game->second_user_id) {
            $game = $this->gameRepository->updateDataThePlayer(
                'second_user_id',
                'second_user_info',
                $searchActiveGameDto->question,
                $game
            );
        } elseif (!$game->third_user_id) {
            $game = $this->gameRepository->updateDataThePlayer(
                'third_user_id',
                'third_user_info',
                $searchActiveGameDto->question,
                $game
            );
            $this->updateStatusGameForWoman($game);
        }

        return $game;
    }

    /**
     * @param Game $game
     * @return void
     */
    private function updateStatusGameForMan(Game $game): void
    {
        if ($game->third_user_id !== null) {
            $this->gameRepository->changeTheStatusOfTheGame($game);
        }
    }

    /**
     * @param Game $game
     * @return void
     */
    private function updateStatusGameForWoman(Game $game): void
    {
        if ($game->sixth_user_id !== null) {
            $this->gameRepository->changeTheStatusOfTheGame($game);
        }
    }
}
