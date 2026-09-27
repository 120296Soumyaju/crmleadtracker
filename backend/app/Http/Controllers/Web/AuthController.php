<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('leads.index'))
                ->with('success', 'Welcome back, '.Auth::user()->name.'! Logged in as '.ucfirst(str_replace('_', ' ', Auth::user()->role)));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out.');
    }

    /**
     * Helper action for quick demo role switching in machine test
     */
    public function switchRole(Request $request, string $role): RedirectResponse
    {
        $user = User::where('role', $role)->first();
        if ($user) {
            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->back()->with('success', 'Switched session to '.ucfirst(str_replace('_', ' ', $role)).' ('.$user->email.')');
        }

        return redirect()->back()->with('error', 'Role user not found.');
    }
}
