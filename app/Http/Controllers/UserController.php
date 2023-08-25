<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Register Users

    public function create() {
        return view('users.register');
    }

    public function store(Request $request) {

        $formFields = $request->validate([
            'name' => ['required', 'min:4'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'confirmed', 'min:6']
        ]);

        
        // Hash Password
        $formFields['password'] = bcrypt($formFields['password']);

        // create User
        $user = User::create($formFields);

        // login
        auth()->login($user);

        return redirect('/')->with('message', 'User Registered Successfully');


    }

    public function logout(Request $request) {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('message', 'You have been Logged Out');
    }

    // login form dispaly function

    public function login() {
        return view('users.login');
        
    }

    public function authenticate(Request $request){
        $formFields = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6']
        ]);

        if(auth()->attempt($formFields)){
            $request->session()->regenerate();
            return redirect('/')->with('message', 'You are now Logged In...');
        }
        return back()->withErrors(['email' => 'Invalid Credentials'])->onlyInput('email');
    }
}
