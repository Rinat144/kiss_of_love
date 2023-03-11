<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserAvatarController;
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

Route::group([
    'prefix' => 'chat',
    'middleware' => 'auth:api'
], static function () {
    Route::post('/store', [ChatController::class, 'store']);
    Route::post('/send_message', [MessageController::class, 'sendMessage']);
    Route::get('/specific/{chatId}', [ChatController::class, 'getSpecificChat']);
    Route::get('/all', [ChatController::class, 'index']);
    Route::delete('/delete/{chatId}', [ChatController::class, 'destroy']);
});

Route::group([
    'prefix' => 'avatar',
    'middleware' => 'auth:api',
], static function () {
    Route::post('/add', [UserAvatarController::class, 'store']);
    Route::delete('/destroy/{id}', [UserAvatarController::class, 'destroy']);
    Route::get('/all/{userId}', [UserAvatarController::class, 'index']);
});

Route::group([
    'prefix' => 'product',
    'middleware' => 'auth:api',
], static function () {
    Route::get('/all', [ProductController::class, 'index']);
});
