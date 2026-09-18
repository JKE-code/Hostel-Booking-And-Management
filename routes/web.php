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

use App\Http\Controllers\Student\StudentPortalController;
use App\Http\Controllers\Admin\AdminPortalController;

/*
|--------------------------------------------------------------------------
| Authentication Routes (Preview Mode)
|--------------------------------------------------------------------------
*/
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/signup', function () {
    return view('auth.signup');
})->name('signup');

/*
|--------------------------------------------------------------------------
| Student Portal Routes
|--------------------------------------------------------------------------
*/
Route::prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/room', [StudentPortalController::class, 'room'])->name('room');
    Route::get('/leaves', [StudentPortalController::class, 'leaves'])->name('leaves');
    Route::get('/complaints', [StudentPortalController::class, 'complaints'])->name('complaints');
    Route::get('/mess', [StudentPortalController::class, 'mess'])->name('mess');
    Route::get('/profile', [StudentPortalController::class, 'profile'])->name('profile');
});

/*
|--------------------------------------------------------------------------
| Warden & Admin Management Portal Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/allocations', [AdminPortalController::class, 'allocations'])->name('allocations');
    Route::get('/leaves', [AdminPortalController::class, 'leaves'])->name('leaves');
    Route::get('/complaints', [AdminPortalController::class, 'complaints'])->name('complaints');
});

