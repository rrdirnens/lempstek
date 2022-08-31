<?php

namespace App\Http\Controllers;

use stdClass;

class ShowController extends Controller
{

    public function showShow($id) {
        
        $show = $this->getShowById($id);

        $result = new stdClass();
        $result->show = $show->getBody()->getContents();

        
        $result->show = json_decode($result->show, true);
        
        // show how many days left until next episode
        $next_episode = $result->show['next_episode_to_air'] ?? null;
        if($next_episode != null) {
            $next_episode_date = new \DateTime($next_episode['air_date']);
            $today = new \DateTime();
            $diff = $next_episode_date->diff($today);
            $days_left = $diff->format('%m month(s), %a day(s)');
            $result->show['next_release_calc'] = $days_left;
        } else {
            $result->show['next_release_calc'] = 'No next episode info';
        }
        
        // convert $result->movie to an array
        $this->data['show'] = $result->show;
                
        return view('show', $this->data);

    }
}
