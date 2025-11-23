<?php

use Illuminate\Support\Facades\Route;

// AUTH
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\GoogleAuthController;

// ADMIN
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\ChallengeController;     // Master
use App\Http\Controllers\Admin\GuidelineController;     // Master
use App\Http\Controllers\Admin\EventGroupController;
use App\Http\Controllers\Admin\EventMentorController;
use App\Http\Controllers\Admin\EventInvestorController;
use App\Http\Controllers\Admin\EventChallengeController; // Pivot
use App\Http\Controllers\Admin\EventCaseController;
use App\Http\Controllers\Admin\EventGuidelineController; // Pivot

// ROLE LAIN
use App\Http\Controllers\Mentor\MentorDashboardController;
use App\Http\Controllers\Investor\InvestorDashboardController;
use App\Http\Controllers\Main\MainDashboardController;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => redirect()->route('login'));

Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');

Route::get('/register', [RegisterController::class, 'index'])->name('register')->middleware('guest');
Route::post('/register', [RegisterController::class, 'register'])->middleware('guest');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Google Auth
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->as('admin.')->middleware(['auth', 'role:admin'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // MASTER DATA (Global)
    Route::resource('/challenges', ChallengeController::class);
    Route::resource('/guidelines', GuidelineController::class);

    // MANAJEMEN USERS
    Route::resource('/users', UserController::class);

    // MANAJEMEN EVENTS
    Route::resource('/events', EventController::class);
    Route::post('/events/{event}/toggle-active', [EventController::class, 'toggleActive'])->name('events.toggleActive');

    // EVENT SUB-MENU (Nested Resources)
    Route::prefix('events/{event}')->as('events.')->group(function () {

        Route::resource('groups', EventGroupController::class);
        Route::resource('mentors', EventMentorController::class);
        Route::resource('investors', EventInvestorController::class);

        // PER-EVENT CONFIGURATION
        // JANGAN pakai ->only() supaya create/edit/update bisa diakses
        Route::resource('challenges', EventChallengeController::class);
        Route::resource('guidelines', EventGuidelineController::class);

        Route::resource('cases', EventCaseController::class);
    });

});


/*
|--------------------------------------------------------------------------
| MENTOR ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('mentor')->as('mentor.')->middleware(['auth', 'role:mentor'])->group(function () {
    Route::get('/dashboard', [MentorDashboardController::class, 'index'])->name('dashboard');
});


/*
|--------------------------------------------------------------------------
| INVESTOR ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('investor')->as('investor.')->middleware(['auth', 'role:investor'])->group(function () {
    Route::get('/dashboard', [InvestorDashboardController::class, 'index'])->name('dashboard');
});


/*
|--------------------------------------------------------------------------
| USER (MAIN) ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('main')->as('main.')->middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard', [MainDashboardController::class, 'index'])->name('dashboard');
});
