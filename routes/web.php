<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
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
use App\Http\Controllers\Admin\RevisiController as AdminRevisiController;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\Client\LayananController as ClientLayananController;
use App\Http\Controllers\Client\PesananController as ClientPesananController;
use App\Http\Controllers\Client\InvoiceController as ClientInvoiceController;
use App\Http\Controllers\Client\PembayaranController as ClientPembayaranController;
use App\Http\Controllers\Client\ProfilController as ClientProfilController;
use App\Http\Controllers\Client\RevisiController as ClientRevisiController;
use App\Http\Controllers\Client\ClientNotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ===== LANDING PAGE =====
Route::get('/', [LandingController::class, 'index'])->name('landing');

// ===== ROUTE AUTHENTICATION =====
// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Register
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// ===== PASSWORD RESET ROUTES =====
// Halaman form request reset password (lupa password)
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request')
    ->middleware('guest');

// Kirim link reset password ke email
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email')
    ->middleware('guest');

// Halaman form reset password (dengan token)
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->name('password.reset')
    ->middleware('guest');

// Proses reset password
Route::post('password/reset', [ResetPasswordController::class, 'reset'])
    ->name('password.update')
    ->middleware('guest');

// ===== TEST EMAIL ROUTE (DEVELOPMENT ONLY - HAPUS DI PRODUCTION) =====
Route::get('/test-email/{type?}', function ($type = 'dp_verified') {
    $invoice = \App\Models\Invoice::with(['pesanan.client', 'pesanan.layanan'])->first();
    
    if (!$invoice) {
        return '<h1>❌ Data invoice tidak ditemukan</h1><p>Pastikan sudah ada data invoice di database.</p>';
    }
    
    $pembayaran = $invoice->pembayaran()->first();
    
    if (!$pembayaran) {
        // Buat pembayaran dummy untuk preview
        $pembayaran = new \App\Models\Pembayaran([
            'invoice_id' => $invoice->id,
            'jumlah_dibayar' => $invoice->jumlah,
            'metode_pembayaran' => 'Transfer Bank',
            'status' => 'Diterima',
            'catatan_verifikasi' => 'Pembayaran telah diverifikasi dan diterima.',
        ]);
    }
    
    // Validasi type
    if (!in_array($type, ['dp_verified', 'pelunasan_verified', 'rejected'])) {
        $type = 'dp_verified';
    }
    
    // Jika type rejected, set alasan penolakan
    if ($type === 'rejected') {
        $invoice->alasan_penolakan = 'Bukti transfer tidak jelas. Mohon upload ulang dengan kualitas yang lebih baik.';
        $invoice->status = 'Ditolak';
    } else {
        $invoice->status = 'Lunas';
        $invoice->alasan_penolakan = null;
    }
    
    return new \App\Mail\InvoiceMail($invoice, $pembayaran, $type);
})->name('test.email');

// ===== ROUTE ADMIN (SUPERADMIN) =====
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Kelola Layanan
    // Dalam group admin layanan
Route::prefix('layanan')->name('layanan.')->group(function () {
    Route::get('/', [AdminLayananController::class, 'index'])->name('index');
    Route::get('/search', [AdminLayananController::class, 'search'])->name('search'); // 🔍 SEARCH
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
        Route::get('/search', [AdminPaketController::class, 'search'])->name('search'); // 🔍 SEARCH
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
        Route::get('/search', [AdminAddonController::class, 'search'])->name('search'); // 🔍 SEARCH
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
        Route::get('/search', [AdminPesananController::class, 'search'])->name('search'); // 🔍 SEARCH
        Route::get('/badge-count', [AdminPesananController::class, 'getBadgeCount'])->name('badge-count');
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
        Route::get('/search', [AdminClientController::class, 'search'])->name('search'); // 🔍 SEARCH
        Route::get('/{user}', [AdminClientController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [AdminClientController::class, 'edit'])->name('edit');
        Route::put('/{user}', [AdminClientController::class, 'update'])->name('update');
        Route::patch('/{user}/toggle-status', [AdminClientController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/{user}', [AdminClientController::class, 'destroy'])->name('destroy');
    });

    // Kelola Invoice
    Route::prefix('invoice')->name('invoice.')->group(function () {
        Route::get('/', [AdminInvoiceController::class, 'index'])->name('index');
        Route::get('/search', [AdminInvoiceController::class, 'search'])->name('search'); // 🔍 SEARCH
        Route::post('/{pesanan}/create-dp', [AdminInvoiceController::class, 'createDP'])->name('create-dp');
        Route::post('/{pesanan}/create-pelunasan', [AdminInvoiceController::class, 'createPelunasan'])->name('create-pelunasan');
        Route::get('/{invoice}', [AdminInvoiceController::class, 'show'])->name('show');
        Route::get('/{invoice}/download', [AdminInvoiceController::class, 'download'])->name('download');
    });

    // Verifikasi Pembayaran
    Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
        Route::get('/', [AdminPembayaranController::class, 'index'])->name('index');
        Route::get('/search', [AdminPembayaranController::class, 'search'])->name('search'); // 🔍 SEARCH
        Route::get('/pending', [AdminPembayaranController::class, 'pending'])->name('pending');
        Route::get('/{pembayaran}', [AdminPembayaranController::class, 'show'])->name('show');
        Route::post('/{pembayaran}/verify', [AdminPembayaranController::class, 'verify'])->name('verify');
        Route::post('/{pembayaran}/reject', [AdminPembayaranController::class, 'reject'])->name('reject');
        Route::delete('/{pembayaran}', [AdminPembayaranController::class, 'destroy'])->name('destroy');
        Route::get('/filter', [AdminPembayaranController::class, 'filter'])->name('filter');
    });

    // ✅ Kelola Revisi (UPDATED)
    Route::prefix('revisi')->name('revisi.')->group(function () {
        Route::get('/', [AdminRevisiController::class, 'index'])->name('index');
        Route::get('/search', [AdminRevisiController::class, 'search'])->name('search'); // 🔍 SEARCH
        Route::get('/{revisi}', [AdminRevisiController::class, 'show'])->name('show');
        Route::patch('/{revisi}/update-status', [AdminRevisiController::class, 'updateStatus'])->name('update-status');
        Route::patch('/{revisi}/upload-link', [AdminRevisiController::class, 'uploadLink'])->name('upload-link'); // ✅ BARU
        Route::get('/{revisi}/download/{index}', [AdminRevisiController::class, 'downloadFile'])->name('download-file');
        Route::delete('/{revisi}', [AdminRevisiController::class, 'destroy'])->name('destroy');
    });

    // Laporan
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/keuangan', [AdminLaporanController::class, 'keuangan'])->name('keuangan');
        Route::get('/pemesanan', [AdminLaporanController::class, 'pemesanan'])->name('pemesanan');
        Route::get('/client', [AdminLaporanController::class, 'client'])->name('client');
        Route::get('/export-keuangan', [AdminLaporanController::class, 'exportKeuangan'])->name('export-keuangan');
        Route::get('/export-pemesanan', [AdminLaporanController::class, 'exportPemesanan'])->name('export-pemesanan');
    });

    // Pengaturan Landing Page
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
    Route::get('/dashboard/realtime-data', [ClientDashboardController::class, 'realtimeData'])->name('dashboard.realtime');

    // Lihat Layanan
    // Dalam group client layanan
Route::prefix('layanan')->name('layanan.')->group(function () {
    Route::get('/', [ClientLayananController::class, 'index'])->name('index');
    Route::get('/search', [ClientLayananController::class, 'search'])->name('search');
    Route::get('/kategori/{kategori}', [ClientLayananController::class, 'byCategory'])->name('category');
    Route::get('/{layanan}', [ClientLayananController::class, 'show'])->name('show');
    Route::get('/api/featured', [ClientLayananController::class, 'getFeatured'])->name('api.featured');
    Route::get('/api/stats', [ClientLayananController::class, 'getStats'])->name('api.stats');
});
    // Pesanan
    Route::prefix('pesanan')->name('pesanan.')->group(function () {
        Route::get('/', [ClientPesananController::class, 'index'])->name('index');
        Route::get('/search', [ClientPesananController::class, 'search'])->name('search'); // 🔍 SEARCH
        Route::get('/create/{layanan}', [ClientPesananController::class, 'create'])->name('create');
        Route::post('/', [ClientPesananController::class, 'store'])->name('store');
        Route::get('/{pesanan}', [ClientPesananController::class, 'show'])->name('show');
        Route::patch('/{pesanan}/cancel', [ClientPesananController::class, 'cancel'])->name('cancel');
        Route::post('/{pesanan}/approve-preview', [ClientPesananController::class, 'approvePreview'])->name('approve-preview');
        Route::post('/{pesanan}/request-revision', [ClientPesananController::class, 'requestRevision'])->name('request-revision');
        Route::get('/{pesanan}/download-final', [ClientPesananController::class, 'downloadFinal'])->name('download-final');
    });

    // Invoice
    Route::prefix('invoice')->name('invoice.')->group(function () {
        Route::get('/', [ClientInvoiceController::class, 'index'])->name('index');
        Route::get('/search', [ClientInvoiceController::class, 'search'])->name('search'); // 🔍 SEARCH
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

    // Revisi
    Route::prefix('revisi')->name('revisi.')->group(function () {
        Route::get('/', [ClientRevisiController::class, 'index'])->name('index');
        Route::get('/search', [ClientRevisiController::class, 'search'])->name('search'); // 🔍 SEARCH
        Route::get('/pesanan/{pesanan}/create', [ClientRevisiController::class, 'create'])->name('create');
        Route::post('/pesanan/{pesanan}', [ClientRevisiController::class, 'store'])->name('store');
        Route::get('/{revisi}', [ClientRevisiController::class, 'show'])->name('show');
        Route::get('/{revisi}/download/{index}', [ClientRevisiController::class, 'downloadFile'])->name('download-file');
        
        // ✅ Routes untuk file hasil revisi
        Route::get('/{revisi}/file-hasil', [ClientRevisiController::class, 'getFileHasil'])->name('file-hasil');
        Route::get('/{revisi}/download-hasil/{index}', [ClientRevisiController::class, 'downloadFileHasil'])->name('download-hasil');
    });

    // Notification Routes
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/badge-count', [ClientNotificationController::class, 'getBadgeCount'])->name('badge-count');
        Route::post('/mark-as-read', [ClientNotificationController::class, 'markAsRead'])->name('mark-as-read');
        Route::get('/payment-status/{invoice}', [ClientNotificationController::class, 'checkPaymentStatus'])->name('payment-status');
    });

    // Profil Client
    Route::get('profil', [ClientProfilController::class, 'index'])->name('profil');
    Route::put('profil', [ClientProfilController::class, 'update'])->name('profil.update');
    Route::put('profil/change-password', [ClientProfilController::class, 'changePassword'])->name('profil.change-password');
});