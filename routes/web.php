<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\PoliController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\Dokter\JadwalPeriksaController as DokterJadwalPeriksaController;
use App\Http\Controllers\Pasien\PoliController as PasienPoliController ;
use App\Http\Controllers\Dokter\PeriksaPasienController as DokterPeriksaPasienController;
use App\Http\Controllers\Dokter\RiwayatPasienController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    Route::resource('polis', PoliController::class);
    Route::resource('dokter', DokterController::class);
    Route::resource('pasien', PasienController::class);
    Route::resource('obat', ObatController::class);
});

// Dokter Routes
Route::middleware(['auth', 'role:dokter'])->prefix('dokter')->group(function () {
    Route::get('/dashboard', function () {
        return view('dokter.dashboard');
    })->name('dokter.dashboard');
    route::resource('jadwal-periksa', DokterJadwalPeriksaController::class);
    Route::get('periksa-pasien', [DokterPeriksaPasienController::class, 'index'])->name('periksa-pasien.index');
    Route::get('periksa-pasien/{id}/create', [DokterPeriksaPasienController::class, 'create'])->name('periksa-pasien.create');
    Route::post('periksa-pasien', [DokterPeriksaPasienController::class, 'store'])->name('periksa-pasien.store');
    Route::get('riwayat-pasien', [RiwayatPasienController::class, 'index'])->name('riwayat-pasien.index');
    Route::get('riwayat-pasien/{id}', [RiwayatPasienController::class, 'show'])->name('riwayat-pasien.show');
});

// Pasien Routes
Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->group(function () {
    Route::get('/dashboard', function () {
        return view('pasien.dashboard');
    })->name('pasien.dashboard');
    Route::get('/daftar', [PasienPoliController::class, 'get'])->name('pasien.daftar');
    Route::post('/daftar', [PasienPoliController::class, 'submit'])->name('pasien.daftar.submit');

});
