<?php

use App\Http\Controllers\Api\V1\CsvReportController;
use App\Http\Controllers\Api\V1\TaskController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\UserTasksController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function() {
    Route::apiResource('tasks', TaskController::class)->except('update');
    Route::put('tasks/{task}', [TaskController::class, 'replace']);
    Route::patch('tasks/{task}', [TaskController::class, 'update']);

    Route::apiResource('users', UserController::class)->except('update');
    Route::put('users/{user}', [UserController::class, 'replace']);
    Route::patch('users/{user}', [UserController::class, 'update']);

    Route::apiResource('users.tasks', UserTasksController::class)->except('update');
    Route::put('users/{user}/tasks/{task}', [UserTasksController::class, 'replace']);
    Route::patch('users/{user}/tasks/{task}', [UserTasksController::class, 'update']);

    Route::post('tasks/export', [TaskController::class, 'exportTasks']);    
});