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
use App\Http\Controllers\Admin\CaseSubmissionController;
use App\Http\Controllers\Admin\SquidBankController;
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

// --- Middlewares ---
use App\Http\Middleware\CheckEventMembership;
use App\Http\Middleware\CheckEventStatus;

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

    // Resource Utama
    Route::resource('/challenges', ChallengeController::class);
    Route::resource('/guidelines', GuidelineController::class);
    Route::resource('/cases', CaseController::class);
    Route::resource('/users', UserController::class);

    // Event Management Utama
    Route::resource('/events', EventController::class);
    Route::post('/events/{event}/toggle-active', [EventController::class, 'toggleActive'])->name('events.toggleActive');

    // Nested Resources (Detail Event)
    Route::prefix('events/{event}')->as('events.')->group(function () {

        // 1. Action Tombol (Start/Finish)
        Route::post('start', [EventController::class, 'startEvent'])->name('start');
        Route::post('finish', [EventController::class, 'finishEvent'])->name('finish');

        // 2. Sub-Modules
        Route::resource('groups', EventGroupController::class);
        Route::resource('mentors', EventMentorController::class);
        Route::resource('investors', EventInvestorController::class);
        Route::resource('challenges', EventChallengeController::class);
        Route::resource('guidelines', EventGuidelineController::class);
        Route::resource('cases', EventCaseController::class);

        // --- SQUID BANK SECTION ---
        Route::post('squidbank/topup', [SquidBankController::class, 'topup'])->name('squidbank.topup');
        Route::resource('squidbank', SquidBankController::class);
        // --------------------------

        Route::resource('case-submission', CaseSubmissionController::class);
    });
});

/*
|--------------------------------------------------------------------------
| MENTOR ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('mentor')->as('mentor.')->middleware(['auth', 'role:mentor'])->group(function () {

    // 1. Route Global: Halaman Pemilihan Event
    // Mentor masuk sini dulu setelah login
    Route::get('/select-event', [MentorDashboardController::class, 'selectEvent'])->name('select-event');

    // 2. Route Spesifik Event
    // URL: /mentor/events/{event_id}/...
    // [FIX] Menggunakan CheckEventMembership::class langsung, bukan string alias
    Route::prefix('events/{event}')->middleware(CheckEventMembership::class)->group(function () {

        // Dashboard Utama (Queue Review untuk Event ini)
        Route::get('/dashboard', [MentorDashboardController::class, 'index'])->name('dashboard');

        // Monitoring Group (Hanya group di Event ini)
        Route::get('/my-teams', [MentorDashboardController::class, 'myGroups'])->name('groups.index');
        Route::get('/my-teams/{id}', [MentorDashboardController::class, 'showGroup'])->name('groups.show');

        // Actions Approve/Reject
        Route::post('/submission/{id}/approve', [MentorDashboardController::class, 'approve'])->name('submission.approve');
        Route::post('/submission/{id}/reject', [MentorDashboardController::class, 'reject'])->name('submission.reject');
    });
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
Route::middleware(['auth', 'role:user'])->group(function () {

    // Onboarding (Bebas akses, tidak dicegat middleware status event)
    Route::get('/onboarding', [OnboardingController::class, 'index'])->name('main.onboarding.index');
    Route::get('/event/{event}/join', [OnboardingController::class, 'joinEvent'])->name('main.event.join');
    Route::get('/event/{event}/onboarding', [OnboardingController::class, 'showForm'])->name('main.onboarding.form');
    Route::post('/event/{event}/onboarding', [OnboardingController::class, 'store'])->name('main.onboarding.store');

    // --- MAIN FEATURES ---
    Route::prefix('main')->as('main.')->group(function () {

        // 1. Route Thanks Page
        Route::get('/thanks', [MainDashboardController::class, 'thanks'])->name('thanks');

        // 2. Fitur Dashboard & Game (Dijaga ketat)
        Route::middleware([CheckEventMembership::class, CheckEventStatus::class])->group(function () {

            Route::get('/dashboard', [MainDashboardController::class, 'index'])->name('dashboard');

            // Transaction
            Route::post('/transfer', [TransactionController::class, 'transfer'])->name('transaction.transfer');
            Route::post('/transaction/withdraw-from-bank', [TransactionController::class, 'withdrawFromBank'])->name('transaction.withdrawFromBank');
            Route::get('/history', [TransactionController::class, 'history'])->name('transaction.history');

            // Challenge
            Route::get('/challenges', [MainChallengeController::class, 'index'])->name('challenges.index');
            Route::post('/challenges/take', [MainChallengeController::class, 'take'])->name('challenges.take');
            Route::post('/challenges/{submission}/submit', [MainChallengeController::class, 'store'])->name('challenges.store');

            // Cases
            Route::get('/cases', [MainCaseController::class, 'index'])->name('cases.index');
            Route::post('/cases/buy-guideline', [MainCaseController::class, 'buyGuideline'])->name('cases.buyGuideline');
            Route::post('/cases/{id}/submit', [MainCaseController::class, 'submit'])->name('cases.submit');

            // Menu Lain
            Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');
            Route::get('/team', [GroupController::class, 'index'])->name('group.index');
        });
    });
});
