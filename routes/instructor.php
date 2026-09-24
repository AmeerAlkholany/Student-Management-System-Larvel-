<?php

use App\Http\Controllers\Instructor\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:instructor'])
    ->prefix('instructor')
    ->name('instructor.')
    ->group(function (): void {
        Route::get('dashboard', DashboardController::class)->name('dashboard');
    });
