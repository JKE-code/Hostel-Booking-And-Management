<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\HomeController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/about', function () {
    return view('public.home'); // Placeholder route to be populated
})->name('public.about');

Route::prefix('hostels')->name('public.hostels.')->group(function () {
    Route::get('/boys', function () {
        return view('public.home');
    })->name('boys');
    
    Route::get('/girls', function () {
        return view('public.home');
    })->name('girls');
    
    Route::get('/new-boys', function () {
        return view('public.home');
    })->name('new-boys');
});

Route::get('/facilities', function () {
    return view('public.home');
})->name('public.facilities');

Route::get('/mess', function () {
    return view('public.home');
})->name('public.mess');

Route::get('/rules', function () {
    return view('public.home');
})->name('public.rules');

Route::get('/notices', function () {
    return view('public.home');
})->name('public.notices');

Route::get('/events', function () {
    return view('public.home');
})->name('public.events');

Route::get('/gallery', function () {
    return view('public.home');
})->name('public.gallery');

Route::get('/downloads', function () {
    return view('public.home');
})->name('public.downloads');

Route::get('/faq', function () {
    return view('public.home');
})->name('public.faq');

Route::get('/contact', function () {
    return view('public.home');
})->name('public.contact');

/*
|--------------------------------------------------------------------------
| Authentication Routes Placeholder
|--------------------------------------------------------------------------
*/
Route::get('/login', function () {
    return redirect()->route('home')->with('info', 'Login authentication will be configured with roles & permissions.');
})->name('login');
