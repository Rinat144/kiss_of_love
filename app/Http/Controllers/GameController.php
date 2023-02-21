<?php

namespace App\Http\Controllers;

use App\Services\Game\GameService;
use App\Services\Game\Requests\AddAnswerTheQuestionsRequest;
use App\Services\Game\Requests\CreateGameRequest;
use App\Services\Game\Requests\SearchActiveGameRequest;
use App\Services\Game\Requests\SelectLikeUserRequest;
use App\Services\Game\Resources\GameResource;
use App\Services\Game\Resources\GetInfoTheGameResource;
use App\Services\Game\Resources\SearchActiveGameResource;
use App\Support\Exceptions\ApiException;
use Illuminate\Http\JsonResponse;

class GameController extends Controller
{
    /**
     * @param GameService $gameService
     */
    public function __construct(
        private readonly GameService $gameService
    ) {
    }

    /**
     * @param CreateGameRequest $createGameRequest
     * @return GameResource
     */
    final public function createGame(CreateGameRequest $createGameRequest): GameResource
    {
        $game = $this->gameService->createGame($createGameRequest->getDto());

        return new GameResource($game);
    }

    /**
     * @param int $gameId
     * @return GetInfoTheGameResource
     * @throws ApiException
     */
    final public function getInfoTheGame(int $gameId): GetInfoTheGameResource
    {
        $infoTheGame = $this->gameService->getInfoAboutTheMatchPlayed($gameId);

        return new GetInfoTheGameResource($infoTheGame);
    }

    /**
     * @param SearchActiveGameRequest $searchActiveGameRequest
     * @return SearchActiveGameResource
     * @throws ApiException
     */
    final public function searchActiveGame(SearchActiveGameRequest $searchActiveGameRequest): SearchActiveGameResource
    {
        $game = $this->gameService->searchActiveGame($searchActiveGameRequest->getDto());

        return new SearchActiveGameResource($game);
    }

    /**
     * @param AddAnswerTheQuestionsRequest $answerTheQuestionsRequest
     * @return JsonResponse
     * @throws ApiException
     */
    final public function addAnswerTheQuestions(AddAnswerTheQuestionsRequest $answerTheQuestionsRequest): JsonResponse
    {
        $this->gameService->addAnswerTheQuestions($answerTheQuestionsRequest->getDto());

        return response()->json([
            'status' => true,
        ]);
    }

    /**
     * @param SelectLikeUserRequest $selectLikeUserRequest
     * @return JsonResponse
     * @throws ApiException
     */
    final public function selectLikeUser(SelectLikeUserRequest $selectLikeUserRequest): JsonResponse
    {
        $this->gameService->selectLikeUser($selectLikeUserRequest['select_user_id']);

        return response()->json([
            'status' => true,
        ]);
    }
}
