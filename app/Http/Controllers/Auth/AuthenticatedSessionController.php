<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show login page
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle login
     */
  public function store(LoginRequest $request): RedirectResponse
{
    $credentials = $request->only('email', 'password');

    // 🔍 Find user BEFORE login
    $user = \App\Models\User::where('email', $credentials['email'])->first();

    // 🔒 Block if an active session already exists
    if ($user) {
        $activeSession = DB::table('sessions')
            ->where('user_id', $user->id)
            ->where(
                'last_activity',
                '>=',
                now()->subMinutes(config('session.lifetime'))->timestamp
            )
            ->exists();

        if ($activeSession) {
            return back()->withErrors([
                'email' => 'This account is already logged in on another device.'
            ]);
        }
    }

    // ❌ Invalid credentials
    if (!Auth::attempt($credentials)) {
        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    // 🔁 Regenerate session
    $request->session()->regenerate();

    // 🔥 THIS IS THE KEY LINE (bind user to session)
    DB::table('sessions')
        ->where('id', session()->getId())
        ->update(['user_id' => Auth::id()]);

    return match (Auth::user()->role) {
        'admin'    => redirect()->route('admin.dashboard'),
        'salesman' => redirect()->route('salesman.dashboard'),
        default    => redirect('/dashboard'),
    };
}


    /**
     * Logout
     */
   public function destroy(Request $request): RedirectResponse
{
    if ($user = Auth::user()) {
        $user->session_id = null;
        $user->save();
    }

    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
}

}
