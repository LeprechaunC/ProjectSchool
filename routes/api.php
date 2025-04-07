// Team Message Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/teams/{team}/messages', [TeamMessageController::class, 'index']);
    Route::post('/teams/{team}/messages', [TeamMessageController::class, 'store']);
});

// Team Management Routes
Route::middleware('auth:sanctum')->group(function () {
    // Team CRUD
    Route::get('/teams', [TeamController::class, 'index']);
    Route::post('/teams', [TeamController::class, 'createTeam']);
    Route::put('/teams/{team}/name', [TeamController::class, 'updateTeamName']);
    Route::delete('/teams/{team}', [TeamController::class, 'deleteTeam']);
    
    // Team Member Management
    Route::post('/teams/{team}/exit', [TeamController::class, 'exitTeam']);
    Route::post('/teams/{team}/members/{user}/make-admin', [TeamController::class, 'makeAdmin']);
    Route::post('/teams/{team}/members/{user}/remove-admin', [TeamController::class, 'removeAdmin']);
    Route::delete('/teams/{team}/users/{user}', [TeamController::class, 'removeMember']);
    
    // Team Invite
    Route::post('/teams/join', [TeamController::class, 'joinTeam']);
    Route::post('/teams/{team}/invite', [TeamController::class, 'generateInviteCode']);
});

// Message routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/messages', [MessageController::class, 'index']);
    Route::get('/messages/{user}', [MessageController::class, 'show']);
    Route::post('/messages/{user}', [MessageController::class, 'store']);
    Route::delete('/messages/{message}', [MessageController::class, 'destroy']);
    Route::get('/messages/unread/count', [MessageController::class, 'unreadCount']);
}); 