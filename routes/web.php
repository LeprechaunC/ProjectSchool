<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TeamMessageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PdfExportController;
use App\Http\Controllers\AdminPdfExportController;
use App\Http\Controllers\Auth\GoogleController;

Route::middleware(['web'])->group(function () {
    // Default home page
    Route::get('/', function () {
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    });

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::get('/teams', function () {
        return Inertia::render('Teams', [
            'currentUser' => auth()->user() // Pass the logged-in user to the Vue component
        ]);
    })->middleware(['auth', 'verified'])->name('teams');
    
    Route::get('/goals', function () {
        return Inertia::render('goals');
    })->middleware(['auth', 'verified'])->name('goals');
    
    Route::get('/messages', function () {
        return Inertia::render('Messages');
    })->name('messages');
    
    // Profile routes
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::post('/profile_image', [ProfileController::class, 'uploadImage'])->name('profile.image.upload');

        // Message routes
        Route::get('/api/messages', [MessageController::class, 'index']);
        Route::get('/api/messages/{user}', [MessageController::class, 'show']);
        Route::post('/api/messages/{user}', [MessageController::class, 'store']);
        Route::get('/api/messages/unread/count', [MessageController::class, 'unreadCount']);
        Route::get('/api/users', [MessageController::class, 'getUsers']);

        Route::post('/teams', [TeamController::class, 'index'])->name('teams.index');
        Route::get('/api/goals/{teamId}', [GoalController::class, 'getGoalsByTeam']);
        Route::post('/api/goals', [GoalController::class, 'store']);
    
        Route::delete('/api/goals/{id}', [GoalController::class, 'destroy'])->name('goals.destroy');
        Route::post('/api/goals', [GoalController::class, 'store']);
        Route::get('/api/teams', [GoalController::class, 'index']);
        Route::get('/api/teams', [TeamController::class, 'index']);    
        Route::get('/api/goals/user/allusergoals', [GoalController::class, 'getAllUserGoals']);
        Route::patch('/api/goals/{id}/done', [GoalController::class, 'markAsDone']);
    
        Route::get('/goals/{teamId}', [GoalController::class, 'getGoalsByTeam']);
    
        Route::delete('/teams/{team}/users/{user}', [TeamController::class, 'removeMember']);
    
        Route::delete('/api/goals/{id}', [GoalController::class, 'delete'])->name('goals.delete');
        
        Route::patch('/api/goals/{id}', [GoalController::class, 'update']);   
        Route::get('/api/goals/user/allusergoals', [GoalController::class, 'getAllUserGoals']);
    
        Route::get('/api/goals/{teamId}', [GoalController::class, 'getGoalsByTeam']);
        Route::get('/api/goals/user/allusergoals', [GoalController::class, 'getAllUserGoals']);
        Route::get('/api/goals/filtered', [GoalController::class, 'getFilteredGoals']);
        Route::get('/api/goals/user/teamgoals', [GoalController::class, 'getUserTeamGoals']);
 
        Route::get('/goals', function () {
            $user = auth()->user();
            
            // Check if the user has any teams
            $firstTeam = $user->teams()->first();
            $teamId = $firstTeam ? $firstTeam->id : null;
    
            return Inertia::render('Goals', [
                'team_id' => $teamId
            ]);
        })->name('goals');
            
        Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
            return $request->user();
        });

        Route::post('/api/teams/{teamId}/votekick', [TeamController::class, 'votekick']);
        Route::get('/api/user', function (Request $request) {
            return auth()->user();
        });
        Route::post('/api/teams/{id}/exit', [TeamController::class, 'exitTeam']);
        Route::post('api/teams/join', [TeamController::class, 'joinTeam']);
        Route::post('/api/teams/{teamId}/invite', [TeamController::class, 'generateInviteCode']);
        Route::get('/api/teams/{id}/name', [TeamController::class, 'updateTeamName']);
        Route::put('/api/teams/{team}/name', [TeamController::class, 'updateTeamName']);
        Route::post('/api/teamsMake', [TeamController::class, 'createTeam']);

        Route::get('/api/teams/{team}/messages', [TeamMessageController::class, 'index']);
        Route::post('/api/teams/{team}/messages', [TeamMessageController::class, 'store']);
     
        Route::post('/profile/picture', [ProfileController::class, 'updateProfilePicture'])
            ->name('profile.picture.update')
            ->middleware('auth');

        Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');

        // PDF Export Routes for Users
        Route::get('/export/personal-goals', [PdfExportController::class, 'exportPersonalGoals'])
            ->name('export.personal-goals');
        Route::get('/export/team-goals/{teamId}', [PdfExportController::class, 'exportTeamGoals'])
            ->name('export.team-goals');
        Route::get('/export/user-teams', [PdfExportController::class, 'exportUserTeams'])
            ->name('export.user-teams');

        // Add missing team management routes
        Route::delete('/api/teams/{team}/users/{user}', [TeamController::class, 'removeMember']);
        Route::post('/api/teams/{team}/members/{user}/make-admin', [TeamController::class, 'makeAdmin']);
        Route::post('/api/teams/{team}/members/{user}/remove-admin', [TeamController::class, 'removeAdmin']);
    });

    // Admin Routes
    Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
        Route::get('/admin', function () {
            return Inertia::render('Admin/AdminDashboard');
        })->name('admin.dashboard');

        Route::prefix('api/admin')->group(function () {
            Route::get('/statistics', [AdminController::class, 'getStatistics']);
            Route::get('/users', [AdminController::class, 'getUsers']);
            Route::post('/users', [AdminController::class, 'createUser']);
            Route::put('/users/{user}', [AdminController::class, 'updateUser']);
            Route::delete('/users/{user}', [AdminController::class, 'deleteUser']);

            Route::get('/teams', [AdminController::class, 'getTeams']);
            Route::post('/teams', [AdminController::class, 'createTeam']);
            Route::put('/teams/{team}', [AdminController::class, 'updateTeam']);
            Route::delete('/teams/{team}', [AdminController::class, 'deleteTeam']);

            Route::get('/settings', [AdminController::class, 'getSettings']);
            Route::put('/settings', [AdminController::class, 'updateSettings']);
        });

        // PDF Export Routes for Admin
        Route::prefix('admin/export')->group(function () {
            Route::get('/users', [AdminPdfExportController::class, 'exportUsers'])
                ->name('admin.export.users');
            Route::get('/teams', [AdminPdfExportController::class, 'exportTeams'])
                ->name('admin.export.teams');
            Route::get('/goal-stats', [AdminPdfExportController::class, 'exportGoalStats'])
                ->name('admin.export.goal-stats');
            Route::get('/system-report', [AdminPdfExportController::class, 'exportSystemReport'])
                ->name('admin.export.system-report');
        });
    });

    // Google OAuth Routes
    Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');
});

require __DIR__.'/auth.php';