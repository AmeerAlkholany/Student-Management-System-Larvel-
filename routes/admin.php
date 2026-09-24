<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\FacultyController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function (): void {
        Route::get('dashboard', DashboardController::class)->name('dashboard');

        Route::resource('faculties', FacultyController::class);
        Route::resource('departments', DepartmentController::class);
    });
