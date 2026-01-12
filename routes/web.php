<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('tickets')->group(function () {
        Route::name('tickets')->group(function () {
            Route::get('/', [TicketController::class, 'index'])->name('.index');
            Route::get('/create', [TicketController::class, 'create'])->name('.create');
            Route::post('/store', [TicketController::class, 'store'])->name('.store');
            Route::get('/show/{ticket}', [TicketController::class, 'show'])->name('.show');
        });
    });
});

require __DIR__ . '/auth.php';
