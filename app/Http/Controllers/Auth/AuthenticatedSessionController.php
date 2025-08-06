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
        return view('auth.slide');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {

            // finish authentication
            $request->authenticate();

            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->email_verified_at != NULL) {
                if ($user->first_logged_in_at != NULL) {
                    return redirect()->intended(route('dashboard', absolute: false));
                }else {
                    $user->first_logged_in_at = now();
                    $user->save();
                    return redirect()->route('profile.set');
                }
            } else {
                Auth::logout();
                return redirect('/login')->withErrors([
                'email' => 'You do not finish email verification!',
                ]);
            }

    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
