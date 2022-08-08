<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http as Client;

trait GetMovieTrait
{
    public function getMovieById($request, $id)
    {
        $key = $this->tmdbkey;
        $client = new Client();
        
        $movie = $client::get("https://api.themoviedb.org/3/movie/$id", [
            'api_key' => $key,
        ]);   
     
        return $movie;
    }
}