<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// --- Controllers ---
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\GoogleAuthController;
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
use App\Http\Controllers\Mentor\MentorDashboardController;
use App\Http\Controllers\Investor\InvestorDashboardController;
use App\Http\Controllers\Main\MainDashboardController;
use App\Http\Controllers\Main\OnboardingController;
use App\Http\Controllers\Main\TransactionController;
use App\Http\Controllers\Main\LeaderboardController;
use App\Http\Controllers\Main\GroupController;
use App\Http\Controllers\Main\ChallengeController as MainChallengeController;
use App\Http\Controllers\Main\CaseController as MainCaseController;
use App\Http\Controllers\LandingPageController;

/*
|--------------------------------------------------------------------------
| PUBLIC & AUTH
|--------------------------------------------------------------------------
*/

Route::get('/', [LandingPageController::class, 'index'])->name('landing');

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
| ADMIN ROUTES
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
| MENTOR & INVESTOR
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
| USER (MAIN) ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user'])->group(function () {

  // Onboarding Routes
  Route::get('/onboarding', [OnboardingController::class, 'index'])->name('main.onboarding.index');
  Route::get('/event/{event}/join', [OnboardingController::class, 'joinEvent'])->name('main.event.join');
  Route::get('/event/{event}/onboarding', [OnboardingController::class, 'showForm'])->name('main.onboarding.form');
  Route::post('/event/{event}/onboarding', [OnboardingController::class, 'store'])->name('main.onboarding.store');

  // Main Dashboard & Features
  Route::middleware(['auth', 'role:user'])->group(function () {

    // Onboarding Routes
    Route::get('/onboarding', [OnboardingController::class, 'index'])->name('main.onboarding.index');
    Route::get('/event/{event}/join', [OnboardingController::class, 'joinEvent'])->name('main.event.join');
    Route::get('/event/{event}/onboarding', [OnboardingController::class, 'showForm'])->name('main.onboarding.form');
    Route::post('/event/{event}/onboarding', [OnboardingController::class, 'store'])->name('main.onboarding.store');

    // Main Dashboard & Features
    // Perhatikan: prefix 'main' dan as 'main.' sudah ada di sini
    Route::prefix('main')->as('main.')
      ->middleware([App\Http\Middleware\CheckEventMembership::class])
      ->group(function () {

        // Dashboard (Akan menjadi main.dashboard)
        Route::get('/dashboard', [MainDashboardController::class, 'index'])->name('dashboard');

        // Transfer Saldo
        Route::post('/transfer', [TransactionController::class, 'transfer'])->name('transaction.transfer');

        // Challenge
        // HAPUS 'main.' di dalam name(), cukup 'challenges.index'
        Route::get('/challenges', [MainChallengeController::class, 'index'])->name('challenges.index');
        Route::post('/challenges/take', [MainChallengeController::class, 'take'])->name('challenges.take');
        Route::post('/challenges/{submission}/submit', [MainChallengeController::class, 'store'])->name('challenges.store');

        // Cases (PERBAIKAN DI SINI)
        // HAPUS 'main.' di dalam name(), cukup 'cases.index'
        Route::get('/cases', [MainCaseController::class, 'index'])->name('cases.index');
        Route::post('/cases/buy-guideline', [MainCaseController::class, 'buyGuideline'])->name('cases.buyGuideline');
        Route::post('/cases/{id}/submit', [MainCaseController::class, 'submit'])->name('cases.submit');

        Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');
        Route::get('/team', [GroupController::class, 'index'])->name('group.index');
      });
  });
});
