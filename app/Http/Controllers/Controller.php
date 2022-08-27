<?php

namespace App\Http\Controllers;

use stdClass;
use App\Models\ShowUser;
use App\Models\MovieUser;
use App\Traits\GetShowTrait;
use Illuminate\Http\Request;
use App\Traits\GetMovieTrait;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Http as Client;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, GetShowTrait, GetMovieTrait;

    protected $tmdbkey;

    public function __construct() {
        $this->tmdbkey = config('tmdb.key') ?? null;
    }

    public function home(Request $request) {
        $this->data['logged_in'] = auth()->check();

        // if logged in, also add items and dates
        if ($this->data['logged_in']) {
            $this->getUserItems();
            $this->data['dates'] = $this->getUserDates($request);
        }

        return view('home', $this->data);
    }

    public function entertainmentSearch (Request $request) {
        $query = $request->input('search_query');

        $tvSearch = Client::get('https://api.themoviedb.org/3/search/tv', [
            'api_key' => $this->tmdbkey,
            'query' => $query,
        ]);
        $movieSearch = Client::get('https://api.themoviedb.org/3/search/movie', [
            'api_key' => $this->tmdbkey,
            'query' => $query,
        ]);

        $results = new stdClass();
        $results->tv = $tvSearch->object()->results;
        $results->movies = $movieSearch->object()->results;

        if (empty($results->tv) && empty($results->movies)) {
            $this->data['search_results'] = null;
            $this->data['search_msg'] = 'Sorry, no result found. Try again.';
        } else {
            $this->data['search_results'] = $results;
            $this->data['search_msg'] = '';
        } 

        $this->editSearchResultsBasedOnUserCalendar($this->data['search_results']);

        return $this->home($request, $this->data);
    }

    /**
     * Edit search results based on user calendar
     */
    private function editSearchResultsBasedOnUserCalendar($search_results) {
        if (empty($search_results)) {
            return;
        }
        $user = auth()->user();
        $shows = ShowUser::where('user_id', $user->id)->get();
        $movies = MovieUser::where('user_id', $user->id)->get();

        foreach ($search_results->tv as $show) {
            $show->in_calendar = false;
            foreach ($shows as $show_user) {
                if ($show_user->show_id == $show->id) {
                    $show->in_calendar = true;
                    break;
                }
            }
        }
        foreach ($search_results->movies as $movie) {
            $movie->in_calendar = false;
            foreach ($movies as $movie_user) {
                if ($movie_user->movie_id == $movie->id) {
                    $movie->in_calendar = true;
                    break;
                }
            }
        }
        return $search_results;
    }

    public function getUserItems() {
        $user = auth()->user();
        $shows = ShowUser::where('user_id', $user->id)->get();
        $movies = MovieUser::where('user_id', $user->id)->get();
        
        $this->data['user'] = $user;
        
        $this->data['shows'] = json_decode($shows);
        foreach ($this->data['shows'] as $show) {
            $show->details = $this->getShowById($show->show_id)->object();
        }

        $this->data['movies'] = json_decode($movies);
        foreach ($this->data['movies'] as $movie) {
            $movie->details = $this->getMovieById($movie->movie_id)->object();
        }
    }

    public function getUserDates() {
        $dates = [];
        
        foreach ($this->data['shows'] as $show) {
            $next = $show->details->next_episode_to_air;
            if (!$next) { continue; }
            $dates[] = [
                'type' => 'show',
                'date' => $next->air_date, 
                'name' => $next->name, 
                'ep_number' => $next->episode_number, 
                'ep_season_number' => $next->season_number, 
                'show_name' => $show->details->name, 
                'id' => $show->show_id, 
                'image' => $show->details->poster_path, 
            ];
        }
        
        foreach ($this->data['movies'] as $movie) {
            $release = $movie->details->release_date;
            if ($release) {
                $dates[] = [
                    'type' => 'movie',
                    'date' => $release,
                    'name' => $movie->details->title, 
                    'id' => $movie->movie_id, 
                    'image' => $movie->details->poster_path, 
                ];
            }
        }

        $dates = collect($dates)->sortBy('date')->groupBy('date');
        
        foreach ($dates as $date => $details) {

            // add what day of week each date is
            $day = date('l', strtotime($date));
            $dates[$date] = collect($details)->map(function($detail) use ($day) {
                $detail['day'] = $day;
                return $detail;
            }); 
            
            // calculate how many days left until each date
            $daysLeft = $this->daysLeft($date);
            $dates[$date] = collect($dates[$date])->map(function($detail) use ($daysLeft) {
                $detail['days_left'] = $daysLeft;
                return $detail;
            }); 

        }

        // Remove dates which are older than X days.
        $dates = collect($dates)->filter(function($details, $date) {
            return $this->daysLeft($date) >= -14;
        });

        // indicate which date is the current date
        $dates = $dates->map(function($details, $date) {
            $details = collect($details)->map(function($detail) use ($date) {
                $detail['is_today'] = $date == date('Y-m-d');
                return $detail;
            });
            return $details;
        });

        return $dates;
    }

    /**
     * Calculate how many days left until a date
     *
     * @param  string  $date
     * @return int
     */

    public function daysLeft($date) {
        $today = date('Y-m-d');
        $diff = date_diff(date_create($today), date_create($date));
        if ($today > $date) return - $diff->days;
        return $diff->days;
    }
}
