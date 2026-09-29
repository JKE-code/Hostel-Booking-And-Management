<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\HostelController;
use App\Http\Controllers\Public\PageController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\StudentOnboardingController;
use App\Http\Controllers\Admin\AdminPortalController;
use App\Http\Controllers\Warden\WardenPortalController;
use App\Http\Controllers\Security\SecurityGateController;
use App\Http\Controllers\Student\StudentPortalController;

/*
|--------------------------------------------------------------------------
| Public Information Routes
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
| Authentication & 2-Way Verification Onboarding
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit')->middleware('throttle:5,1');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Brevo OTP Password Reset Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password/send-otp', [AuthController::class, 'sendPasswordResetOtp'])->name('password.send-otp')->middleware('throttle:3,1');
Route::get('/reset-password', [AuthController::class, 'showResetPasswordForm'])->name('password.reset.form');
Route::post('/reset-password', [AuthController::class, 'resetPasswordWithOtp'])->name('password.update')->middleware('throttle:5,1');

// Student 2-Way Verification (Warden Whitelisted -> 6-Digit OTP -> Password Setup)
Route::get('/signup', [StudentOnboardingController::class, 'showSignupForm'])->name('signup');
Route::post('/signup/request-otp', [StudentOnboardingController::class, 'requestOtp'])->name('signup.request-otp')->middleware('throttle:3,1');
Route::post('/signup/verify-otp', [StudentOnboardingController::class, 'verifyOtp'])->name('signup.verify-otp')->middleware('throttle:5,1');
Route::post('/signup/complete', [StudentOnboardingController::class, 'completeRegistration'])->name('signup.complete');
Route::get('/signup/reset', [StudentOnboardingController::class, 'resetWizard'])->name('signup.reset');

/*
|--------------------------------------------------------------------------
| 1. Root Administrator Portal Gateway (Level 1 - Root Admin Only)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/administrators', [AdminPortalController::class, 'administrators'])->name('administrators');
    Route::post('/administrators', [AdminPortalController::class, 'storeAdmin'])->name('administrators.store');
    Route::get('/wardens', [AdminPortalController::class, 'wardens'])->name('wardens');
    Route::post('/wardens', [AdminPortalController::class, 'storeWarden'])->name('wardens.store');
    Route::post('/users/{user}/toggle', [AdminPortalController::class, 'toggleUserStatus'])->name('users.toggle');
    Route::get('/security', [AdminPortalController::class, 'securityStaff'])->name('security');
    Route::post('/security', [AdminPortalController::class, 'storeSecurityStaff'])->name('security.store');

    // Estate Management & Operations Oversight
    Route::get('/allocations', [AdminPortalController::class, 'allocations'])->name('allocations');
    Route::get('/students', [AdminPortalController::class, 'students'])->name('students');
    Route::post('/students', [AdminPortalController::class, 'storeStudent'])->name('students.store');
    Route::put('/students/{student}', [AdminPortalController::class, 'updateStudent'])->name('students.update');
    Route::post('/students/{student}/documents', [AdminPortalController::class, 'uploadStudentDocuments'])->name('students.documents');
    Route::get('/leaves', [AdminPortalController::class, 'leaves'])->name('leaves');
    Route::get('/complaints', [AdminPortalController::class, 'complaints'])->name('complaints');

    // Inspect Lower Tiers (Seamless in Admin layout with live operational data)
    Route::get('/inspect/warden', [AdminPortalController::class, 'inspectWarden'])->name('inspect.warden');
    Route::get('/inspect/security', [AdminPortalController::class, 'inspectSecurity'])->name('inspect.security');
    Route::get('/inspect/student', [AdminPortalController::class, 'inspectStudent'])->name('inspect.student');
});

/*
|--------------------------------------------------------------------------
| 2. Warden Portal Gateway (Level 2 - Operations, Whitelisting, Leaves)
|--------------------------------------------------------------------------
*/
Route::prefix('warden')->name('warden.')->middleware(['auth', 'role:warden'])->group(function () {
    Route::get('/dashboard', [WardenPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/students', [WardenPortalController::class, 'students'])->name('students');
    Route::post('/students/whitelist', [WardenPortalController::class, 'storeStudentWhitelist'])->name('students.whitelist');
    Route::get('/outpasses', [WardenPortalController::class, 'outpasses'])->name('outpasses');
    Route::post('/outpasses/{outpass}/approve', [WardenPortalController::class, 'approveOutpass'])->name('outpasses.approve');
    Route::post('/outpasses/{outpass}/reject', [WardenPortalController::class, 'rejectOutpass'])->name('outpasses.reject');
    Route::get('/complaints', [WardenPortalController::class, 'complaints'])->name('complaints');
    Route::post('/complaints/{complaint}/status', [WardenPortalController::class, 'updateComplaint'])->name('complaints.status');
    Route::get('/allocations', [WardenPortalController::class, 'allocations'])->name('allocations');
});

/*
|--------------------------------------------------------------------------
| 3. Security / Main Gate Desk Gateway (Level 3 - Gate Monitor, Verification)
|--------------------------------------------------------------------------
*/
Route::prefix('security')->name('security.')->middleware(['auth', 'role:security,admin'])->group(function () {
    Route::get('/dashboard', [SecurityGateController::class, 'dashboard'])->name('dashboard');
    Route::post('/outpasses/{outpass}/entry', [SecurityGateController::class, 'recordEntry'])->name('outpasses.entry');
    Route::get('/visitors', [SecurityGateController::class, 'visitors'])->name('visitors');
    Route::post('/visitors', [SecurityGateController::class, 'storeVisitor'])->name('visitors.store');
    Route::post('/visitors/{visitor}/check-in', [SecurityGateController::class, 'checkInVisitor'])->name('visitors.check-in');
    Route::post('/visitors/{visitor}/check-out', [SecurityGateController::class, 'checkOutVisitor'])->name('visitors.check-out');
});

/*
|--------------------------------------------------------------------------
| 4. Student Resident Portal Gateway (Level 4 - Personal Room & Leaves)
|--------------------------------------------------------------------------
*/
Route::prefix('student')->name('student.')->middleware(['auth', 'role:student'])->group(function () {
    Route::get('/dashboard', [StudentPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/room', [StudentPortalController::class, 'room'])->name('room');
    Route::get('/leaves', [StudentPortalController::class, 'leaves'])->name('leaves');
    Route::post('/leaves', [StudentPortalController::class, 'storeLeave'])->name('leaves.store');
    Route::get('/complaints', [StudentPortalController::class, 'complaints'])->name('complaints');
    Route::post('/complaints', [StudentPortalController::class, 'storeComplaint'])->name('complaints.store');
    Route::get('/mess', [StudentPortalController::class, 'mess'])->name('mess');
    Route::get('/profile', [StudentPortalController::class, 'profile'])->name('profile');
});
