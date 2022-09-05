<?php

namespace App\Http\Controllers;

use stdClass;

class ShowController extends Controller
{

    public function showShow($id) {
        $this->getBasicUserData();
        $show = $this->getShowById($id);
        $result = new stdClass();
        $result->show = $show->getBody()->getContents();
        $result->show = json_decode($result->show, true);
        

        if(empty($result->show['seasons']) || !isset($result->show['seasons'])) {
            $result->show['fetched_episodes'] = null;
        } else {
            $result->show['fetched_episodes'] = $this->getAllEpisodesByShowId($id, $result->show['seasons']);
        }

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

        // check if list of shows returned by $this->getBasicUserData() contains this show (using this for displaying the "remove / add" buttons)
        $result->show['in_calendar'] = false;
        foreach($this->data['shows'] as $show) {
            if($show['show_id'] == $id) {
                $result->show['in_calendar'] = true;
                break;
            }
        }
        
        $this->data['show'] = $result->show;
                
        return view('show', $this->data);

    }
}
