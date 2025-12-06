<?php

use Illuminate\Support\Facades\Route;

// --- Controllers: Auth ---
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\GoogleAuthController;

// --- Controllers: Admin ---
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

// --- Controllers: Mentor ---
use App\Http\Controllers\Mentor\MentorDashboardController;

// --- Controllers: Investor ---
use App\Http\Controllers\Investor\InvestorDashboardController;

// --- Controllers: Main (User/Peserta) ---
use App\Http\Controllers\Main\OnboardingController;
use App\Http\Controllers\Main\TransactionController;
use App\Http\Controllers\Main\LeaderboardController;
use App\Http\Controllers\Main\GroupController;
use App\Http\Controllers\Main\ChallengeController as MainChallengeController;
use App\Http\Controllers\Main\CaseController as MainCaseController;
use App\Http\Controllers\Main\InvestmentController; // Controller baru untuk User melihat investasi
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\Main\MainDashboardController;

// --- Middlewares ---
use App\Http\Middleware\CheckEventMembership;
use App\Http\Middleware\CheckEventStatus;

/*
|--------------------------------------------------------------------------
| PUBLIC & AUTH ROUTES
|--------------------------------------------------------------------------
*/

// Landing Page
Route::get('/', [LandingPageController::class, 'index'])->name('landing');

// Guest Routes (Login/Register)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Google Auth
    Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
});

// Logout
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

    // Nested Resources (Detail Event Management)
    Route::prefix('events/{event}')->as('events.')->group(function () {

        // 1. Action Tombol (Start/Finish)
        Route::post('start', [EventController::class, 'startEvent'])->name('start');
        Route::post('finish', [EventController::class, 'finishEvent'])->name('finish');

        // 2. Sub-Modules Management
        Route::resource('groups', EventGroupController::class);
        Route::resource('mentors', EventMentorController::class);
        Route::resource('investors', EventInvestorController::class);
        Route::resource('challenges', EventChallengeController::class);
        Route::resource('guidelines', EventGuidelineController::class);
        Route::resource('cases', EventCaseController::class);
        Route::resource('case-submission', CaseSubmissionController::class);

        // --- SQUID BANK SECTION (Admin Central Bank) ---
        Route::post('squidbank/topup', [SquidBankController::class, 'topup'])->name('squidbank.topup');
        Route::resource('squidbank', SquidBankController::class);
    });
});

/*
|--------------------------------------------------------------------------
| MENTOR ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('mentor')->as('mentor.')->middleware(['auth', 'role:mentor'])->group(function () {

    // 1. Halaman Pemilihan Event
    Route::get('/select-event', [MentorDashboardController::class, 'selectEvent'])->name('select-event');

    // 2. Route Spesifik Event (Dicek Membershipnya)
    Route::prefix('events/{event}')->middleware(CheckEventMembership::class)->group(function () {

        // Dashboard Utama
        Route::get('/dashboard', [MentorDashboardController::class, 'index'])->name('dashboard');

        // Monitoring Group
        Route::get('/my-teams', [MentorDashboardController::class, 'myGroups'])->name('groups.index');
        Route::get('/my-teams/{id}', [MentorDashboardController::class, 'showGroup'])->name('groups.show');

        // Actions (Approve/Reject Submission)
        Route::post('/submission/{id}/approve', [MentorDashboardController::class, 'approve'])->name('submission.approve');
        Route::post('/submission/{id}/reject', [MentorDashboardController::class, 'reject'])->name('submission.reject');
    });
});

/*
|--------------------------------------------------------------------------
| INVESTOR ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:investor'])->prefix('investor')->name('investor.')->group(function () {

    // 1. Halaman Pilih Event
    Route::get('/', [InvestorDashboardController::class, 'selectEvent'])->name('select-event');

    // 2. Dashboard Event Tertentu
    Route::prefix('event/{event}')->group(function () {
        Route::get('/dashboard', [InvestorDashboardController::class, 'dashboard'])->name('dashboard');

        // Action Investasi (Kirim Uang)
        Route::post('/invest', [InvestorDashboardController::class, 'invest'])->name('invest');
    });
});

/*
|--------------------------------------------------------------------------
| USER (MAIN/PESERTA) ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user'])->group(function () {

    // Onboarding (Bebas akses sebelum masuk fase game)
    Route::get('/onboarding', [OnboardingController::class, 'index'])->name('main.onboarding.index');
    Route::get('/event/{event}/join', [OnboardingController::class, 'joinEvent'])->name('main.event.join');
    Route::get('/event/{event}/onboarding', [OnboardingController::class, 'showForm'])->name('main.onboarding.form');
    Route::post('/event/{event}/onboarding', [OnboardingController::class, 'store'])->name('main.onboarding.store');

    // --- MAIN GAME FEATURES ---
    Route::prefix('main')->as('main.')->group(function () {

        // 1. Halaman Thanks (Redirect Logout/Selesai)
        Route::get('/thanks', [MainDashboardController::class, 'thanks'])->name('thanks');

        // 2. Fitur Dashboard & Game (Wajib Member & Event Aktif)
        Route::middleware([CheckEventMembership::class, CheckEventStatus::class])->group(function () {

            // Dashboard
            Route::get('/dashboard', [MainDashboardController::class, 'index'])->name('dashboard');

            // Transaction (Keuangan)
            Route::post('/transfer', [TransactionController::class, 'transfer'])->name('transaction.transfer');
            Route::post('/transaction/withdraw-from-bank', [TransactionController::class, 'withdrawFromBank'])->name('transaction.withdrawFromBank');
            Route::get('/history', [TransactionController::class, 'history'])->name('transaction.history');

            // Challenge (Logic)
            Route::get('/challenges', [MainChallengeController::class, 'index'])->name('challenges.index');
            Route::post('/challenges/take', [MainChallengeController::class, 'take'])->name('challenges.take');
            Route::post('/challenges/{submission}/submit', [MainChallengeController::class, 'store'])->name('challenges.store');

            // Business Case
            Route::get('/cases', [MainCaseController::class, 'index'])->name('cases.index');
            Route::post('/cases/buy-guideline', [MainCaseController::class, 'buyGuideline'])->name('cases.buyGuideline');
            Route::post('/cases/{id}/submit', [MainCaseController::class, 'submit'])->name('cases.submit');

            // Investment Radar (Halaman "Show" Merah)
            Route::get('/investments', [InvestmentController::class, 'index'])->name('investments.index');

            // Menu Lainnya
            Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');
            Route::get('/team', [GroupController::class, 'index'])->name('group.index');
        });
    });
});
