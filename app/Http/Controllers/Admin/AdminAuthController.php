<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->hasRole('admin')) {
            return redirect()->route('admin.stories.index');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Invalid credentials.']);
        }

        if (! Auth::user()->hasRole('admin')) {
            Auth::logout();
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Admin access only.']);
        }

        $request->session()->regenerate();
        return redirect()->intended(route('admin.stories.index'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
