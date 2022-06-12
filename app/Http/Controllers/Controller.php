<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http as Client;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function home() {
        $key = env('TMDB_API_KEY') ?? null;

        // search
        // $tvSearch = Client::get('https://api.themoviedb.org/3/search/tv', [
        //     'api_key' => $key,
        //     'query' => 'the+walking+dead',
        // ]);
        
        // get TV show
        // $tvShow = Client::get('https://api.themoviedb.org/3/tv/1402', [
        //     'api_key' => $key,
        // ]);
        if (!isset($key)) return view('travellist');
        // dd($key);

        $response = Client::get('https://api.themoviedb.org/3/tv/1402', [
            'api_key' => $key,
        ]);


        $entertainment[0]= [];
        $entertainment[0]['name'] = $response->object()->name;
        $entertainment[0]['last_episode_to_air'] = $response->object()->last_episode_to_air;
        $entertainment[0]['next_episode_to_air'] = $response->object()->next_episode_to_air;

        return view('travellist',  [
            'entertainment' => $entertainment
        ]);
    }
}
