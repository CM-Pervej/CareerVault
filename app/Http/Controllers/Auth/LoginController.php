<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials=$request->validate([
            'email'=>['required','email'],
            'password'=>['required'],
        ]);

        if(!Auth::validate($credentials)){
            return back()
                ->withErrors([
                    'email'=>'The provided credentials do not match our records.',
                ])
                ->onlyInput('email');
        }

        $user=User::where('email',$credentials['email'])->first();

        if(!$user){
            return back()
                ->withErrors([
                    'email'=>'The provided credentials do not match our records.',
                ])
                ->onlyInput('email');
        }

        if($user->status!=='active'){
            return back()
                ->withErrors([
                    'email'=>'Your account is inactive. Please contact the administrator.',
                ])
                ->onlyInput('email');
        }

        Auth::login($user,$request->boolean('remember'));

        $request->session()->regenerate();

        $user->update([
            'last_login_at'=>now(),
        ]);

        if($user->isAdmin()){
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}