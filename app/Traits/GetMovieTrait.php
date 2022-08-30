<?php

namespace App\Traits;

use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http as Client;

trait GetMovieTrait
{
    public function getMoviesByIds($ids)
    {
        $key = $this->tmdbkey;
        $client = new Client();

        $url = "https://api.themoviedb.org/3/movie/";
        
        $responses = $client::pool(function (Pool $pool) use ($url, $ids, $key) {
            return collect($ids)->map(function ($id) use ($url, $key, $pool) {
                return $pool->get($url . $id . "?api_key=$key");
            });
        });

        $movies = [];

        foreach ($responses as $response) {
            $movies[] = $response->json();
        }

        return $movies;
    }
}