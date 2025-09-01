<?php

use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
})->middleware('auth')->name('home');

Route::get('/login', function () {
    return view(view: 'login');
})->name('login');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

Route::prefix('dashboard')->middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/user-management', function () {
        return view('user-management');
    })->name('dashboard.user-management');
    Route::get('/role-management', function () {
        return view('role-management');
    })->name('dashboard.role-management');
    Route::get('/consumption')->name('dashboard.consumption');
    Route::get('/maintenance')->name('dashboard.maintenance');
    Route::get('/agenda')->name('dashboard.agenda');
    Route::get('/chat')->name('dashboard.chat');
    Route::get('/logs')->name('dashboard.logs');
    Route::get('/settings')->name('dashboard.settings');
});