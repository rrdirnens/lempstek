<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http as Client;
use Illuminate\Http\Client\Pool;

trait GetShowTrait
{
    public function getShowsByIds($ids)
    {
        $key = $this->tmdbkey;
        $client = new Client();

        $url = "https://api.themoviedb.org/3/tv/";

        $responses = $client::pool(function (Pool $pool) use ($url, $ids, $key) {
            foreach ($ids as $id) {
                $pool->get($url . $id . "?api_key=" . $key);
            }
        });

        $shows = [];
        
        foreach ($responses as $response) {
            $shows[] = $response->json();
        }
        return $shows;
     
    }
}