<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectContrller;
use App\Http\Controllers\TaskContrller;
use App\Http\Controllers\UserController;

Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('users', UserController::class);
    Route::apiResource('projects', ProjectContrller::class);
    Route::apiResource('tasks', TaskContrller::class);
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])
        ->name('dashboard');
    Route::get('/myTasks', [TaskContrller::class, 'myTasks'])
        ->name('tasks.myTasks');
    Route::get('/project-assciated-tasks', [TaskContrller::class, 'projectAssciatedTasks'])
        ->name('project-assciated-tasks');
});
