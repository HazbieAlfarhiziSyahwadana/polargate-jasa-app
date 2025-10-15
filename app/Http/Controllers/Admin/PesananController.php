<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Invoice;
use App\Helpers\FileHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        $query = Pesanan::with(['client', 'layanan', 'paket']);

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_pesanan', 'like', "%{$search}%")
                  ->orWhereHas('client', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $pesanan = $query->latest()->paginate(15);

        return view('admin.pesanan.index', compact('pesanan'));
    }

    public function show(Pesanan $pesanan)
    {
        $pesanan->load([
            'client',
            'layanan',
            'paket',
            'pesananAddons.addon',
            'invoices',
            'revisi'
        ]);

        return view('admin.pesanan.show', compact('pesanan'));
    }

    public function updateStatus(Request $request, Pesanan $pesanan)
    {
        $validated = $request->validate([
            'status' => 'required|in:Menunggu Pembayaran DP,DP Dibayar - Menunggu Verifikasi,Sedang Diproses,Preview Siap,Revisi Diminta,Menunggu Pelunasan,Pelunasan Dibayar - Menunggu Verifikasi,Selesai,Dibatalkan',
            'catatan' => 'nullable|string',
        ]);

        $pesanan->update([
            'status' => $validated['status']
        ]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui!');
    }

    public function uploadPreview(Request $request, Pesanan $pesanan)
    {
        $validated = $request->validate([
            'preview_link' => 'required|url',
            'duration_hours' => 'nullable|integer|in:6,12,24,48,72,168',
        ], [
            'preview_link.required' => 'Link preview wajib diisi.',
            'preview_link.url' => 'Link preview harus berupa URL yang valid.',
        ]);

        $duration = $validated['duration_hours'] ?? 24;
        $duration = (int) $duration;
        $expiredAt = Carbon::now()->addHours($duration);

        $pesanan->update([
            'preview_link' => $validated['preview_link'],
            'preview_expired_at' => $expiredAt,
            'is_preview_active' => true,
            'status' => 'Preview Siap'
        ]);

        return redirect()->back()->with('success', "Preview berhasil diunggah dan aktif {$duration} jam.");
    }

    public function extendPreview(Request $request, Pesanan $pesanan)
    {
        if (!$pesanan->preview_link) {
            return redirect()->back()->with('error', 'Tidak ada preview yang bisa diperpanjang.');
        }

        $validated = $request->validate([
            'duration_hours' => 'required|integer|in:6,12,24,48,72,168',
        ]);

        $baseTime = $pesanan->preview_expired_at && Carbon::parse($pesanan->preview_expired_at)->isFuture()
            ? Carbon::parse($pesanan->preview_expired_at)
            : Carbon::now();

        $hoursToAdd = (int) $validated['duration_hours'];
        $newExpiry = $baseTime->copy()->addHours($hoursToAdd);

        $pesanan->update([
            'preview_expired_at' => $newExpiry,
            'is_preview_active' => true,
        ]);

        return redirect()->back()->with('success', "Link preview diperpanjang {$hoursToAdd} jam.");
    }

    public function deletePreview(Pesanan $pesanan)
    {
        if (!$pesanan->preview_link) {
            return redirect()->back()->with('error', 'Preview sudah tidak tersedia.');
        }

        $pesanan->update([
            'preview_link' => null,
            'preview_expired_at' => null,
            'is_preview_active' => false,
            'status' => 'Sedang Diproses',
        ]);

        return redirect()->back()->with('success', 'Preview berhasil dihapus.');
    }

    public function uploadFinal(Request $request, Pesanan $pesanan)
    {
        $validated = $request->validate([
            'file_final' => 'required|array',
            'file_final.*' => 'required|file|max:102400', // Max 100MB per file
        ], [
            'file_final.required' => 'File final wajib diunggah.',
            'file_final.*.max' => 'Ukuran file maksimal 100MB.',
        ]);

        // Upload multiple files
        $uploadedFiles = FileHelper::uploadMultipleFiles(
            $request->file('file_final'),
            'final'
        );

        $existingFiles = $pesanan->file_final ?? [];
        if (is_string($existingFiles)) {
            $decoded = json_decode($existingFiles, true);
            $existingFiles = is_array($decoded) ? $decoded : [];
        }

        if (!empty($existingFiles)) {
            FileHelper::deleteMultipleFiles('final', $existingFiles);
        }

        $pesanan->update([
            'file_final' => $uploadedFiles
        ]);

        return redirect()->back()->with('success', 'File final berhasil diperbarui dan akan tersedia setelah pelunasan lunas.');
    }
}
