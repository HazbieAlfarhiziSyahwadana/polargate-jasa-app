<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    /**
     * Tampilkan daftar pembayaran pending
     */
    public function pending()
    {
        $pembayaran = Pembayaran::with(['invoice.pesanan.client', 'invoice.pesanan.layanan'])
            ->where('status', 'Menunggu Verifikasi')
            ->latest()
            ->paginate(10);

        return view('admin.pembayaran.pending', compact('pembayaran'));
    }

    /**
     * Verifikasi dan terima pembayaran
     */
    public function verify(Pembayaran $pembayaran, Request $request)
    {
        $request->validate([
            'catatan_verifikasi' => 'nullable|string|max:1000',
        ]);

        try {
            // Update status pembayaran
            $pembayaran->update([
                'status' => 'Diterima',
                'catatan_verifikasi' => $request->catatan_verifikasi,
                'verified_at' => now(),
                'verified_by' => Auth::id(),
            ]);

            // Update status invoice dan hapus alasan penolakan jika ada
            $invoice = $pembayaran->invoice;
            $invoice->update([
                'status' => 'Lunas',
                'alasan_penolakan' => null // Clear alasan penolakan saat pembayaran diterima
            ]);

            // Update status pesanan
            $pesanan = $invoice->pesanan;
            if ($invoice->tipe == 'DP') {
                $pesanan->update(['status' => 'Sedang Diproses']);
            } elseif ($invoice->tipe == 'Pelunasan') {
                // Cek apakah sudah ada file final
                if ($pesanan->file_final) {
                    $pesanan->update(['status' => 'Selesai']);
                } else {
                    $pesanan->update(['status' => 'Sedang Diproses']);
                }
            }

            return redirect()
                ->route('admin.pembayaran.pending')
                ->with('success', '✅ Pembayaran berhasil diverifikasi! Client akan menerima notifikasi.');
                
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.pembayaran.pending')
                ->with('error', '❌ Gagal memverifikasi pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Tolak pembayaran dengan alasan
     */
    public function reject(Pembayaran $pembayaran, Request $request)
    {
        // Validasi dengan field alasan_penolakan
        $request->validate([
            'alasan_penolakan' => 'required|string|max:1000',
        ], [
            'alasan_penolakan.required' => 'Alasan penolakan wajib diisi',
            'alasan_penolakan.max' => 'Alasan penolakan maksimal 1000 karakter'
        ]);

        try {
            // Update status pembayaran
            $pembayaran->update([
                'status' => 'Ditolak',
                'catatan_verifikasi' => $request->alasan_penolakan, // Simpan juga di pembayaran
                'verified_at' => now(),
                'verified_by' => Auth::id(),
            ]);

            // Update status invoice ke Ditolak dan simpan alasan penolakan
            $invoice = $pembayaran->invoice;
            $invoice->update([
                'status' => 'Ditolak',
                'alasan_penolakan' => $request->alasan_penolakan // PENTING: Simpan alasan penolakan di invoice
            ]);

            // Update status pesanan
            $pesanan = $invoice->pesanan;
            if ($pesanan) {
                $pesanan->update([
                    'status' => $invoice->tipe === 'Pelunasan'
                        ? 'Menunggu Pelunasan'
                        : 'Menunggu Pembayaran DP'
                ]);
            }

            return redirect()
                ->route('admin.pembayaran.pending')
                ->with('success', '⚠️ Pembayaran ditolak! Client akan menerima notifikasi dengan alasan penolakan.');
                
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.pembayaran.pending')
                ->with('error', '❌ Gagal menolak pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail pembayaran
     */
    public function show(Pembayaran $pembayaran)
    {
        $pembayaran->load(['invoice.pesanan.client', 'invoice.pesanan.layanan']);
        
        return view('admin.pembayaran.show', compact('pembayaran'));
    }

    /**
     * Hapus pembayaran (jika diperlukan)
     */
    public function destroy(Pembayaran $pembayaran)
    {
        try {
            // Hapus file bukti pembayaran jika ada
            if ($pembayaran->bukti_pembayaran && \Storage::exists($pembayaran->bukti_pembayaran)) {
                \Storage::delete($pembayaran->bukti_pembayaran);
            }

            $pembayaran->delete();

            return redirect()
                ->route('admin.pembayaran.pending')
                ->with('success', '🗑️ Pembayaran berhasil dihapus!');
                
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.pembayaran.pending')
                ->with('error', '❌ Gagal menghapus pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Filter pembayaran berdasarkan status/tanggal
     */
    public function filter(Request $request)
    {
        $query = Pembayaran::with(['invoice.pesanan.client', 'invoice.pesanan.layanan']);

        // Filter status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter tanggal
        if ($request->has('dari_tanggal') && $request->dari_tanggal != '') {
            $query->whereDate('created_at', '>=', $request->dari_tanggal);
        }

        if ($request->has('sampai_tanggal') && $request->sampai_tanggal != '') {
            $query->whereDate('created_at', '<=', $request->sampai_tanggal);
        }

        $pembayaran = $query->latest()->paginate(10);

        return view('admin.pembayaran.index', compact('pembayaran'));
    }

    /**
     * Tampilkan semua pembayaran (index)
     */
    public function index()
    {
        $pembayaran = Pembayaran::with(['invoice.pesanan.client'])
            ->latest()
            ->paginate(15);

        return view('admin.pembayaran.index', compact('pembayaran'));
    }
}