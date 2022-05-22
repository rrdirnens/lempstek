<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;


// Route::get('/', function () {
//   $visited = DB::select('select * from places where visited = ?', [1]);
//   $togo = DB::select('select * from places where visited = ?', [0]);

//   return view('travellist', ['visited' => $visited, 'togo' => $togo ] );
// });

Route::get('/', [Controller::class, 'home']);