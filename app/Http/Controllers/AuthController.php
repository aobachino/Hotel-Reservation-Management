<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function postLogin(PostLoginRequest $request)
    {
        // Log the login attempt
        Log::info('Login attempt', [
            'email' => $request->email,
            'ip' => $request->ip(),
            'time' => now()
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            // Log successful login
            Log::info('User authenticated successfully', [
                'user_id' => auth()->user()->id,
                'ip' => $request->ip(),
                'time' => now()
            ]);

            return redirect('dashboard')->with('success', 'Welcome ' . auth()->user()->name);
        } else {
            // Log failed login attempt
            Log::warning('Failed login attempt', [
                'email' => $request->email,
                'ip' => $request->ip(),
                'time' => now()
            ]);
        }

        return redirect('login')->with('failed', 'Incorrect email / password');
    }

    public function logout()
    {
        $user = auth()->user();
        if ($user) {
            $name = $user->name;
            $userId = $user->id;
            $ip = request()->ip();

            // Log the logout event
            Log::info('User logged out', [
                'user_id' => $userId,
                'name' => $name,
                'ip' => $ip,
                'time' => now()
            ]);

            Auth::logout();

            return redirect('login')->with('success', 'Logout success, goodbye ');
        }

        return redirect('login');
    }
}
