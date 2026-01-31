<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return redirect()->route('tasks.index');
});

// Task CRUD routes
Route::resource('tasks', TaskController::class)->middleware('auth');

// Dashboard
Route::get('/dashboard', [TaskController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// Auth routes
require __DIR__ . '/auth.php';
