<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function keuangan(Request $request)
    {
        $query = Invoice::with(['pesanan.client', 'pesanan.layanan']);

        // Filter berdasarkan bulan
        if ($request->filled('bulan')) {
            $query->whereMonth('created_at', $request->bulan);
        }

        // Filter berdasarkan tahun
        if ($request->filled('tahun')) {
            $query->whereYear('created_at', $request->tahun);
        } else {
            // Default tahun sekarang
            $query->whereYear('created_at', date('Y'));
        }

        $invoices = $query->latest()->get();

        // Hitung pendapatan per bulan
        $pendapatanPerBulan = Invoice::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('SUM(jumlah) as total')
            )
            ->where('status', 'Lunas')
            ->whereYear('created_at', $request->input('tahun', date('Y')))
            ->groupBy('bulan')
            ->get();

        return view('admin.laporan.keuangan', compact('invoices', 'pendapatanPerBulan'));
    }

    public function pemesanan(Request $request)
    {
        $query = Pesanan::with(['client', 'layanan', 'paket']);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan layanan
        if ($request->filled('layanan')) {
            $query->where('layanan_id', $request->layanan);
        }

        $pesanan = $query->latest()->get();

        // Statistik pesanan per status
        $pesananPerStatus = Pesanan::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // Statistik pesanan per layanan
        $pesananPerLayanan = Pesanan::select('layanan_id')
            ->with('layanan')
            ->groupBy('layanan_id')
            ->get();

        return view('admin.laporan.pemesanan', compact('pesanan', 'pesananPerStatus', 'pesananPerLayanan'));
    }

    public function client()
    {
        $clients = User::where('role', 'client')
            ->with(['pesanan'])
            ->latest()
            ->get();

        $totalClient = $clients->count();
        $clientAktif = $clients->filter(function($client) {
            return $client->pesanan->count() > 0;
        })->count();

        $clientBaru = User::where('role', 'client')
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->count();

        return view('admin.laporan.client', compact('clients', 'totalClient', 'clientAktif', 'clientBaru'));
    }

    public function exportKeuangan(Request $request)
    {
        // TODO: Implementasi export Excel
        return redirect()->back()->with('info', 'Fitur export sedang dalam pengembangan');
    }

    public function exportPemesanan(Request $request)
    {
        // TODO: Implementasi export Excel
        return redirect()->back()->with('info', 'Fitur export sedang dalam pengembangan');
    }
}