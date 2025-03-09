<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GameController;

Route::get('/', [UserController::class, 'login'])->name('index');
Route::get('/login', [UserController::class, 'login'])->name('login');
Route::post('/login', [UserController::class, 'postLogin']);
Route::get('/register', [UserController::class, 'register'])->name('register');
Route::post('/register', [UserController::class, 'postRegister']);



Route::group(['middleware' => 'auth'], function () {
	Route::get('/logout', [UserController::class, 'logout']);

	Route::get('/game', [GameController::class, 'game'])->name('game');
});
