<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GameController;
use App\Http\Middleware\LoggedOut;

Route::group(['middleware' => [LoggedOut::class, 'throttle:global']], function () {
	Route::get('/', [UserController::class, 'login'])->name('index');
	Route::get('/login', [UserController::class, 'login'])->name('login');
	Route::post('/login', [UserController::class, 'postLogin']);
	Route::get('/register', [UserController::class, 'register'])->name('register');
	Route::post('/register', [UserController::class, 'postRegister']);
});

Route::group(['middleware' => ['auth', 'throttle:global']], function () {
	Route::get('/logout', [UserController::class, 'logout']);

	Route::get('/game', [GameController::class, 'game'])->name('game');
	Route::post('/chat', [GameController::class, 'chat'])->name('chat');
	Route::get('/chat', [GameController::class, 'chat'])->name('chat');
	Route::post('/feedback', [GameController::class, 'feedback'])->name('feedback');
	Route::post('/survey', [GameController::class, 'survey'])->name('survey');

});
