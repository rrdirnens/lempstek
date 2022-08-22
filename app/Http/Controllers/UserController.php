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

        $formFields = $this->validate($request, [
            'name' => 'required|max:255|min:4',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $formFields['password'] = bcrypt($formFields['password']);

        $user = new User;
        $user->fill($formFields);
        $user->save();

        auth()->login($user);

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

        $formFields = $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

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

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/')->with('message', 'You are now logged out!');
    }

    /**
     * Show the user profile view
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {

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
                // dd($detail);
                return $detail;
            }); 
            
            // calculate how many days left until each date
            $daysLeft = $this->daysLeft($date);
            $dates[$date] = collect($dates[$date])->map(function($detail) use ($daysLeft) {
                $detail['days_left'] = $daysLeft;
                return $detail;
            }); 

        }

        // $dates items keys are actual dates (YYYY-MM-DD) and values are arrays of show/movie details. Remove dates which are older than 1 week.
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
        
        $this->data['dates'] = $dates;

        // dump($this->data, $dates);
        
        return view('users.profile', $this->data);
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
    
    /**
     * Add TV show to user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addTvShow(Request $request, $id) {
        $user = auth()->user();
        $show = $id;
        
        $showRequest = $this->getShowById($request, $show);

        if ($showRequest->status() !== 200) {
            return back()->withErrors(['user_show' => 'Show not found!']);
        }
    
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
        
        $movieRequest = $this->getMovieById($request, $movie);

        if ($movieRequest->status() !== 200) {
            return back()->withErrors(['user_movie' => 'Movie not found!']);
        }

        $userMovie = new MovieUser;
        $userMovie->user_id = $user->id;
        $userMovie->movie_id = $movie;
        
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
