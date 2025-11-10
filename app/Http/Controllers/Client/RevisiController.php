<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Revisi;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RevisiController extends Controller
{
    public function index(Request $request)
    {
        // Deteksi kolom yang digunakan untuk user
        $userColumn = $this->getUserColumn();
        
        $query = Revisi::with(['pesanan.layanan', 'pesanan.paket'])
            ->whereHas('pesanan', function($q) use ($userColumn) {
                $q->where($userColumn, auth()->id());
            })
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $statusMap = [
                'diminta' => 'Diminta',
                'sedang dikerjakan' => 'Sedang Dikerjakan',
                'selesai' => 'Selesai'
            ];
            $status = $statusMap[strtolower($request->status)] ?? $request->status;
            $query->where('status', $status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search, $userColumn) {
                $q->whereHas('pesanan', function($q2) use ($search, $userColumn) {
                    $q2->where('kode_pesanan', 'like', "%{$search}%")
                       ->where($userColumn, auth()->id());
                })
                ->orWhere(function($q3) use ($search, $userColumn) {
                    $q3->where('catatan_revisi', 'like', "%{$search}%")
                       ->whereHas('pesanan', function($q4) use ($userColumn) {
                           $q4->where($userColumn, auth()->id());
                       });
                });
            });
        }

        $revisi = $query->paginate(10);

        return view('client.revisi.index', compact('revisi'));
    }

    public function create(Pesanan $pesanan)
    {
        // Cek apakah pesanan milik user dengan deteksi otomatis kolom
        if (!$this->isPesananOwnedByUser($pesanan)) {
            abort(403, 'Anda tidak memiliki akses ke pesanan ini');
        }

        // Cek apakah status memungkinkan untuk revisi
        if (!in_array($pesanan->status, ['Preview Siap', 'Menunggu Pelunasan'])) {
            return redirect()->back()->with('error', 'Pesanan tidak dalam status yang dapat direvisi');
        }

        // Cek kuota revisi
        $totalRevisi = $pesanan->revisi()->count();
        $kuotaRevisi = $pesanan->paket->jumlah_revisi ?? 0;

        if ($totalRevisi >= $kuotaRevisi) {
            return redirect()->back()->with('error', 'Kuota revisi Anda telah habis');
        }

        return view('client.revisi.create', compact('pesanan', 'totalRevisi', 'kuotaRevisi'));
    }

    public function store(Request $request, Pesanan $pesanan)
    {
        // Cek apakah pesanan milik user
        if (!$this->isPesananOwnedByUser($pesanan)) {
            abort(403, 'Anda tidak memiliki akses ke pesanan ini');
        }

        // Cek kuota revisi
        $totalRevisi = $pesanan->revisi()->count();
        $kuotaRevisi = $pesanan->paket->jumlah_revisi ?? 0;

        if ($totalRevisi >= $kuotaRevisi) {
            return redirect()->back()->with('error', 'Kuota revisi Anda telah habis');
        }

        $request->validate([
            'catatan_revisi' => 'required|string|min:10',
            'file_referensi.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,zip|max:10240',
            'link_referensi.*' => 'nullable|url'
        ]);

        DB::beginTransaction();
        try {
            $files = [];
            $fileMetadata = [];
            $links = [];

            // Upload files jika ada
            if ($request->hasFile('file_referensi')) {
                foreach ($request->file('file_referensi') as $file) {
                    try {
                        // Simpan dengan cara manual untuk debugging
                        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                        $destinationPath = public_path('storage/revisi');
                        
                        // Buat folder jika belum ada
                        if (!file_exists($destinationPath)) {
                            mkdir($destinationPath, 0755, true);
                        }
                        
                        // Move file
                        $file->move($destinationPath, $filename);
                        $path = 'revisi/' . $filename;
                        
                        $files[] = $path;
                        
                        // Simpan metadata file
                        $fileMetadata[] = [
                            'original_name' => $file->getClientOriginalName(),
                            'size' => $file->getSize(),
                            'mime_type' => $file->getMimeType(),
                            'uploaded_at' => now()->toDateTimeString()
                        ];
                    } catch (\Exception $e) {
                        \Log::error('Error uploading file: ' . $e->getMessage());
                        continue;
                    }
                }
            }

            // Ambil link referensi jika ada
            if ($request->filled('link_referensi')) {
                $links = array_filter($request->link_referensi, function($link) {
                    return !empty($link);
                });
            }

            // Hitung nomor revisi
            $revisiKe = $totalRevisi + 1;

            // Buat revisi
            $revisi = Revisi::create([
                'pesanan_id' => $pesanan->id,
                'revisi_ke' => $revisiKe,
                'catatan_revisi' => $request->catatan_revisi,
                'file_referensi' => !empty($files) ? $files : null,
                'file_metadata' => !empty($fileMetadata) ? $fileMetadata : null,
                'link_referensi' => !empty($links) ? array_values($links) : null,
                'status' => 'Diminta',
            ]);

            // Update status pesanan
            $pesanan->update([
                'status' => 'Sedang Diproses'
            ]);

            DB::commit();

            return redirect()->route('client.revisi.index')
                ->with('success', 'Permintaan revisi berhasil dikirim');
        } catch (\Exception $e) {
            DB::rollBack();

            // Hapus file yang sudah diupload jika terjadi error
            if (!empty($files)) {
                foreach ($files as $file) {
                    if (Storage::disk('public')->exists($file)) {
                        Storage::disk('public')->delete($file);
                    }
                }
            }

            return redirect()->back()
                ->with('error', 'Gagal mengirim revisi: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Revisi $revisi)
    {
        // Cek apakah revisi milik user
        if (!$this->isPesananOwnedByUser($revisi->pesanan)) {
            abort(403, 'Anda tidak memiliki akses ke revisi ini');
        }

        $revisi->load(['pesanan.layanan', 'pesanan.paket']);

        return view('client.revisi.show', compact('revisi'));
    }

    public function downloadFile(Revisi $revisi, $index)
    {
        // Cek apakah revisi milik user
        if (!$this->isPesananOwnedByUser($revisi->pesanan)) {
            abort(403, 'Anda tidak memiliki akses ke file ini');
        }

        if (empty($revisi->file_referensi) || !isset($revisi->file_referensi[$index])) {
            abort(404, 'File tidak ditemukan');
        }

        $filePath = $revisi->file_referensi[$index];
        
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan di storage');
        }

        return Storage::disk('public')->download($filePath);
    }

    /**
     * Helper method untuk deteksi kolom user yang digunakan
     */
    private function getUserColumn(): string
    {
        // Cek kolom mana yang ada di tabel pesanan
        $pesananTable = (new Pesanan())->getTable();
        $columns = \Schema::getColumnListing($pesananTable);
        
        if (in_array('client_id', $columns)) {
            return 'client_id';
        } elseif (in_array('user_id', $columns)) {
            return 'user_id';
        }
        
        // Default ke user_id jika tidak ketemu
        return 'user_id';
    }

    /**
     * Helper method untuk cek kepemilikan pesanan
     */
    private function isPesananOwnedByUser(Pesanan $pesanan): bool
    {
        $userColumn = $this->getUserColumn();
        return $pesanan->{$userColumn} == auth()->id();
    }
}