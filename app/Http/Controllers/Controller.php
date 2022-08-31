<?php

namespace App\Http\Controllers;

use stdClass;
use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http as RegClient;
use App\Models\ShowUser;
use App\Models\MovieUser;
use Illuminate\Http\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Database\Console\DumpCommand;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use GuzzleHttp\Exception\RequestException as Exception;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $tmdbkey;

    public function __construct() {
        $this->tmdbkey = config('tmdb.key') ?? null;
    }

    public function getBasicUserData() {
        $this->data['logged_in'] = auth()->check();

        if (!$this->data['logged_in']) return;
        if (isset($this->data['shows'])) return; 
        $user = auth()->user();
        $shows = ShowUser::where('user_id', $user->id)->get();
        $movies = MovieUser::where('user_id', $user->id)->get();

        $this->data['user'] = $user;
        $this->data['shows'] = $shows;
        $this->data['movies'] = $movies;
    }

    public function home(Request $request) {
        $this->getBasicUserData();

        // if logged in, also add items and dates
        if ($this->data['logged_in']) {
            $this->getUserItems();
            $this->data['dates'] = $this->getUserDates($request);
        }

        return view('home', $this->data);
    }

    public function entertainmentSearch (Request $request) {
        $this->getBasicUserData();
        $query = $request->input('search_query');
        $page = $request->input('page') ?? 1;

        $tvSearch = RegClient::get('https://api.themoviedb.org/3/search/tv', [
            'api_key' => $this->tmdbkey,
            'query' => $query,
            'page' => $page,
        ]);
        $movieSearch = RegClient::get('https://api.themoviedb.org/3/search/movie', [
            'api_key' => $this->tmdbkey,
            'query' => $query,
            'page' => $page,
        ]);

        // compare tvSearch and movieSearch total_pages and assign the biggest value to $totalPages
        $totalPages = $tvSearch->object()->total_pages > $movieSearch->object()->total_pages ? $tvSearch->object()->total_pages : $movieSearch->object()->total_pages;

        $results = new stdClass();
        $results->tv = $tvSearch->object()->results;
        $results->movies = $movieSearch->object()->results;

        if (empty($results->tv) && empty($results->movies)) {
            $this->data['search_results'] = null;
            $this->data['search_msg'] = 'Sorry, no result found. Try again.';
        } else {
            $this->data['search_results'] = $results;
            $this->data['search_msg'] = '';
            $this->data['search_pagination_total'] = $totalPages;
            $this->data['search_pagination_current'] = $page;
        } 
        $this->data['search_query'] = $query;
        
        // save search query in the session (used for search result persistence after adding/removing items)
        $request->session()->put('search_query', $query);

        if ($this->data['logged_in']) {
            $this->editSearchResultsBasedOnUserCalendar($this->data['search_results']);
        }

        return $this->home($request, $this);
    }

    /**
     * Edit search results based on user calendar
     */
    private function editSearchResultsBasedOnUserCalendar($search_results) {
        if (empty($search_results)) {
            return;
        }
        
        foreach ($search_results->tv as $show) {
            $show->in_calendar = false;
            foreach ($this->data['shows'] as $show_user) {
                if ($show_user->show_id == $show->id) {
                    $show->in_calendar = true;
                    break;
                }
            }
        }
        foreach ($search_results->movies as $movie) {
            $movie->in_calendar = false;
            foreach ($this->data['movies'] as $movie_user) {
                if ($movie_user->movie_id == $movie->id) {
                    $movie->in_calendar = true;
                    break;
                }
            }
        }
        return $search_results;
    }

    public function getUserItems() {
        
        $this->data['shows'] = json_decode($this->data['shows']);
        $this->data['movies'] = json_decode($this->data['movies']);
        
        $show_ids = [];
        foreach ($this->data['shows'] as $show) {
            // assign show id to an array
            $show_ids[] = $show->show_id;
        }
        $show_details = $this->getShowsByIds($show_ids);

        $this->data['shows'] = $show_details;

        $movie_ids = [];
        foreach ($this->data['movies'] as $movie) {
            // assign movie id to an array
            $movie_ids[] = $movie->movie_id;
        }
        $movie_details = $this->getMoviesByIds($movie_ids);

        $this->data['movies'] = $movie_details;

    }

    public function getUserDates() {
        $dates = [];
        foreach ($this->data['shows'] as $show) {
            $next = $show['next_episode_to_air'];
            if (!$next) { continue; }
            $dates[] = [
                'type' => 'show',
                'date' => $next['air_date'], 
                'name' => $next['name'], 
                'ep_number' => $next['episode_number'], 
                'ep_season_number' => $next['season_number'], 
                'show_name' => $show['name'], 
                'id' => $show['id'], 
                'image' => $show['poster_path'], 
            ];
        }
        
        foreach ($this->data['movies'] as $movie) {
            $release = $movie['release_date'];
            if ($release) {
                $dates[] = [
                    'type' => 'movie',
                    'date' => $release,
                    'name' => $movie['title'], 
                    'id' => $movie['id'], 
                    'image' => $movie['poster_path'], 
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

    public function getMoviesByIds($ids)
    {
        $key = $this->tmdbkey;
        $url = "https://api.themoviedb.org/3/movie/";
        $client = new Client(['base_uri' => $url]);
        $movies = [];

        $requestGenerator = function ($ids) use ($client, $key) {
            foreach ($ids as $id) {
                yield $id => function () use ($client, $id, $key) {
                    return $client->getAsync("{$id}?api_key={$key}");
                };
            }
        };

        $pool = new Pool($client, $requestGenerator($ids), [
            // this is a trial-error number, you can change it to whatever you want, but check the actual request times
            'concurrency' => 3,
            'fulfilled' => function (Response $response, $index) use (&$movies) {
                $data = json_decode((string)$response->getBody(), true);
                $movies[] = $data;

            },
            'rejected' => function (Exception $reason, $index) {
                // this is delivered each failed request
                echo "Requested search term: ", $index, "\n";
                echo $reason->getMessage(), "\n\n";
            },
        ]);
        $promise = $pool->promise();
        $promise->wait();
        
        return $movies;
    }

    public function getMovieById($id)
    {
        $key = $this->tmdbkey;
        $url = "https://api.themoviedb.org/3/movie/";
        $client = new Client(['base_uri' => $url]);
        
        $movie = $client->get("{$id}?api_key={$key}");
     
        return $movie;
    }

    public function getShowsByIds($ids)
    {
        $key = $this->tmdbkey;
        $url = "https://api.themoviedb.org/3/tv/";
        $client = new Client(['base_uri' => $url]);
        $shows = [];

        $requestGenerator = function ($ids) use ($client, $key) {
            foreach ($ids as $id) {
                yield $id => function () use ($client, $id, $key) {
                    return $client->getAsync("{$id}?api_key={$key}");
                };
            }
        };

        $pool = new Pool($client, $requestGenerator($ids), [
            // this is a trial-error number, you can change it to whatever you want, but check the actual request times
            'concurrency' => 3,
            'fulfilled' => function (Response $response, $index) use (&$shows) {
                $data = json_decode((string)$response->getBody(), true);
                $shows[] = $data;
            },
            'rejected' => function ($reason, $index) {
                // this is delivered each failed request
                report($reason);
                return back()->with('message', 'Something went wrong when looking for your shows. Try again later or contact me.');
            },
        ]);
        $promise = $pool->promise();
        $promise->wait();
        
        return $shows;
    }

    public function getShowById($id)
    {
        $key = $this->tmdbkey;
        $url = "https://api.themoviedb.org/3/tv/";
        $client = new Client(['base_uri' => $url]);
        
        $show = $client->get("{$id}?api_key={$key}");
     
        return $show;
    }
}
