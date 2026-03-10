<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KalenderController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StatistikController;
use App\Http\Controllers\TugasController;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Mata Kuliah (Jadwal Kuliah)
    Route::resource('mata-kuliah', MataKuliahController::class)->except(['show']);

    // Tugas
    Route::resource('tugas', TugasController::class);
    Route::patch('tugas/{tugas}/progress', [TugasController::class, 'updateProgress'])->name('tugas.progress');

    // Kalender
    Route::get('/kalender', [KalenderController::class, 'index'])->name('kalender.index');

    // Statistik
    Route::get('/statistik', [StatistikController::class, 'index'])->name('statistik.index');

    // Profile
    Route::get('/profil', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');

    // About
    Route::get('/about', fn() => view('about.index'))->name('about.index');

    // Global search (placeholder)
    Route::get('/search', function () {
        return response()->json(['results' => []]);
    })->name('global-search');
});
