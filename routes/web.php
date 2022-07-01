<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;


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