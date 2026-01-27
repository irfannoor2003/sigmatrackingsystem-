<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();

    $user = Auth::user();

    // Check if user is already logged in
    if ($user->session_id && $user->session_id !== $request->session()->getId()) {
        Auth::guard('web')->logout(); // log out current attempt
        return redirect()->back()->withErrors([
            'email' => 'This user is already logged in from another device.'
        ]);
    }

    // Regenerate session & store session_id
    $request->session()->regenerate();
    $user->session_id = $request->session()->getId();
    $user->save();

    // Redirect based on role
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'salesman') {
        return redirect()->route('salesman.dashboard');
    }

    return redirect('/dashboard'); // fallback
}


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
{
    $user = Auth::user();
    if ($user) {
        $user->session_id = null;
        $user->save();
    }

    Auth::guard('web')->logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
}

    protected function authenticated($request, $user)
{
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'salesman') {
        return redirect()->route('salesman.dashboard');
    }

    return redirect('/'); // fallback
}

}
