<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http as Client;
use Illuminate\Http\Request;
use stdClass;

class MovieController extends Controller
{

    public function showMovie($id) {
        $this->getBasicUserData();
        $movie = $this->getMovieById($id);

        // TODO: check if movie exists

        $result = new stdClass();
        $result->movie = $movie->getBody()->getContents();
        
        // convert $result->movie to an array
        $result->movie = json_decode($result->movie, true);
        
        // check if list of movies returned by $this->getBasicUserData() contains this movies (using this for displaying the "remove / add" buttons)
        $result->movie['in_calendar'] = false;
        foreach($this->data['movies'] as $movie) {
            if($movie['movie_id'] == $id) {
                $result->movie['in_calendar'] = true;
                break;
            }
        }
        
        $this->data['movie'] = $result->movie;
        
        return view('movie', $this->data);

    }
}
