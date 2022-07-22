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



Route::get('/', [Controller::class, 'home'])->name('home');
Route::post('/', [Controller::class, 'entertainmentSearch']);

// Show Register/create form (user)
Route::get('/register', [UserController::class, 'create'])->name('register');

// Create New (user)
Route::post('/users', [UserController::class, 'store'])->name('users.store');

// Show Login form (user)
Route::get('/login', [UserController::class, 'login'])->name('login');

// Login (user)
Route::post('/users/authenticate', [UserController::class, 'authenticate'])->name('users.authenticate');

// logout (user)
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// Add TV show to user
Route::get('/user-tv/{id}', [UserController::class, 'addTvShow'])->name('users.tv.store');

// Add movie to user
Route::get('/user-movie/{id}', [UserController::class, 'addMovie'])->name('users.movie.store');