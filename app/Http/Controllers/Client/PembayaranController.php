<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    /**
     * Menampilkan halaman invoice untuk pembayaran
     * Route: GET /client/pembayaran/invoice/{invoice}
     */
    public function invoice($id)
    {
        // Ambil data invoice dengan relasi
        $invoice = Invoice::with([
            'pesanan.client',
            'pesanan.layanan',
            'pesanan.paket',
            'pesanan.addons'
        ])
        ->whereHas('pesanan', function($q) {
            $q->where('client_id', Auth::id());
        })
        ->findOrFail($id);

        // Cek apakah invoice sudah lunas
        if ($invoice->status === 'Lunas') {
            return redirect()->route('client.invoice.index')
                ->with('error', 'Invoice ini sudah lunas.');
        }

        return view('client.pembayaran.invoice', compact('invoice'));
    }

    /**
     * Menampilkan form upload bukti pembayaran
     * Route: GET /client/pembayaran/{invoice_id}/upload
     */
    public function create($invoice_id)
    {
        $invoice = Invoice::with([
            'pesanan.client',
            'pesanan.layanan',
            'pesanan.paket',
            'pesanan.addons',
            'pembayaran' => function($q) {
                $q->latest();
            }
        ])
        ->whereHas('pesanan', function($q) {
            $q->where('client_id', Auth::id());
        })
        ->findOrFail($invoice_id);

        // Cek apakah invoice sudah lunas
        if ($invoice->status === 'Lunas') {
            return redirect()->route('client.invoice.index')
                ->with('error', 'Invoice ini sudah lunas.');
        }

        // Cek apakah ada pembayaran yang sedang diverifikasi
        $pendingPembayaran = $invoice->pembayaran()
            ->where('status', 'Menunggu Verifikasi')
            ->first();

        if ($pendingPembayaran) {
            return redirect()->route('client.invoice.index')
                ->with('error', 'Masih ada pembayaran yang sedang diverifikasi untuk invoice ini.');
        }

        return view('client.pembayaran.create', compact('invoice'));
    }

    /**
     * Proses upload bukti pembayaran
     * Route: POST /client/pembayaran/store
     * Route: POST /client/pembayaran/{invoice_id}/store
     */
    public function store(Request $request, $invoice_id = null)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'metode_pembayaran' => 'required|string',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Cek invoice
        $invoice = Invoice::whereHas('pesanan', function($q) {
            $q->where('client_id', Auth::id());
        })->findOrFail($request->invoice_id);

        // Validasi jumlah invoice
        if (!$invoice->jumlah || $invoice->jumlah <= 0) {
            return redirect()->back()
                ->with('error', 'Total invoice tidak valid. Silakan hubungi admin.');
        }

        // Cek apakah sudah ada pembayaran pending untuk invoice ini
        $existingPembayaran = Pembayaran::where('invoice_id', $invoice->id)
            ->where('status', 'Menunggu Verifikasi')
            ->first();

        if ($existingPembayaran) {
            return redirect()->back()
                ->with('error', 'Masih ada pembayaran yang sedang diverifikasi untuk invoice ini.');
        }

        // Upload bukti pembayaran
        $buktiPath = $request->file('bukti_pembayaran')->store('pembayaran', 'public');

        // Simpan pembayaran
        $pembayaran = Pembayaran::create([
            'invoice_id' => $invoice->id,
            'jumlah_dibayar' => $invoice->jumlah,
            'metode_pembayaran' => $request->metode_pembayaran,
            'bukti_pembayaran' => $buktiPath,
            'status' => 'Menunggu Verifikasi',
        ]);

        // Redirect ke halaman success dengan ID pembayaran
        return redirect()->route('client.pembayaran.success', $pembayaran->id)
            ->with('success', 'Bukti pembayaran berhasil diupload dan menunggu verifikasi admin.');
    }

    /**
     * Menampilkan detail pembayaran
     * Route: GET /client/pembayaran/{invoice_id}/show
     */
    public function show($invoice_id)
    {
        $invoice = Invoice::with([
            'pesanan.client',
            'pembayaran' => function($q) {
                $q->latest();
            }
        ])
        ->whereHas('pesanan', function($q) {
            $q->where('client_id', Auth::id());
        })
        ->findOrFail($invoice_id);

        return view('client.pembayaran.show', compact('invoice'));
    }

    /**
     * Hapus pembayaran (jika ditolak atau sebelum diverifikasi)
     * Route: DELETE /client/pembayaran/{pembayaran_id}
     */
    public function destroy($pembayaran_id)
    {
        $pembayaran = Pembayaran::whereHas('invoice.pesanan', function($q) {
            $q->where('client_id', Auth::id());
        })->findOrFail($pembayaran_id);

        // Hanya bisa hapus jika status Ditolak atau Menunggu Verifikasi
        if (!in_array($pembayaran->status, ['Ditolak', 'Menunggu Verifikasi'])) {
            return redirect()->back()
                ->with('error', 'Pembayaran yang sudah diverifikasi tidak dapat dihapus.');
        }

        // Hapus file bukti pembayaran
        if ($pembayaran->bukti_pembayaran) {
            Storage::disk('public')->delete($pembayaran->bukti_pembayaran);
        }

        $pembayaran->delete();

        return redirect()->back()
            ->with('success', 'Pembayaran berhasil dihapus.');
    }

    /**
     * Halaman sukses setelah upload pembayaran
     * Route: GET /client/pembayaran/success/{id}
     */
    public function success($id)
    {
        // Ambil data pembayaran dengan relasi invoice
        $pembayaran = Pembayaran::with('invoice')
            ->whereHas('invoice.pesanan', function($q) {
                $q->where('client_id', Auth::id());
            })
            ->findOrFail($id);

        return view('client.pembayaran.success', compact('pembayaran'));
    }
}