<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use App\Models\Pesanan;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        $query = Pesanan::with(['layanan', 'paket', 'invoices'])
            ->where('client_id', Auth::id());

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pesanan = $query->latest()->paginate(10);

        return view('client.pesanan.index', compact('pesanan'));
    }

    public function create(Layanan $layanan)
    {
        // Pastikan layanan aktif
        if (!$layanan->is_active) {
            abort(404);
        }

        // Load relasi dengan filter is_active
        $layanan->load([
            'paket' => function($query) {
                $query->where('is_active', true)->orderBy('harga', 'asc');
            },
            'addons' => function($query) {
                $query->where('is_active', true)->orderBy('harga', 'asc');
            }
        ]);

        return view('client.pesanan.create', compact('layanan'));
    }

   public function store(Request $request)
{
    $validated = $request->validate([
        'layanan_id' => 'required|exists:layanan,id',
        'paket_id' => 'required|exists:paket,id',
        'addons' => 'nullable|array',
        'addons.*' => 'exists:addons,id',
        'detail_pesanan' => 'required|string',
        'file_pendukung.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,zip,rar|max:5120',
    ]);

    try {
        DB::beginTransaction();

        // Get layanan dan paket
        $layanan = Layanan::findOrFail($validated['layanan_id']);
        $paket = $layanan->paket()->findOrFail($validated['paket_id']);

        // Hitung total harga
        $totalHarga = $paket->harga;

        // Buat pesanan
        $pesanan = Pesanan::create([
            'client_id' => Auth::id(),
            'layanan_id' => $validated['layanan_id'],
            'paket_id' => $validated['paket_id'],
            'kode_pesanan' => $this->generateKodePesanan(),
            'brief' => $validated['detail_pesanan'], // ← INI YANG DITAMBAHKAN
            'harga_paket' => $paket->harga,
            'total_harga' => $totalHarga,
            'status' => 'Menunggu Pembayaran DP',
        ]);

        // Attach addons jika ada
        if ($request->filled('addons')) {
            foreach ($request->addons as $addonId) {
                $addon = $layanan->addons()->find($addonId);
                if ($addon) {
                    $pesanan->addons()->attach($addonId, ['harga' => $addon->harga]);
                    $totalHarga += $addon->harga;
                }
            }

            // Update total harga setelah addon
            $pesanan->update(['total_harga' => $totalHarga]);
        }

        // Upload file pendukung jika ada
        if ($request->hasFile('file_pendukung')) {
            $filePaths = [];
            foreach ($request->file('file_pendukung') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/pesanan'), $filename);
                $filePaths[] = $filename;
            }
            $pesanan->update(['file_pendukung' => json_encode($filePaths)]);
        }

        // Buat Invoice DP (50%)
        $jumlahDP = $totalHarga * 0.5;
        Invoice::create([
            'pesanan_id' => $pesanan->id,
            'nomor_invoice' => $this->generateNomorInvoice(),
            'tipe' => 'DP',
            'jumlah' => $jumlahDP,
            'status' => 'Belum Dibayar',
            'tanggal_jatuh_tempo' => now()->addDays(3),
        ]);

        DB::commit();

        return redirect()->route('client.pesanan.show', $pesanan)
            ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran DP.');

    } catch (\Exception $e) {
        DB::rollBack();
        
        return redirect()->back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

    public function show(Pesanan $pesanan)
    {
        // Pastikan pesanan milik user yang login
        if ($pesanan->client_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $pesanan->load(['layanan', 'paket', 'addons', 'invoices.pembayaran']);

        return view('client.pesanan.show', compact('pesanan'));
    }

    public function approvePreview(Pesanan $pesanan)
    {
        // Pastikan pesanan milik user yang login
        if ($pesanan->client_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Update status
        $pesanan->update(['status' => 'Menunggu Pelunasan']);

        // Buat Invoice Pelunasan (50%)
        $jumlahPelunasan = $pesanan->total_harga * 0.5;
        Invoice::create([
            'pesanan_id' => $pesanan->id,
            'nomor_invoice' => $this->generateNomorInvoice(),
            'tipe' => 'Pelunasan',
            'jumlah' => $jumlahPelunasan,
            'status' => 'Belum Dibayar',
            'tanggal_jatuh_tempo' => now()->addDays(3),
        ]);

        return redirect()->back()->with('success', 'Preview disetujui! Silakan lakukan pelunasan.');
    }

    public function requestRevision(Pesanan $pesanan, Request $request)
    {
        // Pastikan pesanan milik user yang login
        if ($pesanan->client_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'catatan_revisi' => 'required|string',
        ]);

        // Update status dan catatan
        $pesanan->update([
            'status' => 'Sedang Diproses',
            'catatan_revisi' => $request->catatan_revisi,
        ]);

        return redirect()->back()->with('success', 'Permintaan revisi telah dikirim.');
    }

    public function downloadFinal(Pesanan $pesanan)
    {
        // Pastikan pesanan milik user yang login
        if ($pesanan->client_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if (!$pesanan->file_final) {
            return redirect()->back()->with('error', 'File final belum tersedia.');
        }

        $filePath = public_path('uploads/pesanan/' . $pesanan->file_final);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        return response()->download($filePath);
    }

    // Helper functions
    private function generateKodePesanan()
    {
        $prefix = 'PSN';
        $date = date('Ymd');
        $random = strtoupper(Str::random(4));
        return $prefix . '-' . $date . '-' . $random;
    }

    private function generateNomorInvoice()
    {
        $prefix = 'INV';
        $date = date('Ymd');
        $random = strtoupper(Str::random(4));
        return $prefix . '-' . $date . '-' . $random;
    }
}