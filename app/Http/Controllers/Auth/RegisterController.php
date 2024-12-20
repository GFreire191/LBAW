<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\View\View;

use App\Models\User;

class RegisterController extends Controller
{
    /**
     * Display a login form.
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Register a new user.
     */
    public function register(Request $request)
    {

        
        
        $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:8|confirmed',
            'profile_picture_id' => 'sometimes|image|max:2048'

        ]);

        if($request->hasFile('profile_picture_id')) {
            $profile_picture = $request->file('profile_picture_id');
            $profile_picture_name = $request->name . '.' . $profile_picture->getClientOriginalExtension();
            $profile_picture->storeAs('profile_pictures', $profile_picture_name, 'public');
            $profile_picture_path = $profile_picture_name;
        } else {
            $profile_picture_path = 'user_default.jpg';
        }

        



        User::create([
            'username' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'bio' => $request->bio ?? '',
            'profile_picture_id' => $profile_picture_path
        ]);

        
        \Log::info('User registered:');
        $credentials = $request->only('email', 'password');
        Auth::attempt($credentials);
        $request->session()->regenerate();
        return redirect()->route('home')
            ->withSuccess('You have successfully registered & logged in!');
    }
}
