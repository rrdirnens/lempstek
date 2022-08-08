<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http as Client;
use Illuminate\Http\Request;
use stdClass;
use App\Traits\GetMovieTrait;

class MovieController extends Controller
{
    use GetMovieTrait;

    public function showMovie(Request $request, $id) {

        $movie = $this->getMovieById($request, $id);

        // TODO: check if movie exists

        $result = new stdClass();
        $result->movie = $movie->object();
        
        // convert $result->movie to an array
        $this->data['movie'] = json_decode(json_encode($result->movie), true);

        
        return view('movie', $this->data);

    }
}
