<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Revisi;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class RevisiController extends Controller
{
    public function index(Request $request)
    {
        // ✅ PERBAIKAN: Ganti 'pesanan.user' menjadi 'pesanan.client'
        $query = Revisi::with(['pesanan.client', 'pesanan.layanan'])
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('pesanan', function($q) use ($search) {
                $q->where('kode_pesanan', 'like', "%{$search}%")
                  // ✅ PERBAIKAN: Ganti 'user' menjadi 'client'
                  ->orWhereHas('client', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            })->orWhere('catatan_revisi', 'like', "%{$search}%");
        }

        $revisi = $query->paginate(10);

        return view('admin.revisi.index', compact('revisi'));
    }

    public function show(Revisi $revisi)
    {
        // ✅ PERBAIKAN: Ganti 'pesanan.user' menjadi 'pesanan.client'
        $revisi->load(['pesanan.client', 'pesanan.layanan', 'pesanan.paket']);
        
        return view('admin.revisi.show', compact('revisi'));
    }

    public function updateStatus(Request $request, Revisi $revisi)
    {
        $request->validate([
            'status' => 'required|in:Diminta,Sedang Dikerjakan,Selesai',
            'catatan_admin' => 'nullable|string'
        ]);

        // ✅ TAMBAHAN: Validasi jika status diubah ke Selesai harus ada link preview
        if ($request->status === 'Selesai' && empty($revisi->catatan_admin)) {
            return redirect()->back()
                ->with('error', 'Tidak dapat mengubah status ke Selesai. Silakan upload link preview terlebih dahulu.');
        }

        DB::beginTransaction();
        try {
            $revisi->update([
                'status' => $request->status,
                'tanggal_selesai' => $request->status === 'Selesai' ? now() : null,
            ]);

            // Update status pesanan jika revisi selesai
            if ($request->status === 'Selesai') {
                $revisi->pesanan->update([
                    'status' => 'Menunggu Pelunasan'
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Status revisi berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    /**
     * ✅ TAMBAHAN: Upload link preview hasil revisi
     */
    /**
 * ✅ PERBAIKAN FINAL: Upload link preview hasil revisi
 */
public function uploadLink(Request $request, Revisi $revisi)
{
    $validated = $request->validate([
        'link_preview' => 'required|url',
        'catatan_hasil' => 'nullable|string|max:1000',
    ]);

    try {
        $updateData = [
            'preview_link' => $validated['link_preview'],
            'is_preview_active' => true,
            'preview_expired_at' => now()->addDays(7), // 7 hari kedepan
        ];

        if (!empty($validated['catatan_hasil'])) {
            $updateData['catatan_hasil'] = $validated['catatan_hasil'];
        }

        $revisi->update($updateData);

        return redirect()->back()
            ->with('success', 'Link preview berhasil disimpan');
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Gagal menyimpan link preview: ' . $e->getMessage());
    }
}

    public function downloadFile(Revisi $revisi, $index)
    {
        if (!$revisi->hasFiles() || !isset($revisi->file_referensi[$index])) {
            abort(404, 'File tidak ditemukan');
        }

        $filePath = $revisi->file_referensi[$index];
        
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan di storage');
        }

        return Storage::disk('public')->download($filePath);
    }

    public function destroy(Revisi $revisi)
    {
        DB::beginTransaction();
        try {
            // Hapus file jika ada
            if ($revisi->hasFiles()) {
                foreach ($revisi->file_referensi as $file) {
                    if (Storage::disk('public')->exists($file)) {
                        Storage::disk('public')->delete($file);
                    }
                }
            }

            $revisi->delete();

            DB::commit();

            return redirect()->route('admin.revisi.index')
                ->with('success', 'Revisi berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus revisi: ' . $e->getMessage());
        }
    }
}