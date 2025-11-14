<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

// Landing otomatis ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Login
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Register
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->as('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', function () {
            return view('admin.index');
        })->name('dashboard');

        // USER MANAGEMENT (CRUD)
        Route::resource('/users', UserController::class);

        // Event Management
        Route::get('/events', function () {
            return view('admin.event.index');
        })->name('event.index');
    });

/*
|--------------------------------------------------------------------------
| MENTOR ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('mentor')
    ->as('mentor.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('mentor.index');
        })->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| INVESTOR ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('investor')
    ->as('investor.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('investor.index');
        })->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| MAIN USER ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('main')
    ->as('main.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('main.index');
        })->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| GOOGLE AUTH ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])
    ->name('auth.google.redirect');

Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])
    ->name('auth.google.callback');
