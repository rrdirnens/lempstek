<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http as Client;

trait GetShowTrait
{
    public function getShowById($request, $id)
    {
        $key = $this->tmdbkey;
        $client = new Client();
        
        $show = $client::get("https://api.themoviedb.org/3/tv/$id", [
            'api_key' => $key,
        ]);   
     
        return $show;   
     
    }
}