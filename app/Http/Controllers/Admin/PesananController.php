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
            'status' => 'required|in:Sedang Diproses,Preview Siap,Selesai,Dibatalkan',
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
        ], [
            'preview_link.required' => 'Link preview wajib diisi.',
            'preview_link.url' => 'Link preview harus berupa URL yang valid.',
        ]);

        // Set expired 24 jam dari sekarang
        $expiredAt = Carbon::now()->addHours(24);

        $pesanan->update([
            'preview_link' => $validated['preview_link'],
            'preview_expired_at' => $expiredAt,
            'status' => 'Preview Siap'
        ]);

        return redirect()->back()->with('success', 'Preview berhasil diunggah! Link akan kadaluarsa dalam 24 jam.');
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

        $pesanan->update([
            'file_final' => $uploadedFiles,
            'status' => 'Selesai'
        ]);

        return redirect()->back()->with('success', 'File final berhasil diunggah!');
    }
}