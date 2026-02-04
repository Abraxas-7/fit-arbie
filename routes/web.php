<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Dashboard protetta
Route::get('/', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// Auth routes minimal
require __DIR__ . '/auth.php';
