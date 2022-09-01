<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ShowUser;
use App\Models\MovieUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Spatie\ResponseCache\Facades\ResponseCache;

class UserController extends Controller
{
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
    public function show($id) {

        if (!auth()->check()) {
            return redirect('/')->withErrors(['user' => 'Access denied. You are not logged in.']);
        } elseif (auth()->user()->id != $id) {
            return redirect('/')->withErrors(['user' => 'You entered the wrong user ID! We sent you back to YOUR profile.']);
        }

        $user = auth()->user();
        
        $this->data['user'] = $user;
                
        return view('users.profile', $this->data);
    }

    /**
     * Change day limit setting for user
     *
     * @return \Illuminate\Http\Response
     * @param  \Illuminate\Http\Request  $request
     */
    public function edit(Request $request) {
        $formFields = $this->validate($request, [
            'day_limit' => 'required|integer|min:0',
        ]);

        $user = DB::table('users')
                    ->where('id', auth()->user()->id);
        $user->update(['day_limit' => $formFields['day_limit']]);

        ResponseCache::clear();

        return back()->with('message', 'Your day limit has been updated!');
        
    }

    /**
     * Check if logged in user id matches url id
     *
     * @return \Illuminate\Http\Response
     */
    public function checkUserIdAgainstUrl($id) {
        if (auth()->user()->id != $id) return false;
        return true;
    }
    
    /**
     * Add TV show to user
     *
     * @return \Illuminate\Http\Response
     */
    public function addTvShow($id) {
        $user = auth()->user();
        $show = $id;
        
        $showRequest = $this->getShowById($show);

        if ($showRequest->getStatusCode() !== 200) {
            return back()->withErrors(['user_show' => 'Show not found!']);
        }

        // check for duplicate entries
        $duplicate = ShowUser::where('user_id', $user->id)->where('show_id', $show)->first();
        if ($duplicate) {
            return back()->withErrors(['user_show' => 'You already have this show in your list!']);
        }

        // new user show entry to the join table for user shows
        $userShow = new ShowUser;
        $userShow->user_id = $user->id;
        $userShow->show_id = $show;
        $userShow->save();

        ResponseCache::clear();

        return redirect()->back()->with('message', 'Show added!');
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
        
        ResponseCache::clear();

        return back()->with('message', 'Show removed!');
    }

    /**
     * Add movie to user
     *
     * @return \Illuminate\Http\Response
     */
    public function addMovie($id, Request $request) {
        $user = auth()->user();
        $movie= $id;
        
        $movieRequest = $this->getMovieById($movie);

        if ($movieRequest->getStatusCode() !== 200) {
            return back()->withErrors(['user_movie' => 'Movie not found!']);
        }
        
        // check for duplicate entries
        $duplicate = movieUser::where('user_id', $user->id)->where('movie_id', $movie)->first();

        if ($duplicate) {
            return back()->withErrors(['user_movie' => 'You already have this movie in your list!']);
        }

        // new user movie entry to the join table for user movies 
        $userMovie = new MovieUser;
        $userMovie->user_id = $user->id;
        $userMovie->movie_id = $movie;
        $userMovie->save();

        ResponseCache::clear();
        
        return redirect()->back()->with('message', 'Movie added!');
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

        ResponseCache::clear();

        return back()->with('message', 'Movie removed!');
    }
}
