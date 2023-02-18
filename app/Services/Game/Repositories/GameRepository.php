<?php

namespace App\Services\Game\Repositories;

use App\Models\Game;
use App\Services\Game\DTOs\AddAnswerTheQuestionsDto;
use App\Services\Game\DTOs\CreateGameDto;
use App\Services\Game\Enum\StatusGameEnum;

class GameRepository
{
    /**
     * @param CreateGameDto $dto
     * @return Game
     */
    final public function createGameMan(CreateGameDto $dto): Game
    {
        $game = new Game();

        $game->fourth_user_id = auth()->id();
        $game->fourth_user_info = ['question' => $dto->question];
        $game->status = StatusGameEnum::ACTIVE;
        $game->save();

        return $game;
    }

    /**
     * @param CreateGameDto $dto
     * @return Game
     */
    final public function createGameWoman(CreateGameDto $dto): Game
    {
        $game = new Game();

        $game->first_user_id = auth()->id();
        $game->first_user_info = ['question' => $dto->question];
        $game->status = StatusGameEnum::ACTIVE;
        $game->save();

        return $game;
    }

    /**
     * @return Game|null
     */
    final public function findGameForMan(): ?Game
    {
        $game = Game::query()
            ->where('status', '=', StatusGameEnum::ACTIVE)
            ->where(function ($query) {
                $query->orWhereNull('fourth_user_id')
                    ->orWhereNull('fifth_user_id')
                    ->orWhereNull('sixth_user_id');
            })
            ->oldest()
            ->first();

        if ($game instanceof Game) {
            return $game;
        }

        return null;
    }

    /**
     * @return Game|null
     */
    final public function findGameForWoman(): ?Game
    {
        $game = Game::query()
            ->where('status', '=', StatusGameEnum::ACTIVE)
            ->where(function ($query) {
                $query->orWhereNull('first_user_id')
                    ->orWhereNull('second_user_id')
                    ->orWhereNull('third_user_id');
            })
            ->oldest()
            ->first();

        if ($game instanceof Game) {
            return $game;
        }

        return null;
    }

    /**
     * @param string $userIdColumn
     * @param string $userInfoColumn
     * @param string $question
     * @param Game $game
     * @return Game|null
     */
    final public function updateDataThePlayer(string $userIdColumn, string $userInfoColumn, string $question, Game $game): ?Game
    {
        $game->{$userIdColumn} = auth()->id();
        $game->{$userInfoColumn} = ['question' => $question];
        $game->save();

        return $game;
    }

    /**
     * @param Game $game
     * @return void
     */
    final public function changeTheStatusOfTheGame(Game $game): void
    {
        $game->status = StatusGameEnum::PROCESS;
        $game->save();
    }

    /**
     * @param int $gameId
     * @param int $userId
     * @return Game|null
     */
    final public function getInfoAboutTheMatchPlayed(int $gameId, int $userId): ?Game
    {
        $game = Game::query()
            ->where('id', '=', $gameId)
            ->where(function ($query) use ($userId) {
                $query->orWhere('first_user_id', '=', $userId)
                    ->orWhere('second_user_id', '=', $userId)
                    ->orWhere('third_user_id', '=', $userId)
                    ->orWhere('fourth_user_id', '=', $userId)
                    ->orWhere('fifth_user_id', '=', $userId)
                    ->orWhere('sixth_user_id', '=', $userId);
            })
            ->first();

        if ($game instanceof Game) {
            return $game;
        }

        return null;
    }

    /**
     * @param int $userId
     * @return Game|null
     */
    final public function getAnActiveGameForMan(int $userId): ?Game
    {
        $game = Game::query()
            ->where(function ($query) {
                $query->orWhere('status', '=', StatusGameEnum::PROCESS)
                    ->orWhere('status', '=', StatusGameEnum::ACTIVE);
            })
            ->where(function ($query) use ($userId) {
                $query->orWhere('fourth_user_id', '=', $userId)
                    ->orWhere('fifth_user_id', '=', $userId)
                    ->orWhere('sixth_user_id', '=', $userId);
            })
            ->latest()
            ->first();

        if ($game instanceof Game) {
            return $game;
        }

        return null;
    }

    /**
     * @param int $userId
     * @return Game|null
     */
    final public function getAnActiveGameForWoman(int $userId): ?Game
    {
        $game = Game::query()
            ->where(function ($query) {
                $query->orWhere('status', '=', StatusGameEnum::PROCESS)
                    ->orWhere('status', '=', StatusGameEnum::ACTIVE);
            })
            ->where(function ($query) use ($userId) {
                $query->orWhere('first_user_id', '=', $userId)
                    ->orWhere('second_user_id', '=', $userId)
                    ->orWhere('third_user_id', '=', $userId);
            })
            ->latest()
            ->first();

        if ($game instanceof Game) {
            return $game;
        }

        return null;
    }

    /**
     * @param AddAnswerTheQuestionsDto $answerTheQuestionsDto
     * @param Game $game
     * @param int $userId
     * @return void
     */
    final public function updateAnswersToMen(AddAnswerTheQuestionsDto $answerTheQuestionsDto, Game $game, int $userId): void
    {
        $fourthUserInfo = $game->fourth_user_info;
        $fourthUserInfo['answers'][$userId] = ['answer' => $answerTheQuestionsDto->answer_to_fourth_user, 'user_id' => $userId];
        $game->fourth_user_info = $fourthUserInfo;

        $fifthUserInfo = $game->fifth_user_info;
        $fifthUserInfo['answers'][$userId] = ['answer' => $answerTheQuestionsDto->answer_to_fifth_user, 'user_id' => $userId];
        $game->fifth_user_info = $fifthUserInfo;

        $sixthUserInfo = $game->sixth_user_info;
        $sixthUserInfo['answers'][$userId] = ['answer' => $answerTheQuestionsDto->answer_to_sixth_user, 'user_id' => $userId];
        $game->sixth_user_info = $sixthUserInfo;

        $game->save();
    }

    /**
     * @param AddAnswerTheQuestionsDto $answerTheQuestionsDto
     * @param Game $game
     * @param int $userId
     * @return void
     */
    final public function updateAnswersToWoman(AddAnswerTheQuestionsDto $answerTheQuestionsDto, Game $game, int $userId): void
    {
        $firstUserInfo = $game->first_user_info;
        $firstUserInfo['answers'][$userId] = ['answer' => $answerTheQuestionsDto->answer_to_first_user, 'user_id' => $userId];
        $game->first_user_info = $firstUserInfo;

        $secondUserInfo = $game->second_user_info;
        $secondUserInfo['answers'][$userId] = ['answer' => $answerTheQuestionsDto->answer_to_second_user, 'user_id' => $userId];
        $game->second_user_info = $secondUserInfo;

        $thirdUserInfo = $game->third_user_info;
        $thirdUserInfo['answers'][$userId] = ['answer' => $answerTheQuestionsDto->answer_to_third_user, 'user_id' => $userId];
        $game->third_user_info = $thirdUserInfo;

        $game->save();
    }
}
