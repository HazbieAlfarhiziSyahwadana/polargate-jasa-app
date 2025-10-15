<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LayananController as AdminLayananController;
use App\Http\Controllers\Admin\PaketController as AdminPaketController;
use App\Http\Controllers\Admin\AddonController as AdminAddonController;
use App\Http\Controllers\Admin\PesananController as AdminPesananController;
use App\Http\Controllers\Admin\ClientController as AdminClientController;
use App\Http\Controllers\Admin\InvoiceController as AdminInvoiceController;
use App\Http\Controllers\Admin\PembayaranController as AdminPembayaranController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Admin\ProfilController as AdminProfilController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\Client\LayananController as ClientLayananController;
use App\Http\Controllers\Client\PesananController as ClientPesananController;
use App\Http\Controllers\Client\InvoiceController as ClientInvoiceController;
use App\Http\Controllers\Client\PembayaranController as ClientPembayaranController;
use App\Http\Controllers\Client\ProfilController as ClientProfilController;

// ===== LANDING PAGE =====
Route::get('/', [LandingController::class, 'index'])->name('landing');

// ===== ROUTE AUTHENTICATION =====
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// ===== ROUTE ADMIN (SUPERADMIN) =====
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Kelola Layanan
    Route::prefix('layanan')->name('layanan.')->group(function () {
        Route::get('/', [AdminLayananController::class, 'index'])->name('index');
        Route::get('/create', [AdminLayananController::class, 'create'])->name('create');
        Route::post('/', [AdminLayananController::class, 'store'])->name('store');
        Route::get('/{layanan}/edit', [AdminLayananController::class, 'edit'])->name('edit');
        Route::put('/{layanan}', [AdminLayananController::class, 'update'])->name('update');
        Route::delete('/{layanan}', [AdminLayananController::class, 'destroy'])->name('destroy');
        Route::patch('/{layanan}/toggle-status', [AdminLayananController::class, 'toggleStatus'])->name('toggle-status');
    });

    // Kelola Paket
    Route::prefix('paket')->name('paket.')->group(function () {
        Route::get('/', [AdminPaketController::class, 'index'])->name('index');
        Route::get('/create', [AdminPaketController::class, 'create'])->name('create');
        Route::post('/', [AdminPaketController::class, 'store'])->name('store');
        Route::get('/{paket}/edit', [AdminPaketController::class, 'edit'])->name('edit');
        Route::put('/{paket}', [AdminPaketController::class, 'update'])->name('update');
        Route::delete('/{paket}', [AdminPaketController::class, 'destroy'])->name('destroy');
        Route::patch('/{paket}/toggle-status', [AdminPaketController::class, 'toggleStatus'])->name('toggle-status');
    });

    // Kelola Add-on
    Route::prefix('addon')->name('addon.')->group(function () {
        Route::get('/', [AdminAddonController::class, 'index'])->name('index');
        Route::get('/create', [AdminAddonController::class, 'create'])->name('create');
        Route::post('/', [AdminAddonController::class, 'store'])->name('store');
        Route::get('/{addon}/edit', [AdminAddonController::class, 'edit'])->name('edit');
        Route::put('/{addon}', [AdminAddonController::class, 'update'])->name('update');
        Route::delete('/{addon}', [AdminAddonController::class, 'destroy'])->name('destroy');
        Route::patch('/{addon}/toggle-status', [AdminAddonController::class, 'toggleStatus'])->name('toggle-status');
    });

    // Kelola Pesanan
    Route::prefix('pesanan')->name('pesanan.')->group(function () {
        Route::get('/', [AdminPesananController::class, 'index'])->name('index');
        Route::get('/{pesanan}', [AdminPesananController::class, 'show'])->name('show');
        Route::patch('/{pesanan}/update-status', [AdminPesananController::class, 'updateStatus'])->name('update-status');
        Route::post('/{pesanan}/upload-preview', [AdminPesananController::class, 'uploadPreview'])->name('upload-preview');
        Route::post('/{pesanan}/upload-final', [AdminPesananController::class, 'uploadFinal'])->name('upload-final');
        Route::delete('/{pesanan}/delete-preview', [AdminPesananController::class, 'deletePreview'])->name('delete-preview');
        Route::patch('/{pesanan}/extend-preview', [AdminPesananController::class, 'extendPreview'])->name('extend-preview');
    });

    // Kelola Client
    Route::prefix('client')->name('client.')->group(function () {
        Route::get('/', [AdminClientController::class, 'index'])->name('index');
        Route::get('/{user}', [AdminClientController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [AdminClientController::class, 'edit'])->name('edit');
        Route::put('/{user}', [AdminClientController::class, 'update'])->name('update');
        Route::patch('/{user}/toggle-status', [AdminClientController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/{user}', [AdminClientController::class, 'destroy'])->name('destroy');
    });

    // Kelola Invoice
    Route::prefix('invoice')->name('invoice.')->group(function () {
        Route::get('/', [AdminInvoiceController::class, 'index'])->name('index');
        Route::post('/{pesanan}/create-dp', [AdminInvoiceController::class, 'createDP'])->name('create-dp');
        Route::post('/{pesanan}/create-pelunasan', [AdminInvoiceController::class, 'createPelunasan'])->name('create-pelunasan');
        Route::get('/{invoice}', [AdminInvoiceController::class, 'show'])->name('show');
        Route::get('/{invoice}/download', [AdminInvoiceController::class, 'download'])->name('download');
    });

    // Verifikasi Pembayaran
    Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
        Route::get('/', [AdminPembayaranController::class, 'index'])->name('index');
        Route::get('/pending', [AdminPembayaranController::class, 'pending'])->name('pending');
        Route::get('/{pembayaran}', [AdminPembayaranController::class, 'show'])->name('show');
        Route::post('/{pembayaran}/verify', [AdminPembayaranController::class, 'verify'])->name('verify');
        Route::post('/{pembayaran}/reject', [AdminPembayaranController::class, 'reject'])->name('reject');
        Route::delete('/{pembayaran}', [AdminPembayaranController::class, 'destroy'])->name('destroy');
        Route::get('/filter', [AdminPembayaranController::class, 'filter'])->name('filter');
    });

    // Laporan
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/keuangan', [AdminLaporanController::class, 'keuangan'])->name('keuangan');
        Route::get('/pemesanan', [AdminLaporanController::class, 'pemesanan'])->name('pemesanan');
        Route::get('/client', [AdminLaporanController::class, 'client'])->name('client');
        Route::get('/export-keuangan', [AdminLaporanController::class, 'exportKeuangan'])->name('export-keuangan');
        Route::get('/export-pemesanan', [AdminLaporanController::class, 'exportPemesanan'])->name('export-pemesanan');
    });

    // Pengaturan Landing Page (NEW)
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [AdminSettingController::class, 'index'])->name('index');
        Route::post('/update-logo', [AdminSettingController::class, 'updateLogo'])->name('update-logo');
        Route::post('/update-wa', [AdminSettingController::class, 'updateWa'])->name('update-wa');
        Route::post('/update-layanan-image', [AdminSettingController::class, 'updateLayananImage'])->name('update-layanan-image');
    });

    // Profil Admin
    Route::get('profil', [AdminProfilController::class, 'index'])->name('profil');
    Route::put('profil', [AdminProfilController::class, 'update'])->name('profil.update');
    Route::put('profil/change-password', [AdminProfilController::class, 'changePassword'])->name('profil.change-password');
});

// ===== ROUTE CLIENT =====
Route::prefix('client')->middleware(['auth', 'client'])->name('client.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');

    // Lihat Layanan
    Route::prefix('layanan')->name('layanan.')->group(function () {
        Route::get('/', [ClientLayananController::class, 'index'])->name('index');
        Route::get('/{layanan}', [ClientLayananController::class, 'show'])->name('show');
    });

    // Pesanan
    Route::prefix('pesanan')->name('pesanan.')->group(function () {
        Route::get('/', [ClientPesananController::class, 'index'])->name('index');
        Route::get('/create/{layanan}', [ClientPesananController::class, 'create'])->name('create');
        Route::post('/', [ClientPesananController::class, 'store'])->name('store');
        Route::get('/{pesanan}', [ClientPesananController::class, 'show'])->name('show');
        Route::post('/{pesanan}/approve-preview', [ClientPesananController::class, 'approvePreview'])->name('approve-preview');
        Route::post('/{pesanan}/request-revision', [ClientPesananController::class, 'requestRevision'])->name('request-revision');
        Route::get('/{pesanan}/download-final', [ClientPesananController::class, 'downloadFinal'])->name('download-final');
    });

    // Invoice
    Route::prefix('invoice')->name('invoice.')->group(function () {
        Route::get('/', [ClientInvoiceController::class, 'index'])->name('index');
        Route::get('/{invoice}', [ClientInvoiceController::class, 'show'])->name('show');
        Route::get('/{invoice}/download', [ClientInvoiceController::class, 'download'])->name('download');
    });

    // Pembayaran
    Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
        Route::get('/invoice/{invoice}', [ClientPembayaranController::class, 'invoice'])->name('invoice');
        Route::get('/{invoice_id}/upload', [ClientPembayaranController::class, 'create'])->name('create');
        Route::post('/store', [ClientPembayaranController::class, 'store'])->name('store');
        Route::post('/{invoice_id}/store', [ClientPembayaranController::class, 'store'])->name('store.invoice');
        Route::get('/{invoice_id}/show', [ClientPembayaranController::class, 'show'])->name('show');
        Route::delete('/{pembayaran_id}', [ClientPembayaranController::class, 'destroy'])->name('destroy');
        Route::get('/success/{pembayaran}', [ClientPembayaranController::class, 'success'])->name('success');
    });

    // Profil Client
    Route::get('profil', [ClientProfilController::class, 'index'])->name('profil');
    Route::put('profil', [ClientProfilController::class, 'update'])->name('profil.update');
    Route::put('profil/change-password', [ClientProfilController::class, 'changePassword'])->name('profil.change-password');
});
