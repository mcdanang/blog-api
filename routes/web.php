<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// Display the registration page
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Handle registration form submission
Route::post('/register', [AuthController::class, 'register']);

// Display the login page
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Handle login form submission
Route::post('/login', [AuthController::class, 'login']);

