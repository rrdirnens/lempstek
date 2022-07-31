<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http as Client;
use Illuminate\Http\Request;
use stdClass;

class ShowController extends Controller
{
    public function showShow(Request $request, $id) {
        
        $key = $this->tmdbkey;
        $client = new Client();
        
        $show = $client::get("https://api.themoviedb.org/3/tv/$id", [
            'api_key' => $key,
        ]);   

        $result = new stdClass();
        $result->show = $show->object();

        // show how many days left until next episode
        $next_episode = $result->show->next_episode_to_air;
        $next_episode_date = new \DateTime($next_episode->air_date);
        $today = new \DateTime();
        $diff = $next_episode_date->diff($today);
        $days_left = $diff->format('%m month(s), %a day(s)');
        $result->show->next_release_calc = $days_left;
        
        // convert $result->movie to an array
        $this->data['show'] = json_decode(json_encode($result->show), true);
                
        return view('show', $this->data);

    }
}
