<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * Task Routes
 */
Route::prefix('tasks')->group(function () {
    Route::post('/', [\App\Http\Controllers\TaskController::class, 'createTask']);
    Route::get('/', [\App\Http\Controllers\TaskController::class, 'getTasks']);
    Route::put('/{hash}', [\App\Http\Controllers\TaskController::class, 'updateStatus']);
    Route::delete('/{hash}', [\App\Http\Controllers\TaskController::class, 'deleteTask']);
    Route::get('/status/{status}', [\App\Http\Controllers\TaskController::class, 'getTasksByStatus']);

});

Route::fallback(function () {
    return response()->json([
        'message' => 'Rota não encontrada.'
    ], 404);
});