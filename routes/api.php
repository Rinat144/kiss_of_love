<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'auth'
], static function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});

Route::group([
    'prefix' => 'city',
], static function () {
    Route::get('/all', [CityController::class, 'getAllCities']);
}
);

Route::group([
    'prefix' => 'match',
    'middleware' => 'auth:api'
], static function () {
    Route::post('/create', [GameController::class, 'createGame']);
    Route::get('/info/{gameId}', [GameController::class, 'getInfoTheGame']);
    Route::post('/search_active', [GameController::class, 'searchActiveGame']);
    Route::post('/answer', [GameController::class, 'addAnswerTheQuestions']);
    Route::post('/like', [GameController::class, 'selectLikeUser']);
});
