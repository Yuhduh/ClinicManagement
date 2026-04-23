<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Doctor\VisitController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/download', [ReportController::class, 'download'])->name('reports.download');
    });

    Route::middleware(['auth', 'role:doctor,receptionist'])->group(function () {
        Route::resource('patients', PatientController::class)->except(['show']);
        Route::resource('appointments', AppointmentController::class)->except(['show']);
    });

    Route::middleware(['auth', 'role:doctor'])->group(function () {
        Route::resource('prescriptions', PrescriptionController::class)->except(['show']);
        Route::resource('records', VisitController::class)
            ->parameters(['records' => 'record'])
            ->except(['show', 'destroy']);
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
