<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ShowUser;
use App\Models\MovieUser;
use App\Traits\GetShowTrait;
use App\Traits\GetMovieTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    use GetMovieTrait, GetShowTrait;

    /**
     * Show the user Register/create view
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('users.register');
    }

    /**
     * Store a newly created user in db.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        // Validate the request
        $formFields = $this->validate($request, [
            'name' => 'required|max:255|min:4',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        // Hash PW
        $formFields['password'] = bcrypt($formFields['password']);

        // Create a new user
        $user = new User;
        $user->fill($formFields);
        $user->save();

        // login
        auth()->login($user);

        // Redirect to home page
        return redirect('/')->with('message', 'You are now logged in!');
    }

    /**
     * Show the user Login view
     *
     * @return \Illuminate\Http\Response
     */
    public function login() {
        return view('users.login');
    }

    /**
     * Login a user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request) {

        // Validate the request
        $formFields = $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to login
        if (auth()->attempt($formFields)) {
            $request->session()->regenerate();
            return redirect('/')->with('message', 'You are now logged in!');
        } else {
            return back()->withErrors(['email' => 'Invalid credentials'])->onlyInput('email');
        }
    }

    /**
     * Logout a user
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request) {
        auth()->logout();

        // invalidate session
        $request->session()->invalidate();

        //regenerate token  
        $request->session()->regenerateToken();

        return redirect('/')->with('message', 'You are now logged out!');
    }

    /**
     * Show the user profile view
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {

        // check if authorized user is trying to view profile
        if (!auth()->check() && auth()->user()->id != $id) {
            return back()->withErrors(['user' => 'You entered the wrong user ID! We sent you back to YOUR profile.']);
        }

        $user = auth()->user();
        $shows = ShowUser::where('user_id', $user->id)->get();
        $movies = MovieUser::where('user_id', $user->id)->get();
        
        $this->data['user'] = $user;
        
        $this->data['shows'] = json_decode($shows);
        foreach ($this->data['shows'] as $show) {
            $show->details = $this->getShowById($request, $show->show_id)->object();
        }

        $this->data['movies'] = json_decode($movies);
        foreach ($this->data['movies'] as $movie) {
            $movie->details = $this->getMovieById($request, $movie->movie_id)->object();
        }

        // a collection of dates (movie release dates, tv show next episode dates, tv show previous episodes dates) group by date
        
        $dates = [];
        
        // temporary just to have a duplicate date
        $dates[] = [
            'date' => "2022-10-02",
            'details' => [
                'type' => 'show',
                "ep_date" => "2022-10-02",
                "ep_name" => "Episode 3h35",
                "ep_num" => 153,
                "ep_seas_num" => 3411,
                "show_name" => "The Walkewvwtbting Dead",
                "show_id" => "143452",
                "show_image" => "/xf9wuDcqlUPWABZNeDKPbZUjWx0.jpg"
            ]
        ];

        foreach ($this->data['shows'] as $show) {
            $next = $show->details->next_episode_to_air;
            if (!$next) { continue; }
            if ($this->checkDatePresence($dates, $next->air_date) === false) {
                $dates[] = [
                    'date' => $next->air_date,
                    'details' => [
                        'type' => 'show',
                        'ep_date' => $next->air_date, 
                        'ep_name' => $next->name, 
                        'ep_num' => $next->episode_number, 
                        'ep_seas_num' => $next->season_number, 
                        'show_name' => $show->details->name, 
                        'show_id' => $show->show_id, 
                        'show_image' => $show->details->poster_path, 
                    ]
                ];
            }
        }
        foreach ($this->data['movies'] as $movie) {
            $release = $movie->details->release_date;
            if ($release) {
                $dates[] = [
                    'date' => $release,
                    'details' => [
                        'type' => 'movie',
                        'movie_release_date' => $release, 
                        'movie_name' => $movie->details->title, 
                        'movie_id' => $movie->movie_id, 
                        'movie_image' => $movie->details->poster_path, 
                    ]
                ];
            }
        }

        // $dates = array_unique($dates);
        sort($dates);
        $this->data['dates'] = $dates;

        // dump($this->data);
        return view('users.profile', $this->data);
    }

    /**
     * Check if dates array already contains the date
     *
     * @return \Illuminate\Http\Response
     */
    public function checkDatePresence($dates, $date) {
        dump('fuck', $dates, $date, 'a duck');
        foreach ($dates as $d) {
            if ($d['date'] == $date) {
                return true;
            }
        }
        return false;
    }

    /**
     * Add TV show to user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addTvShow(Request $request, $id) {
        $user = auth()->user();
        $show= $id;
        
        // new user show entry to the join table for user shows
        $userShow = new ShowUser;
        $userShow->user_id = $user->id;
        $userShow->show_id = $show;
        
        // check for duplicate entries
        $duplicate = ShowUser::where('user_id', $user->id)->where('show_id', $show)->first();
        
        if ($duplicate) {
            return back()->withErrors(['user_show' => 'You already have this show!']);
        } else {
            $userShow->save();
            return back()->with('message', 'Show added!');
        }

        dd($show, $user, $userShow);
        return redirect('/')->with('message', 'TV show added!');
    }
    /**
     * Remove TV show from user
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function removeTvShow($id) {
        $user = auth()->user();

        // delete user show entry from the join table for user shows
        $userShow = ShowUser::where('user_id', $user->id)->where('show_id', $id)->first();
        $userShow->delete();
        
        return back()->with('message', 'Show removed!');
    }

    /**
     * Add movie to user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addMovie(Request $request, $id) {
        $user = auth()->user();
        $movie= $id;
        
        // new user movie entry to the join table for user movies
        $userMovie = new MovieUser;
        $userMovie->user_id = $user->id;
        $userMovie->movie_id = $movie;
        
        // check for duplicate entries
        $duplicate = movieUser::where('user_id', $user->id)->where('movie_id', $movie)->first();
        
        if ($duplicate) {
            return back()->withErrors(['user_movie' => 'You already have this movie!']);
        } else {
            $userMovie->save();
            return back()->with('message', 'Movie added!');
        }

        return redirect('/')->with('message', 'Movie added!');
    }

    /**
     * Remove Movie from user
     *
     * @return \Illuminate\Http\Response
     */
    public function removeMovie($id) {
        $user = auth()->user();
        
        // delete user movie entry to the join table for user movies
        $userMovie = MovieUser::where('user_id', $user->id)->where('movie_id', $id)->first();
        $userMovie->delete();
        
        return back()->with('message', 'Movie removed!');
    }
}
