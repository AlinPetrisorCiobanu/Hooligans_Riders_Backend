<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// AUTH
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
