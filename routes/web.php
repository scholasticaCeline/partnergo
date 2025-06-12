<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\SearchPartnerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrganizationController;

Route::get('/', function () {
    return view('landing_page');
})->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login_logic'])->name('login_logic');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register_logic'])->name('register_logic');
});


Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'index'])->name('user.home');   
    Route::get('/partners', [SearchPartnerController::class, 'index'])->name('partners');
    Route::get('/proposals', [ProposalController::class, 'index'])->name('proposals');
    
    // Create organization route
    Route::get('/organization/create', [OrganizationController::class, 'create'])->name('organizations.create');
    Route::post('/organization', [OrganizationController::class, 'store'])->name('organizations.store');
    
    // The SPECIFIC "create proposal" route comes FIRST.
    Route::get('/organization/{organization}/propose', [ProposalController::class, 'create'])->name('proposals.create');
    // The POST route to submit the form now uses the same URL.
    Route::post('/organization/{organization}/propose', [ProposalController::class, 'store'])->name('proposals.store');
    // The GENERAL "show profile" route comes LAST.
    Route::get('/organization/{organization}', [OrganizationController::class, 'show'])->name('organizations.show');

    // Show proposals route
    Route::get('/proposals/{proposal}', [ProposalController::class, 'show'])->name('proposals.show');
    // Add this line with your other proposal routes
    Route::get('/proposals/files/{proposalFile}', [ProposalController::class, 'downloadFile'])->name('proposals.files.download');

    // ORGANIZATION MANAGEMENT PART
    // Route to organization dashboard
    Route::get('/organizations/{organization}/dashboard', [OrganizationController::class, 'dashboard'])->name('organization.dashboard');
    
    // Route to show the management page
    Route::get('/organizations/{organization}/manage', [OrganizationController::class, 'manage'])->name('organization.manage');

    // Route to handle updating the main details (name, description)
    Route::patch('/organizations/{organization}/manage/details', [OrganizationController::class, 'updateDetails'])->name('organization.update.details');

    // Route to handle updating the partnership type tags
    Route::patch('/organizations/{organization}/manage/tags', [OrganizationController::class, 'updateTags'])->name('organization.update.tags');
    
    // Route to handle updating member roles
    Route::patch('/organizations/{organization}/manage/members', [OrganizationController::class, 'updateMembers'])->name('organization.update.members');
});
