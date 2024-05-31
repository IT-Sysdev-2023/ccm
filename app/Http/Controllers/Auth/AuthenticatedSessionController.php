<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Hash;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        // dd(1);
        $request->authenticate();

        $request->session()->regenerate();

        if (Auth::user()->usertype_id === 1) {
            return redirect(RouteServiceProvider::ADMIN_HOME);
        } else if (Auth::user()->usertype_id === 2) {
            return redirect(RouteServiceProvider::ACCOUNTING_HOME);
        } else if (Auth::user()->usertype_id === 9) {
            return redirect(RouteServiceProvider::TREASURY_HOME);
        } else {
            return redirect('notfound');
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

        return redirect()->route('login');
    }

    public function newPassword(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'password' => 'required|string|min:6',
        ]);

        $user = Auth::user();

        $user->password = Hash::make($request->password);

        $user->save();
    }
}
