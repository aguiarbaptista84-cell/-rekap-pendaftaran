<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\UserController;

// Health check (publik — untuk monitoring uptime)
Route::get('health', [HealthController::class, 'check'])->name('health');

// Auth
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected routes
Route::middleware(['auth', 'role'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Pendaftaran — semua role bisa akses index/show; create/edit/delete hanya canWrite
    Route::resource('pendaftaran', PendaftaranController::class);
    Route::post('pendaftaran/{pendaftaran}/status', [PendaftaranController::class, 'updateStatus'])
        ->name('pendaftaran.status');

    // Rekap
    Route::get('rekap', [RekapController::class, 'index'])->name('rekap.index');
    Route::get('rekap/export-csv',  [RekapController::class, 'exportCsv'])->name('rekap.export-csv');
    Route::get('rekap/export-pdf',  [RekapController::class, 'exportPdf'])->name('rekap.export-pdf');
    Route::get('rekap/export-word', [RekapController::class, 'exportWord'])->name('rekap.export-word');

    // User Management — super_admin only
    Route::middleware('role:super_admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::post('users/{user}/toggle', [UserController::class, 'toggleAktif'])->name('users.toggle');
    });
});
