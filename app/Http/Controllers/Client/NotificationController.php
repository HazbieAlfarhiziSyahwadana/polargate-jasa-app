<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Invoice;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientNotificationController extends Controller
{
    /**
     * Get badge count untuk client notifications
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBadgeCount()
    {
        $clientId = Auth::id();
        
        // Hitung pesanan yang ada update dalam 24 jam terakhir
        $pesananBaru = Pesanan::where('client_id', $clientId)
            ->where('updated_at', '>=', now()->subHours(24))
            ->whereIn('status', ['Selesai', 'Perlu Revisi', 'Sedang Dikerjakan'])
            ->count();
        
        // Hitung invoice yang belum dibayar
        $invoiceBelumBayar = Invoice::whereHas('pesanan', function($query) use ($clientId) {
                $query->where('client_id', $clientId);
            })
            ->where('status', 'Belum Bayar')
            ->count();
        
        // Hitung pesanan yang perlu revisi dari client
        $revisiPerluDiproses = Pesanan::where('client_id', $clientId)
            ->where('status', 'Perlu Revisi')
            ->count();
        
        // 🆕 TAMBAHAN: Ambil pembayaran yang baru diverifikasi dalam 5 menit terakhir
        $pembayaranBaru = Pembayaran::whereHas('invoice.pesanan', function($query) use ($clientId) {
                $query->where('client_id', $clientId);
            })
            ->whereIn('status', ['Terverifikasi', 'Ditolak'])
            ->where('updated_at', '>=', now()->subMinutes(5))
            ->with(['invoice.pesanan'])
            ->get()
            ->map(function($pembayaran) {
                return [
                    'id' => $pembayaran->id,
                    'invoice_id' => $pembayaran->invoice_id,
                    'invoice_nomor' => $pembayaran->invoice->nomor_invoice,
                    'pesanan_kode' => $pembayaran->invoice->pesanan->kode_pesanan,
                    'status' => $pembayaran->status,
                    'alasan_penolakan' => $pembayaran->alasan_penolakan,
                    'jumlah' => $pembayaran->jumlah,
                    'updated_at' => $pembayaran->updated_at->diffForHumans()
                ];
            });
        
        return response()->json([
            'success' => true,
            'pesananBaru' => $pesananBaru,
            'invoiceBelumBayar' => $invoiceBelumBayar,
            'revisiPerluDiproses' => $revisiPerluDiproses,
            'pembayaranBaru' => $pembayaranBaru, // 🆕 Data pembayaran yang baru diverifikasi
            'timestamp' => now()->toIso8601String()
        ]);
    }
    
    /**
     * Mark notification as read
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead(Request $request)
    {
        $request->validate([
            'type' => 'required|in:pesanan,invoice,revisi,pembayaran',
            'id' => 'nullable|integer'
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }
    
    /**
     * 🆕 Check payment status untuk specific invoice
     * Digunakan di halaman detail invoice untuk realtime update
     * 
     * @param int $invoiceId
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkPaymentStatus($invoiceId)
    {
        $clientId = Auth::id();
        
        $pembayaran = Pembayaran::where('invoice_id', $invoiceId)
            ->whereHas('invoice.pesanan', function($query) use ($clientId) {
                $query->where('client_id', $clientId);
            })
            ->with(['invoice'])
            ->latest()
            ->first();
        
        if (!$pembayaran) {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran tidak ditemukan'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'pembayaran' => [
                'id' => $pembayaran->id,
                'status' => $pembayaran->status,
                'alasan_penolakan' => $pembayaran->alasan_penolakan,
                'jumlah' => $pembayaran->jumlah,
                'metode' => $pembayaran->metode_pembayaran,
                'bukti_url' => $pembayaran->bukti_url,
                'updated_at' => $pembayaran->updated_at->format('d M Y H:i'),
                'verifikator' => $pembayaran->verified_by ? $pembayaran->verifiedBy->name : null
            ],
            'invoice' => [
                'status' => $pembayaran->invoice->status,
                'total' => $pembayaran->invoice->total_tagihan
            ]
        ]);
    }
}