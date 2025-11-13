<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['pesanan.layanan', 'pesanan.paket'])
            ->whereHas('pesanan', function($q) {
                $q->where('client_id', Auth::id());
            })
            ->latest();

        // Batalkan invoice yang melewati jatuh tempo
        $this->batalkanInvoiceJatuhTempo();

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_invoice', 'like', "%{$search}%")
                  ->orWhereHas('pesanan', function ($pesananQuery) use ($search) {
                      $pesananQuery->where('kode_pesanan', 'like', "%{$search}%")
                                  ->orWhereHas('layanan', function ($layananQuery) use ($search) {
                                      $layananQuery->where('nama_layanan', 'like', "%{$search}%");
                                  })
                                  ->orWhereHas('paket', function ($paketQuery) use ($search) {
                                      $paketQuery->where('nama_paket', 'like', "%{$search}%");
                                  });
                  });
            });
        }

        $invoices = $query->paginate(10);

        return view('client.invoice.index', compact('invoices'));
    }

    private function batalkanInvoiceJatuhTempo()
    {
        try {
            $invoices = Invoice::whereHas('pesanan', function($q) {
                    $q->where('client_id', Auth::id());
                })
                ->where('status', 'Belum Lunas')
                ->where('tanggal_jatuh_tempo', '<', now())
                ->get();

            foreach ($invoices as $invoice) {
                $invoice->update([
                    'status' => 'Dibatalkan',
                    'alasan_penolakan' => 'Invoice dibatalkan secara otomatis karena melewati tanggal jatuh tempo'
                ]);

                // Jika invoice DP dibatalkan, batalkan juga pesanannya
                if ($invoice->tipe === 'DP') {
                    $invoice->pesanan->update([
                        'status' => 'Dibatalkan',
                        'alasan_pembatalan' => 'Pesanan dibatalkan otomatis karena invoice DP melewati jatuh tempo'
                    ]);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error dalam membatalkan invoice jatuh tempo: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $invoice = Invoice::with([
            'pesanan.client',
            'pesanan.layanan', 
            'pesanan.paket',
            'pesanan.addons',
            'pembayaran'
        ])
        ->whereHas('pesanan', function($q) {
            $q->where('client_id', Auth::id());
        })
        ->findOrFail($id);

        // Cek dan batalkan jika jatuh tempo
        if ($invoice->status === 'Belum Lunas' && $invoice->tanggal_jatuh_tempo < now()) {
            $invoice->update([
                'status' => 'Dibatalkan',
                'alasan_penolakan' => 'Invoice dibatalkan secara otomatis karena melewati tanggal jatuh tempo'
            ]);
            $invoice->refresh();
        }

        // Hitung sisa waktu pembayaran
        $sisaWaktu = null;
        if ($invoice->status === 'Belum Lunas' && $invoice->tanggal_jatuh_tempo > now()) {
            $sisaWaktu = now()->diff($invoice->tanggal_jatuh_tempo);
        }

        return view('client.invoice.show', compact('invoice', 'sisaWaktu'));
    }

    public function download($id)
    {
        $invoice = Invoice::with([
            'pesanan.client',
            'pesanan.layanan',
            'pesanan.paket',
            'pesanan.addons'
        ])
        ->whereHas('pesanan', function($q) {
            $q->where('client_id', Auth::id());
        })
        ->findOrFail($id);

        // Data untuk PDF
        $data = [
            'invoice' => $invoice,
            'tanggal_cetak' => now()->format('d F Y H:i:s'),
            'perusahaan' => [
                'nama' => config('app.name', 'Polargete'),
                'alamat' => 'Jl. Contoh Alamat No. 123',
                'telepon' => '+62 812-3456-7890',
                'email' => 'info@polargete.com'
            ]
        ];

        $pdf = Pdf::loadView('pdf.invoice', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ]);

        return $pdf->download('invoice-' . $invoice->nomor_invoice . '.pdf');
    }

    public function bayar($id)
    {
        $invoice = Invoice::with(['pesanan'])
            ->whereHas('pesanan', function($q) {
                $q->where('client_id', Auth::id());
            })
            ->findOrFail($id);

        // Validasi invoice
        if ($invoice->status !== 'Belum Lunas') {
            return redirect()->back()->with('error', 'Invoice sudah dibayar atau dibatalkan.');
        }

        if ($invoice->tanggal_jatuh_tempo < now()) {
            return redirect()->back()->with('error', 'Invoice sudah melewati tanggal jatuh tempo.');
        }

        // Validasi jika pesanan sudah dibatalkan
        if ($invoice->pesanan->status === 'Dibatalkan') {
            return redirect()->back()->with('error', 'Tidak dapat membayar invoice karena pesanan sudah dibatalkan.');
        }

        return redirect()->route('client.pembayaran.create', $invoice);
    }

    public function extendDueDate(Request $request, $id)
    {
        $invoice = Invoice::with(['pesanan'])
            ->whereHas('pesanan', function($q) {
                $q->where('client_id', Auth::id());
            })
            ->findOrFail($id);

        // Validasi
        if ($invoice->status !== 'Belum Lunas') {
            return redirect()->back()->with('error', 'Hanya invoice yang belum lunas yang dapat diperpanjang.');
        }

        // Validasi jika pesanan sudah dibatalkan
        if ($invoice->pesanan->status === 'Dibatalkan') {
            return redirect()->back()->with('error', 'Tidak dapat memperpanjang invoice karena pesanan sudah dibatalkan.');
        }

        $request->validate([
            'hari_tambahan' => 'required|integer|min:1|max:7'
        ]);

        try {
            $invoice->update([
                'tanggal_jatuh_tempo' => $invoice->tanggal_jatuh_tempo->addDays($request->hari_tambahan)
            ]);

            return redirect()->back()->with('success', 'Tanggal jatuh tempo berhasil diperpanjang ' . $request->hari_tambahan . ' hari.');

        } catch (\Exception $e) {
            \Log::error('Error memperpanjang invoice: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperpanjang tanggal jatuh tempo.');
        }
    }
}