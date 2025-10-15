<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function index(Request $request)
    {
        $query = Layanan::where('is_active', true)
            ->with(['paket' => function($query) {
                $query->where('is_active', true);
            }]);

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Search
        if ($request->filled('search')) {
            $query->where('nama_layanan', 'like', '%' . $request->search . '%');
        }

        $layanan = $query->latest()->paginate(9);

        return view('client.layanan.index', compact('layanan'));
    }

    public function show(Layanan $layanan)
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

        // Layanan terkait (kategori yang sama)
        $layananTerkait = Layanan::where('kategori', $layanan->kategori)
            ->where('id', '!=', $layanan->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return view('client.layanan.show', compact('layanan', 'layananTerkait'));
    }
}