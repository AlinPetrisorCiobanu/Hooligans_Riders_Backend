<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Events_Controller;
use App\Http\Controllers\Users_Controller;
use Illuminate\Support\Facades\Route;

// AUTH
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

//USERS
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [Users_Controller::class, 'user_data']);
    Route::put('/user', [Users_Controller::class, 'update_user']);
    Route::delete('/user', [Users_Controller::class, 'delete_user']);
    Route::get('/users', [Users_Controller::class, 'list_users'])->middleware('isNotUser');
});

//Events
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/events', [Events_Controller::class, 'list_events']);
    Route::post('/events', [Events_Controller::class, 'new_event'])->middleware('isNotUser');
    Route::patch('/add_participant/{id}', [Events_Controller::class, 'add_participant']);
    Route::patch('/remove_participant/{id}', [Events_Controller::class, 'remove_participant']);
});