<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Addon;
use App\Models\Layanan;

class AddonController extends Controller
{
    public function index()
    {
        $addons = Addon::with('layanan')->latest()->get();
        return view('admin.addon.index', compact('addons'));
    }

    public function create()
    {
        $layanan = Layanan::active()->get();
        return view('admin.addon.create', compact('layanan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'layanan_id' => 'required|exists:layanan,id',
            'nama_addon' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ], [
            'layanan_id.required' => 'Layanan wajib dipilih.',
            'nama_addon.required' => 'Nama add-on wajib diisi.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'harga.required' => 'Harga wajib diisi.',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        Addon::create($validated);

        return redirect()->route('admin.addon.index')
            ->with('success', 'Add-on berhasil ditambahkan!');
    }

    public function edit(Addon $addon)
    {
        $layanan = Layanan::active()->get();
        return view('admin.addon.edit', compact('addon', 'layanan'));
    }

    public function update(Request $request, Addon $addon)
    {
        $validated = $request->validate([
            'layanan_id' => 'required|exists:layanan,id',
            'nama_addon' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $addon->update($validated);

        return redirect()->route('admin.addon.index')
            ->with('success', 'Add-on berhasil diperbarui!');
    }

    public function destroy(Addon $addon)
    {
        // Cek apakah addon memiliki pesanan
        if ($addon->pesananAddons()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Add-on tidak dapat dihapus karena sudah digunakan di pesanan!');
        }

        $addon->delete();

        return redirect()->route('admin.addon.index')
            ->with('success', 'Add-on berhasil dihapus!');
    }

    public function toggleStatus(Addon $addon)
    {
        $addon->update([
            'is_active' => !$addon->is_active
        ]);

        $status = $addon->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()
            ->with('success', "Add-on berhasil {$status}!");
    }
}