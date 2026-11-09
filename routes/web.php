<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MooraController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\RocController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('siswa', SiswaController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('kriteria', KriteriaController::class)
        ->parameters(['kriteria' => 'kriteria'])
        ->only(['index', 'store', 'update', 'destroy']);

    Route::get('/nilai', [NilaiController::class, 'index'])->name('nilai.index');
    Route::post('/nilai', [NilaiController::class, 'store'])->name('nilai.store');

    Route::get('/roc', [RocController::class, 'index'])->name('roc.index');
    Route::post('/roc', [RocController::class, 'hitung'])->name('roc.hitung');

    Route::get('/moora', [MooraController::class, 'index'])->name('moora.index');
    Route::post('/moora', [MooraController::class, 'hitung'])->name('moora.hitung');

    Route::get('/ranking', [RankingController::class, 'index'])->name('ranking.index');
    Route::post('/ranking/refresh', [RankingController::class, 'refresh'])->name('ranking.refresh');
    Route::post('/ranking/pengaturan', [RankingController::class, 'updatePengaturan'])->name('ranking.pengaturan');

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
    Route::get('/laporan/csv', [LaporanController::class, 'exportCsv'])->name('laporan.csv');
    Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
});
