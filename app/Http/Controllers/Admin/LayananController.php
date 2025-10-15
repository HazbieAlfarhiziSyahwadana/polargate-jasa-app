<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Layanan;
use App\Helpers\FileHelper;

class LayananController extends Controller
{
    public function index()
    {
        $layanan = Layanan::withCount('pesanan')->latest()->get();
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
            'harga_mulai' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ], [
            'kategori.required' => 'Kategori wajib dipilih.',
            'nama_layanan.required' => 'Nama layanan wajib diisi.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus jpg, jpeg, atau png.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
            'harga_mulai.required' => 'Harga mulai wajib diisi.',
            'harga_mulai.numeric' => 'Harga harus berupa angka.',
        ]);

        // Upload gambar jika ada
        if ($request->hasFile('gambar')) {
            $validated['gambar'] = FileHelper::uploadFile($request->file('gambar'), 'layanan');
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
            'harga_mulai' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        // Upload gambar baru jika ada
        if ($request->hasFile('gambar')) {
            $validated['gambar'] = FileHelper::uploadFile(
                $request->file('gambar'),
                'layanan',
                $layanan->gambar
            );
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
}