<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\TeamController;

// Default home page
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Dashboard route
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile_image', [ProfileController::class, 'uploadImage'])->name('profile.image.upload');

    Route::post('/teams', [TeamController::class, 'index'])->name('teams.index');
    Route::get('/api/goals/{teamId}', [GoalController::class, 'getGoalsByTeam']);
    Route::post('/api/goals', [GoalController::class, 'store']);
    Route::get('goals', [GoalController::class, 'index']);
    Route::delete('/api/goals/{id}', [GoalController::class, 'destroy'])->name('goals.destroy');


});
Route::post('/api/goals/{id}', [GoalController::class, 'store']);
Route::post('/api/goals/{id}', [GoalController::class, 'update']);
 require __DIR__.'/auth.php';
