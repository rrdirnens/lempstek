<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http as Client;
use Illuminate\Http\Request;
use stdClass;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $tmdbkey;

    public function __construct() {
        $this->tmdbkey = config('tmdb.key') ?? null;
    }

    public function home() {
        return view('home');
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

        // dd($results);
        if (empty($results->tv) && empty($results->movies)) {
            $this->data['search_results'] = null;
            $this->data['search_msg'] = 'Sorry, no result found. Try again.';
        } else {
            $this->data['search_results'] = $results;
            $this->data['search_msg'] = '';
        } 
        
        return view('home', $this->data);
    }
}
