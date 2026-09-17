<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\HomeController;

use App\Http\Controllers\Public\HostelController;
use App\Http\Controllers\Public\PageController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('public.about');

Route::prefix('hostels')->name('public.hostels.')->group(function () {
    Route::get('/boys', [HostelController::class, 'boys'])->name('boys');
    Route::get('/girls', [HostelController::class, 'girls'])->name('girls');
    Route::get('/new-boys', [HostelController::class, 'newBoys'])->name('new-boys');
});

Route::get('/facilities', [PageController::class, 'facilities'])->name('public.facilities');
Route::get('/mess', [PageController::class, 'mess'])->name('public.mess');
Route::get('/rules', [PageController::class, 'rules'])->name('public.rules');
Route::get('/notices', [PageController::class, 'notices'])->name('public.notices');
Route::get('/events', [PageController::class, 'events'])->name('public.events');
Route::get('/gallery', [PageController::class, 'gallery'])->name('public.gallery');
Route::get('/downloads', [PageController::class, 'downloads'])->name('public.downloads');
Route::get('/faq', [PageController::class, 'faq'])->name('public.faq');
Route::get('/contact', [PageController::class, 'contact'])->name('public.contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('public.contact.submit');

/*
|--------------------------------------------------------------------------
| Authentication Routes Placeholder
|--------------------------------------------------------------------------
*/
Route::get('/login', function () {
    return redirect()->route('home')->with('info', 'Login authentication will be configured with roles & permissions.');
})->name('login');
