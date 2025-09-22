<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Public\FormPageController;
use App\Http\Controllers\Public\FormSubmitController;
use App\Http\Controllers\Public\FormSlugController;
use App\Http\Controllers\Public\FormSlugSubmitController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FormController;
use App\Http\Controllers\Admin\SubmissionController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Form routes by slug (public access - no authentication required)
Route::get('/form/{slug}', [FormSlugController::class, 'show'])
    ->name('public.forms.slug.show');
Route::post('/form/{slug}', [FormSlugSubmitController::class, 'store'])
    ->name('public.forms.slug.submit');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    
    Route::get('/register', [RegisteredUserController::class, 'create'])
        ->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
    
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');
    
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Dashboard route
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Forms management
    Route::resource('forms', FormController::class);
    Route::post('/forms/{form}/activate', [FormController::class, 'activate'])->name('forms.activate');
    Route::post('/forms/{form}/deactivate', [FormController::class, 'deactivate'])->name('forms.deactivate');
    
    // Submissions management
    Route::get('/submissions', [SubmissionController::class, 'index'])->name('submissions.index');
    Route::get('/submissions/{submission}', [SubmissionController::class, 'show'])->name('submissions.show');
    Route::get('/submissions/export/csv', [SubmissionController::class, 'export'])->name('submissions.export');
    Route::get('/submissions/statistics', [SubmissionController::class, 'statistics'])->name('submissions.statistics');
});
