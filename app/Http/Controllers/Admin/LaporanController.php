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
        $query = Invoice::with(['pesanan.client', 'pesanan.layanan'])
            ->whereIn('tipe', ['DP', 'Pelunasan']); // Hanya DP dan Pelunasan

        // Filter berdasarkan tipe invoice
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        // Filter berdasarkan status invoice
        if ($request->filled('status_invoice')) {
            $query->where('status', $request->status_invoice);
        }

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

        // Hitung statistik per tipe (hanya DP dan Pelunasan)
        $statistikTipe = [
            'dp' => [
                'count' => $invoices->where('tipe', 'DP')->count(),
                'total' => $invoices->where('tipe', 'DP')->sum('jumlah'),
                'lunas' => $invoices->where('tipe', 'DP')->where('status', 'Lunas')->count()
            ],
            'pelunasan' => [
                'count' => $invoices->where('tipe', 'Pelunasan')->count(),
                'total' => $invoices->where('tipe', 'Pelunasan')->sum('jumlah'),
                'lunas' => $invoices->where('tipe', 'Pelunasan')->where('status', 'Lunas')->count()
            ]
        ];

        // Hitung total pendapatan (hanya dari DP dan Pelunasan yang Lunas)
        $totalPendapatan = $invoices->where('status', 'Lunas')->sum('jumlah');

        // Hitung pendapatan per bulan (hanya DP dan Pelunasan)
        $pendapatanPerBulan = Invoice::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('SUM(jumlah) as total')
            )
            ->where('status', 'Lunas')
            ->whereIn('tipe', ['DP', 'Pelunasan'])
            ->whereYear('created_at', $request->input('tahun', date('Y')))
            ->groupBy('bulan')
            ->get();

        return view('admin.laporan.keuangan', compact('invoices', 'pendapatanPerBulan', 'statistikTipe', 'totalPendapatan'));
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
        // Query invoice dengan filter (hanya DP dan Pelunasan)
        $query = Invoice::with(['pesanan.client', 'pesanan.layanan'])
            ->whereIn('tipe', ['DP', 'Pelunasan']);

        // Filter berdasarkan tipe invoice
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        // Filter berdasarkan status invoice
        if ($request->filled('status_invoice')) {
            $query->where('status', $request->status_invoice);
        }

        // Filter berdasarkan bulan
        if ($request->filled('bulan')) {
            $query->whereMonth('created_at', $request->bulan);
        }

        // Filter berdasarkan tahun
        if ($request->filled('tahun')) {
            $query->whereYear('created_at', $request->tahun);
        } else {
            $query->whereYear('created_at', date('Y'));
        }

        $invoices = $query->latest()->get();

        // Prepare data untuk laporan
        $bulanText = '';
        if ($request->filled('bulan')) {
            $bulanText = date('F', mktime(0, 0, 0, $request->bulan, 1));
        }
        $tahun = $request->input('tahun', date('Y'));
        $tipe = $request->input('tipe', 'Semua Tipe (DP & Pelunasan)');
        $status = $request->input('status_invoice', 'Semua Status');

        // Generate filename
        $filename = 'laporan-keuangan-' . date('Y-m-d-His') . '.csv';

        // Set headers
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        // Callback untuk generate CSV
        $callback = function() use ($invoices, $bulanText, $tahun, $tipe, $status) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // ==================== HEADER LAPORAN ====================
            fputcsv($file, ['LAPORAN KEUANGAN - DP & PELUNASAN']);
            fputcsv($file, ['Periode: ' . ($bulanText ? $bulanText . ' ' : '') . $tahun]);
            fputcsv($file, ['Tipe: ' . $tipe]);
            fputcsv($file, ['Status: ' . $status]);
            fputcsv($file, ['Tanggal Cetak: ' . date('d F Y H:i:s')]);
            fputcsv($file, ['']);

            // ==================== TABEL DATA ====================
            fputcsv($file, [
                'No',
                'Tipe Invoice',
                'No Invoice',
                'Kode Pesanan',
                'Nama Client',
                'Email Client',
                'Layanan',
                'Jumlah (Rp)',
                'Status Invoice',
                'Status Pesanan',
                'Tanggal Invoice',
                'Tanggal Pembayaran'
            ]);
            
            // Data rows
            $no = 1;
            $totalDP = 0;
            $totalPelunasan = 0;
            $totalLunas = 0;
            
            foreach ($invoices as $invoice) {
                $jumlah = $invoice->jumlah;
                
                // Hitung total per tipe
                if ($invoice->tipe === 'DP') {
                    $totalDP += $jumlah;
                } else if ($invoice->tipe === 'Pelunasan') {
                    $totalPelunasan += $jumlah;
                }
                
                // Hitung total yang sudah lunas
                if ($invoice->status === 'Lunas') {
                    $totalLunas += $jumlah;
                }
                
                fputcsv($file, [
                    $no++,
                    $invoice->tipe,
                    $invoice->nomor_invoice,
                    $invoice->pesanan->kode_pesanan ?? '-',
                    $invoice->pesanan->client->name ?? '-',
                    $invoice->pesanan->client->email ?? '-',
                    $invoice->pesanan->layanan->nama_layanan ?? $invoice->pesanan->layanan->nama ?? '-',
                    number_format($jumlah, 0, ',', '.'),
                    $invoice->status,
                    $invoice->pesanan->status ?? '-',
                    $invoice->created_at->format('d/m/Y H:i'),
                    $invoice->tanggal_pembayaran ? $invoice->tanggal_pembayaran->format('d/m/Y H:i') : '-'
                ]);
            }

            fputcsv($file, ['']);

            // ==================== RINGKASAN PER TIPE ====================
            fputcsv($file, ['RINGKASAN LAPORAN KEUANGAN']);
            fputcsv($file, ['']);
            
            fputcsv($file, ['Tipe Invoice', 'Jumlah Invoice', 'Total Nilai (Rp)', 'Lunas']);
            fputcsv($file, ['DP', 
                $invoices->where('tipe', 'DP')->count() . ' invoice', 
                'Rp ' . number_format($totalDP, 0, ',', '.'),
                $invoices->where('tipe', 'DP')->where('status', 'Lunas')->count() . ' invoice'
            ]);
            fputcsv($file, ['Pelunasan', 
                $invoices->where('tipe', 'Pelunasan')->count() . ' invoice', 
                'Rp ' . number_format($totalPelunasan, 0, ',', '.'),
                $invoices->where('tipe', 'Pelunasan')->where('status', 'Lunas')->count() . ' invoice'
            ]);
            
            fputcsv($file, ['']);
            fputcsv($file, ['TOTAL KESELURUHAN', 
                $invoices->count() . ' invoice', 
                'Rp ' . number_format($totalDP + $totalPelunasan, 0, ',', '.'),
                $invoices->where('status', 'Lunas')->count() . ' invoice'
            ]);
            
            fputcsv($file, ['']);
            fputcsv($file, ['TOTAL PENDAPATAN (YANG SUDAH LUNAS)', 
                '', 
                'Rp ' . number_format($totalLunas, 0, ',', '.'),
                ''
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPemesanan(Request $request)
    {
        // Query pesanan dengan filter
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

        // Generate filename
        $filename = 'laporan-pemesanan-' . date('Y-m-d-His') . '.csv';

        // Set headers
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        // Callback untuk generate CSV
        $callback = function() use ($pesanan, $request) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // ==================== HEADER LAPORAN ====================
            fputcsv($file, ['LAPORAN PEMESANAN']);
            fputcsv($file, ['Tanggal Cetak: ' . date('d F Y H:i:s')]);
            if ($request->filled('status')) {
                fputcsv($file, ['Filter Status: ' . $request->status]);
            }
            fputcsv($file, ['']);

            // ==================== TABEL DATA ====================
            fputcsv($file, [
                'No',
                'Kode Pesanan',
                'Nama Client',
                'Email Client',
                'Layanan',
                'Paket',
                'Status',
                'Total Harga (Rp)',
                'Tanggal Pesan',
                'Deadline'
            ]);
            
            // Data rows
            $no = 1;
            $totalPesanan = $pesanan->count();
            $totalNilaiPesanan = 0;
            
            foreach ($pesanan as $order) {
                $totalHarga = $order->total_harga;
                $totalNilaiPesanan += $totalHarga;
                
                fputcsv($file, [
                    $no++,
                    $order->kode_pesanan,
                    $order->client->name ?? '-',
                    $order->client->email ?? '-',
                    $order->layanan->nama_layanan ?? $order->layanan->nama ?? '-',
                    $order->paket->nama_paket ?? '-',
                    $order->status,
                    number_format($totalHarga, 0, ',', '.'),
                    $order->created_at->format('d/m/Y'),
                    $order->deadline ? $order->deadline->format('d/m/Y') : '-'
                ]);
            }

            fputcsv($file, ['']);
            
            // ==================== RINGKASAN ====================
            fputcsv($file, ['RINGKASAN LAPORAN']);
            fputcsv($file, ['']);
            
            fputcsv($file, ['Keterangan', 'Jumlah']);
            fputcsv($file, ['Total Pesanan', $totalPesanan . ' pesanan']);
            fputcsv($file, ['Pending', $pesanan->where('status', 'Pending')->count() . ' pesanan']);
            fputcsv($file, ['Proses', $pesanan->where('status', 'Proses')->count() . ' pesanan']);
            fputcsv($file, ['Selesai', $pesanan->where('status', 'Selesai')->count() . ' pesanan']);
            fputcsv($file, ['Batal', $pesanan->where('status', 'Batal')->count() . ' pesanan']);
            fputcsv($file, ['']);
            fputcsv($file, ['TOTAL NILAI PESANAN', 'Rp ' . number_format($totalNilaiPesanan, 0, ',', '.')]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportClient()
    {
        $clients = User::where('role', 'client')
            ->with(['pesanan'])
            ->latest()
            ->get();

        // Generate filename
        $filename = 'laporan-client-' . date('Y-m-d-His') . '.csv';

        // Set headers
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        // Callback untuk generate CSV
        $callback = function() use ($clients) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // ==================== HEADER LAPORAN ====================
            fputcsv($file, ['LAPORAN CLIENT']);
            fputcsv($file, ['Tanggal Cetak: ' . date('d F Y H:i:s')]);
            fputcsv($file, ['']);
            
            // ==================== TABEL DATA ====================
            fputcsv($file, [
                'No',
                'Nama Client',
                'Email',
                'Tanggal Bergabung',
                'Total Pesanan',
                'Status'
            ]);
            
            // Data rows
            $no = 1;
            $totalClient = $clients->count();
            $clientAktif = 0;
            
            foreach ($clients as $client) {
                $totalPesanan = $client->pesanan->count();
                $status = $totalPesanan > 0 ? 'Aktif' : 'Non-Aktif';
                
                if ($totalPesanan > 0) {
                    $clientAktif++;
                }
                
                fputcsv($file, [
                    $no++,
                    $client->name,
                    $client->email,
                    $client->created_at->format('d/m/Y'),
                    $totalPesanan . ' pesanan',
                    $status
                ]);
            }

            fputcsv($file, ['']);
            
            // ==================== RINGKASAN ====================
            fputcsv($file, ['RINGKASAN LAPORAN']);
            fputcsv($file, ['']);
            
            fputcsv($file, ['Keterangan', 'Jumlah']);
            fputcsv($file, ['Total Client', $totalClient . ' client']);
            fputcsv($file, ['Client Aktif', $clientAktif . ' client']);
            fputcsv($file, ['Client Non-Aktif', ($totalClient - $clientAktif) . ' client']);
            fputcsv($file, ['Client Baru (Bulan Ini)', User::where('role', 'client')
                ->whereMonth('created_at', date('m'))
                ->whereYear('created_at', date('Y'))
                ->count() . ' client']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}