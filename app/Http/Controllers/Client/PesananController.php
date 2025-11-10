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
use Illuminate\Support\Facades\File;
use ZipArchive;

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
        'detail_pesanan' => 'required|string', // Form menggunakan detail_pesanan
        'file_pendukung.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,zip,rar|max:5120',
    ]);

    try {
        DB::beginTransaction();

        // Get layanan dan paket
        $layanan = Layanan::findOrFail($validated['layanan_id']);
        $paket = $layanan->paket()->findOrFail($validated['paket_id']);

        // Hitung total harga
        $totalHarga = $paket->harga;

        // ✅ PERBAIKAN: Simpan ke kolom detail_pesanan (bukan brief)
        $pesanan = Pesanan::create([
            'client_id' => Auth::id(),
            'layanan_id' => $validated['layanan_id'],
            'paket_id' => $validated['paket_id'],
            'kode_pesanan' => $this->generateKodePesanan(),
            'detail_pesanan' => $validated['detail_pesanan'], // ✅ Ubah dari 'brief' ke 'detail_pesanan'
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
            $pesanan->update(['file_pendukung' => $filePaths]);
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

        // Load relasi yang diperlukan
        $pesanan->load(['layanan', 'paket', 'addons', 'invoices.pembayaran', 'revisi']);

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

        $pelunasanInvoice = $pesanan->invoices()
            ->where('tipe', 'Pelunasan')
            ->latest()
            ->first();

        if (!$pelunasanInvoice || $pelunasanInvoice->status !== 'Lunas') {
            return redirect()->back()->with('error', 'File final baru dapat diunduh setelah pelunasan dinyatakan lunas.');
        }

        $fileFinal = $pesanan->file_final ?? [];
        if (is_string($fileFinal)) {
            $fileFinal = [$fileFinal];
        }

        $fileFinal = array_filter($fileFinal);

        if (empty($fileFinal)) {
            return redirect()->back()->with('error', 'File final belum tersedia.');
        }

        $basePath = public_path('uploads/final');
        $availableFiles = [];

        foreach ($fileFinal as $fileName) {
            $fullPath = $basePath . DIRECTORY_SEPARATOR . $fileName;
            if (file_exists($fullPath)) {
                $availableFiles[$fileName] = $fullPath;
            }
        }

        if (empty($availableFiles)) {
            return redirect()->back()->with('error', 'File final tidak ditemukan.');
        }

        if (count($availableFiles) === 1) {
            $fileName = array_key_first($availableFiles);
            return response()->download($availableFiles[$fileName], $fileName);
        }

        $tempDir = storage_path('app/tmp');
        if (!File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }

        $zipName = 'final-' . $pesanan->kode_pesanan . '-' . now()->format('YmdHis') . '.zip';
        $zipPath = $tempDir . DIRECTORY_SEPARATOR . $zipName;

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return redirect()->back()->with('error', 'Gagal memproses file final untuk diunduh.');
        }

        foreach ($availableFiles as $fileName => $fullPath) {
            $zip->addFile($fullPath, $fileName);
        }

        $zip->close();

        return response()->download($zipPath, $zipName)->deleteFileAfterSend(true);
    }

   public function cancel(Request $request, Pesanan $pesanan)
{
    // Validasi: hanya bisa membatalkan jika status Menunggu Pembayaran DP dan belum bayar
    if ($pesanan->status !== 'Menunggu Pembayaran DP') {
        return redirect()->back()->with('error', 'Pesanan tidak dapat dibatalkan.');
    }

    // Cek apakah sudah ada pembayaran DP
    $invoiceDP = $pesanan->invoices()->where('tipe', 'DP')->first();
    if ($invoiceDP && $invoiceDP->status === 'Lunas') {
        return redirect()->back()->with('error', 'Pesanan tidak dapat dibatalkan karena DP sudah dibayar.');
    }

    $pesanan->update([
        'status' => 'Dibatalkan'
    ]);

    return redirect()->route('client.pesanan.index')->with('success', 'Pesanan berhasil dibatalkan.');
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