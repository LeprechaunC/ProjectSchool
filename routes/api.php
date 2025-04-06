// Team Message Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/teams/{team}/messages', [TeamMessageController::class, 'index']);
    Route::post('/teams/{team}/messages', [TeamMessageController::class, 'store']);
}); 