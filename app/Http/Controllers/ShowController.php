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
        
        $showData = $this->getSeasonAndEpisodesData($id, $result->show);

        // show how many days left until next episode
        $next_episode = $showData['next_episode_to_air'] ?? null;
        if($next_episode != null) {
            $next_episode_date = new \DateTime($next_episode['air_date']);
            $today = new \DateTime();
            $diff = $next_episode_date->diff($today);
            $days_left = $diff->format('%m month(s), %a day(s)');
            $showData['next_release_calc'] = $days_left;
        } else {
            $showData['next_release_calc'] = 'No next episode info';
        }
        
        $showData['in_calendar'] = false;

        if($this->data['logged_in']) {
            // check if list of shows returned by $this->getBasicUserData() contains this show (using this for displaying the "remove / add" buttons)
            foreach($this->data['shows'] as $show) {
                if($show['show_id'] == $id) {
                    $showData['in_calendar'] = true;
                    break;
                }
            }
        }
        
        $this->data['show'] = $showData;

        return view('show', $this->data);

    }
    
    public function getSeasonAndEpisodesData($id, $show) {
        $requestWithSeasons = [];
        if(empty($show['seasons']) || !isset($show['seasons'])) {
            $requestWithSeasons = null;
            return $requestWithSeasons;
        } else {
            $requestWithSeasons = $this->getAllEpisodesByShowId($id, $show['seasons']);
            
            $seasonKeys = [];
            $startWith = 'season';
            foreach($requestWithSeasons as $key => $value) {
                $expKey = explode('/', $key);
                if($expKey[0] == $startWith) {
                    $seasonKeys[$key] = $value;
                }
            }
            ksort($seasonKeys);
            $show['sorted_seasons'] = $seasonKeys;
            return $show;
        }
    }
}
