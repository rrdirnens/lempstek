<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShowController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MovieController;


/*
|--------------------------------------------------------------------------
| Common resource routes
|--------------------------------------------------------------------------
| index - show all
| show - show one
| create - create new
| store - store new
| edit - edit one
| update - update one
| destroy - delete one
*/

// home route
Route::get('/', [Controller::class, 'home'])->name('home');

// search route
Route::get('/search', [Controller::class, 'entertainmentSearch'])->name('search');


/*
|--------------------------------------------------------------------------
| User routes
|--------------------------------------------------------------------------
*/

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
Route::get('/user-tv/{id}', [UserController::class, 'addTvShow'])->name('users.tv.store')->middleware('auth');

// Remove TV show from user
Route::get('/user-tv/{id}/delete', [UserController::class, 'removeTvShow'])->name('users.tv.delete')->middleware('auth');

// Add movie to user
Route::get('/user-movie/{id}', [UserController::class, 'addMovie'])->name('users.movie.store')->middleware('auth');

// Remove movie from user
Route::get('/user-movie/{id}/delete', [UserController::class, 'removeMovie'])->name('users.movie.delete')->middleware('auth');

// Show user's TV shows

// Show user's movies

// Show user's profile
Route::get('/user/{id}', [UserController::class, 'show'])->name('users.profile')->middleware(['auth', 'doNotCacheResponse']);

Route::post('/user/{id}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware(['auth', 'doNotCacheResponse']);


/*
|--------------------------------------------------------------------------
| Movie routes
|--------------------------------------------------------------------------
*/

// Show movie page
Route::get('/movies/{id}', [MovieController::class, 'showMovie'])->name('movies.show');

// Show show page
Route::get('/shows/{id}', [ShowController::class, 'showShow'])->name('shows.show');