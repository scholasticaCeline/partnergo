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

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');