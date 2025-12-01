<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// PUBLIC CONTROLLERS
use App\Http\Controllers\FrontAntrianController;
use App\Http\Controllers\ContactController;

// ADMIN CONTROLLERS
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardAntrianController;
use App\Http\Controllers\DashboardLaporanController;
use App\Http\Controllers\DashboardPasienController;
use App\Http\Controllers\DashboardAnalyticsController;

// LIVEWIRE (Status antrian realtime)
use App\Http\Livewire\Antrian\StatusAntrian;
use App\Http\Controllers\TvAntrianController;

/*
|--------------------------------------------------------------------------
| ROUTE PUBLIK (PASIEN / USER BIASA)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('home');
})->name('home');

Route::post('/contact/send', [ContactController::class, 'send'])
    ->name('contact.send');

// FORM CARI ANTRIAN (tampil form)
Route::get('/antrian/cari', [FrontAntrianController::class, 'showCariAntrianForm'])
    ->name('antrian.cari');

// PROSES CARI ANTRIAN (submit NIK)
Route::post('/antrian/cari', [FrontAntrianController::class, 'searchByNik'])
    ->name('antrian.cari.proses');

// form edit antrian (dari sisi pasien)
Route::get('/antrian/{antrian}/edit', [FrontAntrianController::class, 'edit'])
    ->name('antrian.edit');

// update antrian
Route::put('/antrian/{antrian}', [FrontAntrianController::class, 'update'])
    ->name('antrian.update');

// hapus antrian
Route::delete('/antrian/{antrian}', [FrontAntrianController::class, 'destroy'])
    ->name('antrian.destroy');

// CRUD antrian pasien
Route::resource('antrian', FrontAntrianController::class);

// Cetak nomor antrian
Route::get('/livewire/antrian/cetakAntrian', [FrontAntrianController::class, 'cetakAntrian'])
    ->name('cetakAntrian');

// Halaman status antrian (LIVEWIRE)
Route::get('/antrian/status/{antrian}', StatusAntrian::class)
    ->name('antrian.status');

// Halaman profil/riwayat pasien (untuk QR di kartu pasien)
Route::get('/pasien/{patient}', [FrontAntrianController::class, 'profilPasien'])
    ->name('pasien.profil');

// Halaman cetak kartu pasien
Route::get('/pasien/kartu/{patient}', [FrontAntrianController::class, 'kartuPasien'])
    ->name('pasien.kartu');
    
// Halaman cetak tiket antrian
Route::get('/antrian/tiket/{antrian}', [FrontAntrianController::class, 'tiketAntrian'])
    ->name('antrian.tiket');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (Register dimatikan)
|--------------------------------------------------------------------------
*/
Auth::routes(['register' => false]);

// Halaman TV Antrian (public, tidak perlu login)
Route::get('/tv-antrian', [TvAntrianController::class, 'index'])
    ->name('tv.antrian');
Route::get('/tv-antrian/data', [TvAntrianController::class, 'data'])
    ->name('tv.data');

/*
|--------------------------------------------------------------------------
| ROUTES ADMIN (WAJIB LOGIN + ROLE ADMIN)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard utama
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // 🔥 Dashboard Analitik
        Route::get('/analytics', [DashboardAnalyticsController::class, 'index'])
            ->name('analytics');

        // 🔴 Reset Antrian Hari Ini
        Route::post('/dashboard/reset-antrian-hari-ini', [DashboardController::class, 'resetAntrianHariIni'])
            ->name('dashboard.reset-antrian');

        /*
        |--------------------------------------------------------------------------
        | CHECK-IN QR ADMIN
        |--------------------------------------------------------------------------
        */
        Route::get('/checkin', [DashboardAntrianController::class, 'checkinPage'])
            ->name('checkin.page');

        Route::post('/checkin', [DashboardAntrianController::class, 'checkinStore'])
            ->name('checkin.store');

        /*
        |--------------------------------------------------------------------------
        | ANTRIAN PER POLI
        |--------------------------------------------------------------------------
        */
        Route::get('/dashboard/antrian/poliUmum',      [DashboardAntrianController::class, 'indexPoliUmum'])->name('antrian.umum');
        Route::get('/dashboard/antrian/poliGigi',      [DashboardAntrianController::class, 'indexPoliGigi'])->name('antrian.gigi');
        Route::get('/dashboard/antrian/poliTht',       [DashboardAntrianController::class, 'indexPoliTht'])->name('antrian.tht');
        Route::get('/dashboard/antrian/poliLansia',    [DashboardAntrianController::class, 'indexPoliLansia'])->name('antrian.lansia');
        Route::get('/dashboard/antrian/poliKandungan', [DashboardAntrianController::class, 'indexPoliKandungan'])->name('antrian.kandungan');
        Route::get('/dashboard/antrian/poliKia',       [DashboardAntrianController::class, 'indexPoliKia'])->name('antrian.kia');
        Route::get('/dashboard/antrian/poliNifas',     [DashboardAntrianController::class, 'indexPoliNifas'])->name('antrian.nifas');
        Route::get('/dashboard/antrian/poliBalita',    [DashboardAntrianController::class, 'indexPoliBalita'])->name('antrian.balita');

        /*
        |--------------------------------------------------------------------------
        | DATA PASIEN
        |--------------------------------------------------------------------------
        */
        Route::get('/dashboard/pasien', [DashboardPasienController::class, 'index'])
            ->name('pasien.index');

        Route::get('/dashboard/pasien/{patient}', [DashboardPasienController::class, 'show'])
            ->name('pasien.show');

        /*
        |--------------------------------------------------------------------------
        | LAPORAN
        |--------------------------------------------------------------------------
        */
        Route::get('/dashboard/laporan/index', [DashboardLaporanController::class, 'index'])
            ->name('laporan.index');

        Route::get('/dashboard/laporan/cetak',        [DashboardLaporanController::class, 'exportPdf'])->name('cetakLaporan');
        Route::get('/dashboard/laporan/cetak-pdf',    [DashboardLaporanController::class, 'exportPdf'])->name('laporan.pdf');
        Route::get('/dashboard/laporan/export-excel', [DashboardLaporanController::class, 'exportExcelCsv'])->name('laporan.excel');
    });

/*
|--------------------------------------------------------------------------
| REDIRECT /dashboard KE /admin/dashboard
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->get('/dashboard', function () {
        return redirect()->route('admin.dashboard');
    });
