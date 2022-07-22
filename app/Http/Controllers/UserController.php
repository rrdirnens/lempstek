<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ShowUser;
use App\Models\MovieUser;
use Illuminate\Http\Request;

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
}
