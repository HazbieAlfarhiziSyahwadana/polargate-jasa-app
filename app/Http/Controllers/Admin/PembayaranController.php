<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    public function pending()
    {
        $pembayaran = Pembayaran::with(['invoice.pesanan.client', 'invoice.pesanan.layanan'])
            ->where('status', 'Pending')
            ->latest()
            ->paginate(10);

        return view('admin.pembayaran.pending', compact('pembayaran'));
    }

    public function verify(Pembayaran $pembayaran, Request $request)
    {
        $request->validate([
            'catatan_verifikasi' => 'nullable|string',
        ]);

        // Update status pembayaran
        $pembayaran->update([
            'status' => 'Diterima',
            'catatan_verifikasi' => $request->catatan_verifikasi,
            'verified_at' => now(),
            'verified_by' => Auth::id(),
        ]);

        // Update status invoice
        $invoice = $pembayaran->invoice;
        $invoice->update(['status' => 'Lunas']);

        // Update status pesanan
        $pesanan = $invoice->pesanan;
        if ($invoice->tipe == 'DP') {
            $pesanan->update(['status' => 'Sedang Diproses']);
        } elseif ($invoice->tipe == 'Pelunasan') {
            // Cek apakah sudah ada file final
            if ($pesanan->file_final) {
                $pesanan->update(['status' => 'Selesai']);
            }
        }

        return redirect()->route('admin.pembayaran.pending')->with('success', 'Pembayaran berhasil diverifikasi!');
    }

    public function reject(Pembayaran $pembayaran, Request $request)
    {
        $request->validate([
            'catatan_verifikasi' => 'required|string',
        ]);

        $pembayaran->update([
            'status' => 'Ditolak',
            'catatan_verifikasi' => $request->catatan_verifikasi,
            'verified_at' => now(),
            'verified_by' => Auth::id(),
        ]);

        // Kembalikan status invoice ke Belum Dibayar
        $pembayaran->invoice->update(['status' => 'Belum Dibayar']);

        return redirect()->route('admin.pembayaran.pending')->with('success', 'Pembayaran ditolak!');
    }
}