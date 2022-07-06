<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Show Register/create form (user)
    public function create() {
        return view('users.register');
    }

    // Create new (user)
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
        return redirect('/')->with('success', 'You are now logged in!');
    }
}
