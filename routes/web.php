<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\PemasokController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes – Sistem Inventaris Bengkel
|--------------------------------------------------------------------------
*/

// Redirect root ke dashboard (jika login) atau login
Route::get('/', fn() => redirect()->route('dashboard'));

// ── Auth ────────────────────────────────────────────────────────────────
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Registrasi publik dinonaktifkan – penambahan pengguna hanya oleh Admin via menu Pengguna
// Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
// Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// ── Protected Routes (harus login) ──────────────────────────────────────
Route::middleware('auth.custom')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Barang
    Route::resource('barang', BarangController::class)->except(['show']);

    // Barang Masuk
    Route::get('/barang-masuk',                      [BarangMasukController::class, 'index'])->name('barang-masuk.index');
    Route::get('/barang-masuk/tambah',               [BarangMasukController::class, 'create'])->name('barang-masuk.create');
    Route::post('/barang-masuk',                     [BarangMasukController::class, 'store'])->name('barang-masuk.store');
    Route::delete('/barang-masuk/{barangMasuk}',     [BarangMasukController::class, 'destroy'])->name('barang-masuk.destroy');

    // Barang Keluar
    Route::get('/barang-keluar',                     [BarangKeluarController::class, 'index'])->name('barang-keluar.index');
    Route::get('/barang-keluar/tambah',              [BarangKeluarController::class, 'create'])->name('barang-keluar.create');
    Route::post('/barang-keluar',                    [BarangKeluarController::class, 'store'])->name('barang-keluar.store');
    Route::delete('/barang-keluar/{barangKeluar}',   [BarangKeluarController::class, 'destroy'])->name('barang-keluar.destroy');

    // Pemasok
    Route::resource('pemasok', PemasokController::class)->except(['show']);

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

    // Activity Logs & Export
    Route::get('/activity-log',      [ActivityLogController::class, 'index'])->name('activity-log.index');
    Route::get('/export/barang',     [ExportController::class, 'exportBarang'])->name('export.barang');
    Route::get('/export/laporan',    [ExportController::class, 'exportLaporan'])->name('export.laporan');

    // Pengguna – hanya admin
    Route::middleware('admin')->group(function () {
        Route::resource('pengguna', PenggunaController::class)->except(['show']);
    });
});
