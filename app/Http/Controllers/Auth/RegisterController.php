<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $user=User::create([
            'name'=>$request->name,
            'slug'=>$this->generateUniqueSlug($request->name),
            'email'=>$request->email,
            'password'=>$request->password,
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        $user->update([
            'last_login_at'=>now(),
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success','Registration successful! You are now logged in.');
    }

    private function generateUniqueSlug(string $name): string
    {
        $slug=Str::slug($name);
        $original=$slug;
        $counter=1;

        while(User::where('slug',$slug)->exists()){
            $slug=$original.'-'.$counter++;
        }

        return $slug;
    }
}