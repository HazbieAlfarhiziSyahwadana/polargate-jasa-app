<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $clientId = Auth::id();

        $data = [
            'total_pesanan' => Pesanan::where('client_id', $clientId)->count(),
            'pesanan_aktif' => Pesanan::where('client_id', $clientId)
                ->whereIn('status', [
                    'Menunggu Pembayaran DP',
                    'DP Dibayar - Menunggu Verifikasi',
                    'Sedang Diproses',
                    'Preview Siap',
                    'Revisi Diminta',
                    'Menunggu Pelunasan',
                    'Pelunasan Dibayar - Menunggu Verifikasi'
                ])
                ->count(),
            'pesanan_selesai' => Pesanan::where('client_id', $clientId)
                ->where('status', 'Selesai')
                ->count(),
            'total_pembayaran' => Invoice::whereHas('pesanan', function($query) use ($clientId) {
                    $query->where('client_id', $clientId);
                })
                ->where('status', 'Lunas')
                ->sum('jumlah'),
            'pesanan_terbaru' => Pesanan::where('client_id', $clientId)
                ->with(['layanan', 'paket', 'invoices'])
                ->latest()
                ->take(5)
                ->get(),
            'invoice_pending' => Invoice::whereHas('pesanan', function($query) use ($clientId) {
                    $query->where('client_id', $clientId);
                })
                ->whereIn('status', ['Belum Dibayar', 'Menunggu Verifikasi'])
                ->with('pesanan.layanan')
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('client.dashboard', $data);
    }

    // API untuk fetch data realtime
    public function realtimeData()
    {
        $clientId = Auth::id();

        $pesananTerbaru = Pesanan::where('client_id', $clientId)
            ->with(['layanan', 'paket', 'invoices'])
            ->latest()
            ->take(5)
            ->get();

        $invoicePending = Invoice::whereHas('pesanan', function($query) use ($clientId) {
                $query->where('client_id', $clientId);
            })
            ->whereIn('status', ['Belum Dibayar', 'Menunggu Verifikasi'])
            ->with('pesanan.layanan', 'pembayaran')
            ->latest()
            ->take(5)
            ->get();

        $data = [
            'total_pesanan' => Pesanan::where('client_id', $clientId)->count(),
            'pesanan_aktif' => Pesanan::where('client_id', $clientId)
                ->whereIn('status', [
                    'Menunggu Pembayaran DP',
                    'DP Dibayar - Menunggu Verifikasi',
                    'Sedang Diproses',
                    'Preview Siap',
                    'Revisi Diminta',
                    'Menunggu Pelunasan',
                    'Pelunasan Dibayar - Menunggu Verifikasi'
                ])
                ->count(),
            'pesanan_selesai' => Pesanan::where('client_id', $clientId)
                ->where('status', 'Selesai')
                ->count(),
            'total_pembayaran' => Invoice::whereHas('pesanan', function($query) use ($clientId) {
                    $query->where('client_id', $clientId);
                })
                ->where('status', 'Lunas')
                ->sum('jumlah'),
            'pesanan_terbaru' => $pesananTerbaru,
            'invoice_pending' => $invoicePending,
        ];

        return response()->json($data);
    }
}