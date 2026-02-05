<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Volt;


Volt::route('/dashboard', 'app-shell')->middleware(['auth'])->name('dashboard');

// Auth routes minimal
require __DIR__ . '/auth.php';
