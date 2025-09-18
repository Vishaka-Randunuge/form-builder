<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\User;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\SubmissionController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin'       => Route::has('login'),
        'canRegister'    => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion'     => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Form resource routes (excluding 'show')
    Route::resource('forms', FormController::class)->except(['show']);

    // Additional form-related routes
    Route::get('/forms/{form}/preview', [FormController::class, 'preview'])->name('forms.preview');
    Route::post('/forms/{form}/submit', [SubmissionController::class, 'submit'])->name('forms.submit');

    // Submission routes
    Route::get('/forms/{form}/submissions', [SubmissionController::class, 'index'])->name('forms.submissions.index');
    Route::get('/forms/{form}/submissions/{submission}', [SubmissionController::class, 'show'])->name('forms.submissions.show');
});

// Manual logout route
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

// Dev login route (for local only)
Route::get('/dev-login', function () {
    if (!app()->environment('local')) {
        abort(403);
    }

    $user = User::where('email', 'admin@example.com')->first();
    abort_if(!$user, 404);

    Auth::login($user);
    return redirect('/dashboard');
});

// Auth scaffolding (e.g., Breeze, Jetstream, etc.)
require __DIR__.'/auth.php';
