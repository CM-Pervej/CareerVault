<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function showRegister(){
        return view('auth.register');
    }

    public function register(Request $request){
        $validated =$request->validate([
            'name'  => ['required','string','max:255'],
            'email' => ['required','string','email','max:255','unique:users,email'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols(),
            ],
        ]);

        $user = User::create($validated);

        // Automatically Log in user after registration
        Auth::login($user);

        // Prevent Session Fixation
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Registration successful! you are now logged in.');
    }
}
