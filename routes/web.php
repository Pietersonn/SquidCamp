<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// --- AUTH CONTROLLERS ---
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\GoogleAuthController;

// --- ADMIN CONTROLLERS ---
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\EventGroupController;
use App\Http\Controllers\Admin\EventMentorController;
use App\Http\Controllers\Admin\EventInvestorController;
use App\Http\Controllers\Admin\EventChallengeController;
use App\Http\Controllers\Admin\EventCaseController;
use App\Http\Controllers\Admin\EventGuidelineController;
use App\Http\Controllers\Admin\ChallengeController;
use App\Http\Controllers\Admin\GuidelineController;
use App\Http\Controllers\Admin\CaseController;

// --- ROLE CONTROLLERS ---
use App\Http\Controllers\Mentor\MentorDashboardController;
use App\Http\Controllers\Investor\InvestorDashboardController;
use App\Http\Controllers\Main\MainDashboardController;
use App\Http\Controllers\Main\OnboardingController;
use App\Http\Controllers\Main\ChallengeController as MainChallengeController; // Tambahkan Alias biar beda sama Admin
use App\Http\Controllers\LandingPageController;

/*
|--------------------------------------------------------------------------
| 1. PUBLIC ROUTES (LANDING PAGE)
|--------------------------------------------------------------------------
*/

Route::get('/', [LandingPageController::class, 'index'])->name('landing');

/*
|--------------------------------------------------------------------------
| 2. AUTH ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| 3. ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->as('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('/challenges', ChallengeController::class);
    Route::resource('/guidelines', GuidelineController::class);
    Route::resource('/cases', CaseController::class);
    Route::resource('/users', UserController::class);
    Route::resource('/events', EventController::class);
    Route::post('/events/{event}/toggle-active', [EventController::class, 'toggleActive'])->name('events.toggleActive');

    Route::prefix('events/{event}')->as('events.')->group(function () {
        Route::resource('groups', EventGroupController::class);
        Route::resource('mentors', EventMentorController::class);
        Route::resource('investors', EventInvestorController::class);
        Route::resource('challenges', EventChallengeController::class);
        Route::resource('guidelines', EventGuidelineController::class);
        Route::resource('cases', EventCaseController::class);
    });
});

/*
|--------------------------------------------------------------------------
| 4. MENTOR & INVESTOR ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('mentor')->as('mentor.')->middleware(['auth', 'role:mentor'])->group(function () {
    Route::get('/dashboard', [MentorDashboardController::class, 'index'])->name('dashboard');
});

Route::prefix('investor')->as('investor.')->middleware(['auth', 'role:investor'])->group(function () {
    Route::get('/dashboard', [InvestorDashboardController::class, 'index'])->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| 5. USER (MAIN) ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:user'])->group(function () {

    // A. BRIDGE JOIN EVENT
    Route::get('/event/{event}/join', [OnboardingController::class, 'joinEvent'])->name('main.event.join');

    // B. ONBOARDING
    Route::get('/event/{event}/onboarding', [OnboardingController::class, 'showForm'])->name('main.onboarding.form');
    Route::post('/event/{event}/onboarding', [OnboardingController::class, 'store'])->name('main.onboarding.store');

    // C. DASHBOARD & GAMEPLAY
    Route::prefix('main')
         ->as('main.')
         ->middleware([App\Http\Middleware\CheckEventMembership::class])
         ->group(function () {

             // 1. Dashboard Utama
             Route::get('/dashboard', [MainDashboardController::class, 'index'])->name('dashboard');

             // 2. Challenges (Lomba Harian) - INI YANG TADI ERROR
             Route::get('/challenges', [MainChallengeController::class, 'index'])->name('challenges.index');

             // 3. Leaderboard (Rank) - Placeholder route agar tidak error jika nanti diklik
             Route::get('/leaderboard', function() { return "Halaman Leaderboard"; })->name('leaderboard.index');

         });
});
