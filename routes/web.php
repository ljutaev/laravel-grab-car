<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\LoginController;

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('signup', [SignupController::class, 'create'])->name('signup')
Route::get('login', [LoginController::class, 'create'])->name('login');

Route::get('car', [CarController::class, 'index'])->name('home');
