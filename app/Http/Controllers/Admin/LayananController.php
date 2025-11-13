<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Layanan;
use App\Helpers\FileHelper;

class LayananController extends Controller
{
    public function index(Request $request)
    {
        $query = Layanan::withCount('pesanan')->latest();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_layanan', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhere('harga_mulai', 'like', "%{$search}%");
            });
        }

        // Filter kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }

        // Filter status
        if ($request->has('status') && $request->status != '') {
            $query->where('is_active', $request->status == 'aktif');
        }

        $layanan = $query->get();

        return view('admin.layanan.index', compact('layanan'));
    }

    public function create()
    {
        return view('admin.layanan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori' => 'required|in:Multimedia,IT',
            'nama_layanan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'video_url' => 'nullable|url|max:500',
            'harga_mulai' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ], [
            'kategori.required' => 'Kategori wajib dipilih.',
            'nama_layanan.required' => 'Nama layanan wajib diisi.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus jpg, jpeg, atau png.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
            'video_url.url' => 'URL video harus berupa URL yang valid.',
            'video_url.max' => 'URL video maksimal 500 karakter.',
            'harga_mulai.required' => 'Harga mulai wajib diisi.',
            'harga_mulai.numeric' => 'Harga harus berupa angka.',
        ]);

        // Upload gambar jika ada
        if ($request->hasFile('gambar')) {
            // Validasi gambar
            $validation = FileHelper::validateImage($request->file('gambar'), 2048);
            if (!$validation['valid']) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', $validation['message']);
            }

            $validated['gambar'] = FileHelper::uploadFile($request->file('gambar'), 'layanan');
        }

        // Jika ada video URL, hapus gambar yang diupload (video prioritas)
        if ($request->filled('video_url')) {
            $validated['gambar'] = null;
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;

        Layanan::create($validated);

        return redirect()->route('admin.layanan.index')
            ->with('success', 'Layanan berhasil ditambahkan!');
    }

    public function edit(Layanan $layanan)
    {
        return view('admin.layanan.edit', compact('layanan'));
    }

    public function update(Request $request, Layanan $layanan)
    {
        $validated = $request->validate([
            'kategori' => 'required|in:Multimedia,IT',
            'nama_layanan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'video_url' => 'nullable|url|max:500',
            'harga_mulai' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ], [
            'video_url.url' => 'URL video harus berupa URL yang valid.',
            'video_url.max' => 'URL video maksimal 500 karakter.',
        ]);

        // Upload gambar baru jika ada
        if ($request->hasFile('gambar')) {
            // Validasi gambar
            $validation = FileHelper::validateImage($request->file('gambar'), 2048);
            if (!$validation['valid']) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', $validation['message']);
            }

            $validated['gambar'] = FileHelper::uploadFile(
                $request->file('gambar'),
                'layanan',
                $layanan->gambar
            );
        } else {
            // Jika tidak ada gambar baru, pertahankan gambar lama
            $validated['gambar'] = $layanan->gambar;
        }

        // Jika ada video URL, hapus gambar
        if ($request->filled('video_url')) {
            $validated['gambar'] = null;
            
            // Hapus file gambar lama jika ada
            if ($layanan->gambar) {
                FileHelper::deleteFile('layanan', $layanan->gambar);
            }
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $layanan->update($validated);

        return redirect()->route('admin.layanan.index')
            ->with('success', 'Layanan berhasil diperbarui!');
    }

    public function destroy(Layanan $layanan)
    {
        // Cek apakah layanan memiliki pesanan
        if ($layanan->pesanan()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Layanan tidak dapat dihapus karena sudah memiliki pesanan!');
        }

        // Hapus gambar jika ada
        if ($layanan->gambar) {
            FileHelper::deleteFile('layanan', $layanan->gambar);
        }

        $layanan->delete();

        return redirect()->route('admin.layanan.index')
            ->with('success', 'Layanan berhasil dihapus!');
    }

    public function toggleStatus(Layanan $layanan)
    {
        $layanan->update([
            'is_active' => !$layanan->is_active
        ]);

        $status = $layanan->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()
            ->with('success', "Layanan berhasil {$status}!");
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        
        $layanan = Layanan::withCount('pesanan')
            ->where(function($query) use ($search) {
                $query->where('nama_layanan', 'like', "%{$search}%")
                      ->orWhere('kategori', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%")
                      ->orWhere('harga_mulai', 'like', "%{$search}%");
            })
            ->latest()
            ->get();

        return view('admin.layanan.index', compact('layanan'));
    }
}