<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * Task Routes
 */
Route::prefix('tasks')->group(function () {
    Route::post('/', [\App\Http\Controllers\TaskController::class, 'createTask'])->name('tasks.create');
    Route::get('/', [\App\Http\Controllers\TaskController::class, 'getTasks'])->name('tasks.index');
    Route::get('/{hash}', [\App\Http\Controllers\TaskController::class, 'getTask'])->name('tasks.show');
    Route::put('/{hash}', [\App\Http\Controllers\TaskController::class, 'updateTask'])->name('tasks.update');
    Route::delete('/{hash}', [\App\Http\Controllers\TaskController::class, 'deleteTask'])->name('tasks.delete');
    Route::get('/status/{status}', [\App\Http\Controllers\TaskController::class, 'getTasksByStatus'])->name('tasks.status');
});