<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http as Client;
use Illuminate\Http\Request;
use stdClass;

class MovieController extends Controller
{
    //
    public function showMovie(Request $request, $id) {
        
        $key = $this->tmdbkey;
        $client = new Client();
        
        $movie = $client::get("https://api.themoviedb.org/3/movie/$id", [
            'api_key' => $key,
        ]);   

        $result = new stdClass();
        $result->movie = $movie->object();
        
        // convert $result->movie to an array
        $this->data['movie'] = json_decode(json_encode($result->movie), true);

        
        return view('movie', $this->data);

    }
}
