<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Models\Invoice;
use App\Models\Revisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PesananController extends Controller
{
    /**
     * Tampilkan daftar pesanan
     */
    public function index(Request $request)
    {
        $query = Pesanan::with(['client', 'layanan', 'paket', 'invoices']);

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('kode_pesanan', 'like', '%' . $request->search . '%')
                  ->orWhereHas('client', function($q2) use ($request) {
                      $q2->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $pesanan = $query->latest()->paginate(10);

        return view('admin.pesanan.index', compact('pesanan'));
    }

    /**
     * Tampilkan detail pesanan
     */
    public function show(Pesanan $pesanan)
    {
        $pesanan->load([
            'client', 
            'layanan', 
            'paket', 
            'addons', 
            'invoices.pembayaran',
            'revisi'
        ]);

        return view('admin.pesanan.show', compact('pesanan'));
    }

    /**
     * 🔔 API untuk mendapatkan jumlah notifikasi badge
     */
    public function getBadgeCount()
    {
        try {
            // 📦 Pesanan baru (24 jam terakhir)
            $pesananBaru = Pesanan::where('created_at', '>=', now()->subHours(24))
                ->whereNotIn('status', ['Selesai', 'Dibatalkan'])
                ->count();

            // 📋 Menunggu proses
            $menungguProses = Pesanan::whereIn('status', [
                'Menunggu Pembayaran DP',
            ])->count();

            // 💰 Invoice yang butuh verifikasi (statusnya 'Menunggu Verifikasi')
            $pembayaranPending = Invoice::whereHas('pembayaran', function($q) {
                $q->where('status', 'Menunggu Verifikasi');
            })->count();

            // ✏️ Revisi yang menunggu
            $revisiMenunggu = Revisi::whereIn('status', ['Diminta'])
                ->count();

            $totalNotifikasi = $pesananBaru + $menungguProses + $pembayaranPending + $revisiMenunggu;

            return response()->json([
                'success' => true,
                'pesananBaru' => $pesananBaru,
                'menungguProses' => $menungguProses,
                'pembayaranPending' => $pembayaranPending,
                'revisiMenunggu' => $revisiMenunggu,
                'totalNotifikasi' => $totalNotifikasi,
                'timestamp' => now()->toIso8601String()
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error fetching badge count', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error fetching badge count',
                'pesananBaru' => 0,
                'menungguProses' => 0,
                'pembayaranPending' => 0,
                'revisiMenunggu' => 0,
                'totalNotifikasi' => 0,
            ], 500);
        }
    }

    /**
     * Update status pesanan
     */
    public function updateStatus(Request $request, Pesanan $pesanan)
    {
        $request->validate([
            'status' => 'required|string|in:Menunggu Pembayaran DP,Sedang Diproses,Preview Siap,Menunggu Pelunasan,Selesai,Dibatalkan',
            'catatan_admin' => 'nullable|string|max:1000',
        ]);

        try {
            $oldStatus = $pesanan->status;
            
            $pesanan->update([
                'status' => $request->status,
                'catatan_admin' => $request->catatan_admin,
            ]);

            Log::info('Pesanan status updated', [
                'pesanan_id' => $pesanan->id,
                'kode_pesanan' => $pesanan->kode_pesanan,
                'old_status' => $oldStatus,
                'new_status' => $request->status,
                'admin_id' => auth()->id()
            ]);

            return redirect()->back()->with('success', '✅ Status pesanan berhasil diupdate!');

        } catch (\Exception $e) {
            Log::error('Error updating pesanan status', [
                'pesanan_id' => $pesanan->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', '❌ Gagal update status: ' . $e->getMessage());
        }
    }

    /**
     * Upload link preview
     */
    public function uploadPreview(Request $request, Pesanan $pesanan)
    {
        $request->validate([
            'preview_link' => 'required|url|max:500',
            'duration_hours' => 'required|integer|min:1|max:720', // Max 30 hari
        ]);

        try {
            $durationHours = (int) $request->duration_hours;
            $expiredAt = now()->addHours($durationHours);

            $pesanan->update([
                'preview_link' => $request->preview_link,
                'preview_expired_at' => $expiredAt,
                'is_preview_active' => true, // ← PENTING: Set ke true
                'status' => 'Preview Siap',
            ]);

            Log::info('Preview link uploaded', [
                'pesanan_id' => $pesanan->id,
                'kode_pesanan' => $pesanan->kode_pesanan,
                'preview_link' => $request->preview_link,
                'duration_hours' => $durationHours,
                'expired_at' => $expiredAt->format('Y-m-d H:i:s'),
                'admin_id' => auth()->id()
            ]);

            return redirect()->back()->with('success', '✅ Link preview berhasil diupload dan akan aktif selama ' . $durationHours . ' jam!');

        } catch (\Exception $e) {
            Log::error('Error uploading preview link', [
                'pesanan_id' => $pesanan->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', '❌ Gagal upload link preview: ' . $e->getMessage());
        }
    }

    /**
     * Upload file final (multi-file)
     */
    public function uploadFinal(Request $request, Pesanan $pesanan)
    {
        $request->validate([
            'file_final' => 'required|array|min:1',
            'file_final.*' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png,zip,rar|max:10240', // Max 10MB per file
        ]);

        try {
            $filePaths = [];
            
            if ($request->hasFile('file_final')) {
                $uploadPath = public_path('uploads/final');
                
                // Buat folder jika belum ada
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                foreach ($request->file('file_final') as $file) {
                    $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                    $file->move($uploadPath, $filename);
                    $filePaths[] = $filename;
                }
            }

            // Cek apakah pelunasan sudah lunas
            $invoicePelunasan = $pesanan->invoices()
                ->where('tipe', 'Pelunasan')
                ->first();

            $status = ($invoicePelunasan && $invoicePelunasan->status === 'Lunas') 
                ? 'Selesai' 
                : 'Menunggu Pelunasan';

            $pesanan->update([
                'file_final' => $filePaths,
                'status' => $status,
            ]);

            Log::info('Final files uploaded', [
                'pesanan_id' => $pesanan->id,
                'kode_pesanan' => $pesanan->kode_pesanan,
                'file_count' => count($filePaths),
                'files' => $filePaths,
                'new_status' => $status,
                'admin_id' => auth()->id()
            ]);

            return redirect()->back()->with('success', '✅ File final berhasil diupload! Status: ' . $status);

        } catch (\Exception $e) {
            Log::error('Error uploading final files', [
                'pesanan_id' => $pesanan->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', '❌ Gagal upload file final: ' . $e->getMessage());
        }
    }

    /**
     * Hapus link preview
     */
    public function deletePreview(Pesanan $pesanan)
    {
        try {
            $pesanan->update([
                'preview_link' => null,
                'preview_expired_at' => null,
                'is_preview_active' => false,
                'status' => 'Sedang Diproses',
            ]);

            Log::info('Preview link deleted', [
                'pesanan_id' => $pesanan->id,
                'kode_pesanan' => $pesanan->kode_pesanan,
                'admin_id' => auth()->id()
            ]);

            return redirect()->back()->with('success', '🗑️ Link preview berhasil dihapus!');

        } catch (\Exception $e) {
            Log::error('Error deleting preview', [
                'pesanan_id' => $pesanan->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', '❌ Gagal hapus preview: ' . $e->getMessage());
        }
    }

    /**
     * Perpanjang waktu preview - FIX: Ganti parameter dari extend_hours ke duration_hours
     */
    public function extendPreview(Request $request, Pesanan $pesanan)
    {
        $request->validate([
            'duration_hours' => 'required|integer|min:1|max:720', // FIX: Ganti dari extend_hours
        ]);

        try {
            $extendHours = (int) $request->duration_hours; // FIX: Ganti dari extend_hours
            
            // Hitung waktu expired baru dari waktu yang ada atau sekarang
            $currentExpiry = $pesanan->preview_expired_at 
                ? \Carbon\Carbon::parse($pesanan->preview_expired_at) 
                : now();
            
            // Jika sudah kadaluarsa, mulai dari sekarang. Jika belum, tambahkan dari waktu expired yang ada
            if ($currentExpiry->isPast()) {
                $newExpiry = now()->addHours($extendHours);
            } else {
                $newExpiry = $currentExpiry->addHours($extendHours);
            }

            $pesanan->update([
                'preview_expired_at' => $newExpiry,
                'is_preview_active' => true,
            ]);

            Log::info('Preview time extended', [
                'pesanan_id' => $pesanan->id,
                'kode_pesanan' => $pesanan->kode_pesanan,
                'extend_hours' => $extendHours,
                'old_expiry' => $currentExpiry->format('Y-m-d H:i:s'),
                'new_expiry' => $newExpiry->format('Y-m-d H:i:s'),
                'admin_id' => auth()->id()
            ]);

            return redirect()->back()->with('success', '⏰ Waktu preview berhasil diperpanjang ' . $extendHours . ' jam hingga ' . $newExpiry->format('d M Y H:i'));

        } catch (\Exception $e) {
            Log::error('Error extending preview', [
                'pesanan_id' => $pesanan->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', '❌ Gagal perpanjang preview: ' . $e->getMessage());
        }
    }

    /**
     * Hapus file final
     */
    public function deleteFinal(Pesanan $pesanan)
    {
        try {
            // Hapus file dari storage
            if ($pesanan->file_final && is_array($pesanan->file_final)) {
                foreach ($pesanan->file_final as $file) {
                    $filePath = public_path('uploads/final/' . $file);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }

            $pesanan->update([
                'file_final' => null,
                'status' => 'Sedang Diproses',
            ]);

            Log::info('Final files deleted', [
                'pesanan_id' => $pesanan->id,
                'kode_pesanan' => $pesanan->kode_pesanan,
                'admin_id' => auth()->id()
            ]);

            return redirect()->back()->with('success', '🗑️ File final berhasil dihapus!');

        } catch (\Exception $e) {
            Log::error('Error deleting final files', [
                'pesanan_id' => $pesanan->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', '❌ Gagal hapus file final: ' . $e->getMessage());
        }
    }
}