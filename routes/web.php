<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    Route::resource('tasks', TaskController::class);
    Route::put('tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
    Route::put('tasks/{task}/reopen', [TaskController::class, 'reopen'])->name('tasks.reopen');
});

require __DIR__.'/settings.php';
