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
    
    // Profile routes
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::post('/profile_image', [ProfileController::class, 'uploadImage'])->name('profile.image.upload');

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
 
            Route::get('/goals', function () {
                $user = auth()->user();
                // Assuming the user belongs to one team
                $teamId = $user->teams()->first()->id;  // Make sure the user is in a team
        
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
       
        Route::post('api/teams/join', [TeamController::class, 'joinTeam']);
        Route::post('/api/teams/{teamId}/invite', [TeamController::class, 'generateInviteCode']);

        Route::post('/api/teamsMake', [TeamController::class, 'createTeam']);
    });

 
   
   
  
 
     require __DIR__.'/auth.php';