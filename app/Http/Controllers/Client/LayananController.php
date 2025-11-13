<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use App\Models\Paket;
use App\Models\Addon;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function index(Request $request)
    {
        $query = Layanan::active()
            ->with(['paket' => function($query) {
                $query->where('is_active', true)->orderBy('harga', 'asc');
            }, 'addons' => function($query) {
                $query->where('is_active', true)->orderBy('harga', 'asc');
            }])
            ->withCount(['pesanan as total_pesanan']);

        // Filter kategori
        if ($request->filled('kategori') && in_array($request->kategori, ['Multimedia', 'IT'])) {
            $query->where('kategori', $request->kategori);
        }

        // Search dengan multiple field
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama_layanan', 'like', '%' . $searchTerm . '%')
                  ->orWhere('kategori', 'like', '%' . $searchTerm . '%')
                  ->orWhere('deskripsi', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('paket', function($q) use ($searchTerm) {
                      $q->where('nama_paket', 'like', '%' . $searchTerm . '%')
                        ->orWhere('deskripsi', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // Filter harga
        if ($request->filled('harga_min')) {
            $query->where('harga_mulai', '>=', $request->harga_min);
        }

        if ($request->filled('harga_max')) {
            $query->where('harga_mulai', '<=', $request->harga_max);
        }

        // Sorting
        $sort = $request->get('sort', 'terbaru');
        switch ($sort) {
            case 'harga_terendah':
                $query->orderBy('harga_mulai', 'asc');
                break;
            case 'harga_tertinggi':
                $query->orderBy('harga_mulai', 'desc');
                break;
            case 'popularitas':
                $query->orderBy('total_pesanan', 'desc');
                break;
            case 'nama_az':
                $query->orderBy('nama_layanan', 'asc');
                break;
            case 'nama_za':
                $query->orderBy('nama_layanan', 'desc');
                break;
            default: // terbaru
                $query->latest();
                break;
        }

        $layanan = $query->paginate(12);

        // Stats untuk filter
        $stats = [
            'total_layanan' => Layanan::active()->count(),
            'multimedia_count' => Layanan::active()->where('kategori', 'Multimedia')->count(),
            'it_count' => Layanan::active()->where('kategori', 'IT')->count(),
            'min_harga' => Layanan::active()->min('harga_mulai'),
            'max_harga' => Layanan::active()->max('harga_mulai'),
        ];

        return view('client.layanan.index', compact('layanan', 'stats'));
    }

    public function show(Layanan $layanan)
    {
        // Pastikan layanan aktif
        if (!$layanan->is_active) {
            abort(404, 'Layanan tidak ditemukan atau tidak aktif');
        }

        // Load relasi dengan eager loading dan filter aktif
        $layanan->load([
            'paket' => function($query) {
                $query->where('is_active', true)->orderBy('harga', 'asc');
            },
            'addons' => function($query) {
                $query->where('is_active', true)->orderBy('harga', 'asc');
            }
        ]);

        // Layanan terkait (kategori yang sama, exclude current)
        $layananTerkait = Layanan::where('kategori', $layanan->kategori)
            ->where('id', '!=', $layanan->id)
            ->active()
            ->with(['paket' => function($query) {
                $query->where('is_active', true)->orderBy('harga', 'asc');
            }])
            ->limit(6)
            ->get();

        // Increment view count (optional - untuk analytics)
        // $layanan->increment('view_count');

        return view('client.layanan.show', compact('layanan', 'layananTerkait'));
    }

    public function search(Request $request)
    {
        $searchTerm = $request->get('q');
        
        if (!$searchTerm) {
            return redirect()->route('client.layanan.index');
        }

        $layanan = Layanan::active()
            ->with(['paket' => function($query) {
                $query->where('is_active', true)->orderBy('harga', 'asc');
            }])
            ->where(function($query) use ($searchTerm) {
                $query->where('nama_layanan', 'like', '%' . $searchTerm . '%')
                      ->orWhere('kategori', 'like', '%' . $searchTerm . '%')
                      ->orWhere('deskripsi', 'like', '%' . $searchTerm . '%')
                      ->orWhereHas('paket', function($q) use ($searchTerm) {
                          $q->where('nama_paket', 'like', '%' . $searchTerm . '%');
                      });
            })
            ->latest()
            ->paginate(12);

        return view('client.layanan.index', compact('layanan'))
            ->with('searchTerm', $searchTerm);
    }

    public function byCategory($kategori)
    {
        if (!in_array($kategori, ['Multimedia', 'IT'])) {
            abort(404, 'Kategori tidak valid');
        }

        $layanan = Layanan::active()
            ->where('kategori', $kategori)
            ->with(['paket' => function($query) {
                $query->where('is_active', true)->orderBy('harga', 'asc');
            }])
            ->latest()
            ->paginate(12);

        $categoryName = $kategori;

        return view('client.layanan.category', compact('layanan', 'categoryName'));
    }

    public function getFeatured()
    {
        // Layanan featured (bisa ditambahkan field 'is_featured' di model)
        $featuredLayanan = Layanan::active()
            ->with(['paket' => function($query) {
                $query->where('is_active', true)->orderBy('harga', 'asc');
            }])
            ->withCount('pesanan')
            ->orderBy('pesanan_count', 'desc')
            ->limit(6)
            ->get();

        return response()->json($featuredLayanan);
    }

    public function getStats()
    {
        $stats = [
            'total_layanan' => Layanan::active()->count(),
            'total_kategori' => Layanan::active()->distinct('kategori')->count('kategori'),
            'popular_categories' => Layanan::active()
                ->groupBy('kategori')
                ->selectRaw('kategori, COUNT(*) as count')
                ->get()
        ];

        return response()->json($stats);
    }
}