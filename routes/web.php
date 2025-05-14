<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchPartnerController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('landing_page');
});


Route::get('/dashboard', [HomeController::class, 'index'])->name('home');
Route::get('/partners', [SearchPartnerController::class, 'index'])->name('partners');
Route::get('/proposals', [ProposalController::class, 'index'])->name('proposals');

// Display forms
Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');
Route::get('/register', [AuthController::class, 'showRegister'])->name('signup.form');

// Handle form submissions (to be implemented in controller later)
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');