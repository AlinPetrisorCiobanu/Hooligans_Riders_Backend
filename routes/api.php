<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Users_Controller;
use Illuminate\Support\Facades\Route;

// AUTH
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

//USERS
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [Users_Controller::class, 'list_users']);
    Route::put('/user', [Users_Controller::class, 'update_user']);
});