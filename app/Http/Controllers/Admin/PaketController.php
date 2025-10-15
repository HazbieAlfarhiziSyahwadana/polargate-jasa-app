<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paket;
use App\Models\Layanan;

class PaketController extends Controller
{
    public function index()
    {
        $paket = Paket::with('layanan')->latest()->get();
        return view('admin.paket.index', compact('paket'));
    }

    public function create()
    {
        $layanan = Layanan::active()->get();
        return view('admin.paket.create', compact('layanan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'layanan_id' => 'required|exists:layanan,id',
            'nama_paket' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'fitur' => 'required|array',
            'fitur.*' => 'required|string',
            'durasi_pengerjaan' => 'required|integer|min:1',
            'jumlah_revisi' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ], [
            'layanan_id.required' => 'Layanan wajib dipilih.',
            'nama_paket.required' => 'Nama paket wajib diisi.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'harga.required' => 'Harga wajib diisi.',
            'fitur.required' => 'Fitur wajib diisi minimal 1.',
            'durasi_pengerjaan.required' => 'Durasi pengerjaan wajib diisi.',
            'jumlah_revisi.required' => 'Jumlah revisi wajib diisi.',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        Paket::create($validated);

        return redirect()->route('admin.paket.index')
            ->with('success', 'Paket berhasil ditambahkan!');
    }

    public function edit(Paket $paket)
    {
        $layanan = Layanan::active()->get();
        return view('admin.paket.edit', compact('paket', 'layanan'));
    }

    public function update(Request $request, Paket $paket)
    {
        $validated = $request->validate([
            'layanan_id' => 'required|exists:layanan,id',
            'nama_paket' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'fitur' => 'required|array',
            'fitur.*' => 'required|string',
            'durasi_pengerjaan' => 'required|integer|min:1',
            'jumlah_revisi' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $paket->update($validated);

        return redirect()->route('admin.paket.index')
            ->with('success', 'Paket berhasil diperbarui!');
    }

    public function destroy(Paket $paket)
    {
        // Cek apakah paket memiliki pesanan
        if ($paket->pesanan()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Paket tidak dapat dihapus karena sudah memiliki pesanan!');
        }

        $paket->delete();

        return redirect()->route('admin.paket.index')
            ->with('success', 'Paket berhasil dihapus!');
    }

    public function toggleStatus(Paket $paket)
    {
        $paket->update([
            'is_active' => !$paket->is_active
        ]);

        $status = $paket->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()
            ->with('success', "Paket berhasil {$status}!");
    }
}