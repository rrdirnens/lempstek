<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http as Client;
use Illuminate\Http\Request;
use stdClass;

class MovieController extends Controller
{

    public function showMovie($id) {

        $movie = $this->getMovieById($id);

        // TODO: check if movie exists

        $result = new stdClass();
        $result->movie = $movie->getBody()->getContents();
        
        // convert $result->movie to an array
        $this->data['movie'] = json_decode($result->movie, true);
        
        return view('movie', $this->data);

    }
}
