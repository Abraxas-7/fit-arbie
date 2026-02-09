<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Volt;

Route::middleware('web')->group(function () {
    Route::get('/', function () {
        return Auth::check()
            ? redirect()->intended('/dashboard')
            : redirect()->route('login');
    });
});

Volt::route('/dashboard', 'app-shell')->middleware(['auth'])->name('dashboard');

// Auth routes minimal
require __DIR__ . '/auth.php';
