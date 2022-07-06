<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


/*
|--------------------------------------------------------------------------
| common resource routes
|--------------------------------------------------------------------------
| index - show all
| show - show one
| create - create new
| store - store new
| edit - edit one
| update - update one
| destroy - delete one
*/



Route::get('/', [Controller::class, 'home']);
Route::post('/', [Controller::class, 'entertainmentSearch']);

// Show Register/create form (user)
Route::get('/register', [UserController::class, 'create']);

// Create New (user)
Route::post('/users', [UserController::class, 'store']);