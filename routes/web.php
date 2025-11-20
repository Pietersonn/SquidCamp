<?php

use Illuminate\Support\Facades\Route;

// AUTH
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\GoogleAuthController;

// ADMIN
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\ChallengeController;
use App\Http\Controllers\Admin\GuidelineController;
use App\Http\Controllers\Admin\EventGroupController;
use App\Http\Controllers\Admin\EventMentorController;
use App\Http\Controllers\Admin\EventInvestorController;
use App\Http\Controllers\Admin\EventChallengeController;
use App\Http\Controllers\Admin\EventCaseController;
use App\Http\Controllers\Admin\EventGuidelineController;

// ROLE LAIN
// (Kita bisa tambahkan controller dashboard untuk mereka nanti)

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
|
| Rute untuk login, register, logout, dan Google Auth.
|
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
|
*/

Route::prefix('admin')->as('admin.')->middleware(['auth', 'role:admin'])->group(function () {

  Route::get('/dashboard', fn() => view('admin.index'))->name('dashboard');

  // MASTER DATA (Global)
  Route::resource('/challenges', ChallengeController::class);
  Route::resource('/guidelines', GuidelineController::class);

  // Events
  Route::resource('/events', EventController::class);
  Route::resource('/users', UserController::class);
  Route::post('/events/{event}/toggle-active', [EventController::class, 'toggleActive'])->name('events.toggleActive');

  // EVENT SUBMENU
  Route::prefix('events/{event}')->as('events.')->group(function () {
    Route::resource('groups', EventGroupController::class);
    Route::resource('mentors', EventMentorController::class);
    Route::resource('investors', EventInvestorController::class);

    // PER-EVENT
    Route::resource('challenges', EventChallengeController::class);
    Route::resource('guidelines', EventGuidelineController::class);

    Route::resource('cases', EventCaseController::class);
  });
});



/*
|--------------------------------------------------------------------------
| MENTOR
|--------------------------------------------------------------------------
*/

Route::prefix('mentor')->as('mentor.')->middleware(['auth', 'role:mentor'])->group(function () {
  Route::get('/dashboard', fn() => view('mentor.index'))->name('dashboard');
});


/*
|--------------------------------------------------------------------------
| INVESTOR
|--------------------------------------------------------------------------
*/

Route::prefix('investor')->as('investor.')->middleware(['auth', 'role:investor'])->group(function () {
  Route::get('/dashboard', fn() => view('investor.index'))->name('dashboard');
});


/*
|--------------------------------------------------------------------------
| USER (MAIN)
|--------------------------------------------------------------------------
*/

Route::prefix('main')->as('main.')->middleware(['auth', 'role:user'])->group(function () {
  Route::get('/dashboard', fn() => view('main.index'))->name('dashboard');
});
