<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Pesanan;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['pesanan.client']);

        // Filter berdasarkan tipe
        if ($request->has('tipe') && $request->tipe != '') {
            $query->where('tipe', $request->tipe);
        }

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $invoices = $query->latest()->paginate(15);

        return view('admin.invoice.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        $invoice->load([
            'pesanan.client',
            'pesanan.layanan',
            'pesanan.paket',
            'pesanan.pesananAddons.addon',
            'pembayaran'
        ]);

        return view('admin.invoice.show', compact('invoice'));
    }

    public function createDP(Request $request, Pesanan $pesanan)
    {
        // Cek apakah sudah ada invoice DP
        $existingDP = $pesanan->invoices()->where('tipe', 'DP')->first();
        if ($existingDP) {
            return redirect()->back()
                ->with('error', 'Invoice DP sudah dibuat sebelumnya!');
        }

        // DP = 50% dari total harga
        $jumlahDP = $pesanan->total_harga * 0.5;

        $invoice = Invoice::create([
            'pesanan_id' => $pesanan->id,
            'tipe' => 'DP',
            'jumlah' => $jumlahDP,
            'status' => 'Belum Dibayar',
            'tanggal_jatuh_tempo' => Carbon::now()->addDays(3),
        ]);

        // Update status pesanan
        $pesanan->update([
            'status' => 'Menunggu Pembayaran DP'
        ]);

        return redirect()->back()
            ->with('success', 'Invoice DP berhasil dibuat!');
    }

    public function createPelunasan(Request $request, Pesanan $pesanan)
    {
        // Cek apakah DP sudah lunas
        $invoiceDP = $pesanan->invoices()->where('tipe', 'DP')->first();
        if (!$invoiceDP || $invoiceDP->status !== 'Lunas') {
            return redirect()->back()
                ->with('error', 'Invoice DP harus lunas terlebih dahulu!');
        }

        // Cek apakah sudah ada invoice pelunasan
        $existingPelunasan = $pesanan->invoices()->where('tipe', 'Pelunasan')->first();
        if ($existingPelunasan) {
            return redirect()->back()
                ->with('error', 'Invoice pelunasan sudah dibuat sebelumnya!');
        }

        // Pelunasan = 50% sisanya
        $jumlahPelunasan = $pesanan->total_harga * 0.5;

        $invoice = Invoice::create([
            'pesanan_id' => $pesanan->id,
            'tipe' => 'Pelunasan',
            'jumlah' => $jumlahPelunasan,
            'status' => 'Belum Dibayar',
            'tanggal_jatuh_tempo' => Carbon::now()->addDays(3),
        ]);

        // Update status pesanan
        $pesanan->update([
            'status' => 'Menunggu Pelunasan'
        ]);

        return redirect()->back()
            ->with('success', 'Invoice pelunasan berhasil dibuat!');
    }

    public function pendingPayments()
    {
        $pembayaran = Pembayaran::where('status', 'Menunggu Verifikasi')
            ->with(['invoice.pesanan.client'])
            ->latest()
            ->paginate(15);

        return view('admin.pembayaran.pending', compact('pembayaran'));
    }

    public function verifyPayment(Request $request, Pembayaran $pembayaran)
    {
        if ($pembayaran->status !== 'Menunggu Verifikasi') {
            return redirect()->back()
                ->with('error', 'Pembayaran ini sudah diverifikasi sebelumnya!');
        }

        $validated = $request->validate([
            'catatan_verifikasi' => 'nullable|string',
        ]);

        // Update pembayaran
        $pembayaran->update([
            'status' => 'Diterima',
            'catatan_verifikasi' => $validated['catatan_verifikasi'] ?? 'Pembayaran diterima.',
            'tanggal_verifikasi' => now(),
            'verified_by' => Auth::id(),
        ]);

        // Update invoice menjadi lunas
        $pembayaran->invoice->update([
            'status' => 'Lunas'
        ]);

        // Update status pesanan
        $pesanan = $pembayaran->invoice->pesanan;
        if ($pembayaran->invoice->tipe === 'DP') {
            $pesanan->update(['status' => 'Sedang Diproses']);
        } else {
            // Jika pelunasan, cek apakah file final sudah ada
            if ($pesanan->file_final) {
                $pesanan->update(['status' => 'Selesai']);
            }
        }

        return redirect()->back()
            ->with('success', 'Pembayaran berhasil diverifikasi!');
    }

    public function rejectPayment(Request $request, Pembayaran $pembayaran)
    {
        if ($pembayaran->status !== 'Menunggu Verifikasi') {
            return redirect()->back()
                ->with('error', 'Pembayaran ini sudah diverifikasi sebelumnya!');
        }

        $validated = $request->validate([
            'catatan_verifikasi' => 'required|string',
        ], [
            'catatan_verifikasi.required' => 'Alasan penolakan wajib diisi.',
        ]);

        // Update pembayaran
        $pembayaran->update([
            'status' => 'Ditolak',
            'catatan_verifikasi' => $validated['catatan_verifikasi'],
            'tanggal_verifikasi' => now(),
            'verified_by' => Auth::id(),
        ]);

        return redirect()->back()
            ->with('success', 'Pembayaran ditolak. Client akan diminta upload ulang bukti pembayaran.');
    }

    public function print(Invoice $invoice)
    {
        $invoice->load([
            'pesanan.client',
            'pesanan.layanan',
            'pesanan.paket',
            'pesanan.pesananAddons.addon',
        ]);

        return view('admin.invoice.print', compact('invoice'));
    }
}