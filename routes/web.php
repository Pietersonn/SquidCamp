<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\GoogleAuthController;

// Admin Controllers
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\EventController;

use App\Http\Controllers\Admin\EventGroupController;
use App\Http\Controllers\Admin\EventMentorController;
use App\Http\Controllers\Admin\EventInvestorController;
use App\Http\Controllers\Admin\EventChallengeController;
use App\Http\Controllers\Admin\EventCaseController;
use App\Http\Controllers\Admin\EventGuidelineController;

/*
|--------------------------------------------------------------------------
| AUTH
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
| ADMIN
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->as('admin.')->middleware(['auth'])->group(function () {

    Route::get('/dashboard', fn() => view('admin.index'))->name('dashboard');

    // Users
    Route::resource('/users', UserController::class);

    // Events
    Route::resource('/events', EventController::class);

    // Kelola Grup
    Route::resource('/groups', EventGroupController::class)->names('groups');

    Route::resource('/mentors', EventMentorController::class)->names('mentors');
    Route::resource('/investors', EventInvestorController::class)->names('investors');
    Route::resource('/challenges', EventChallengeController::class)->names('challenges');
    Route::resource('/cases', EventCaseController::class)->names('cases');
    Route::resource('/guidelines', EventGuidelineController::class)->names('guidelines');
    Route::post('/events/{event}/toggle-active', [EventController::class, 'toggleActive'])->name('events.toggleActive');
});


/*
|--------------------------------------------------------------------------
| MENTOR
|--------------------------------------------------------------------------
*/

Route::prefix('mentor')->as('mentor.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', fn() => view('mentor.index'))->name('dashboard');
});


/*
|--------------------------------------------------------------------------
| INVESTOR
|--------------------------------------------------------------------------
*/

Route::prefix('investor')->as('investor.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', fn() => view('investor.index'))->name('dashboard');
});


/*
|--------------------------------------------------------------------------
| USER (MAIN)
|--------------------------------------------------------------------------
*/

Route::prefix('main')->as('main.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', fn() => view('main.index'))->name('dashboard');
});
