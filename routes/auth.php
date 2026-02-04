<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Rotte per utenti guest
Route::middleware('guest')->group(function () {
    // Login
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    // POST login con remember automatico
    Route::post('login', function (Request $request) {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials, true)) { // remember automatico
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'username' => 'Le credenziali non sono corrette.',
        ]);
    })->name('login');
});
