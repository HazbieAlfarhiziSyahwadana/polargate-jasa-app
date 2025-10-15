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

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($builder) use ($search) {
                $builder->where('nomor_invoice', 'like', "%{$search}%")
                    ->orWhereHas('pesanan', function ($pesanan) use ($search) {
                        $pesanan->where('kode_pesanan', 'like', "%{$search}%");
                    })
                    ->orWhereHas('pesanan.layanan', function ($layanan) use ($search) {
                        $layanan->where('nama_layanan', 'like', "%{$search}%");
                    })
                    ->orWhereHas('pesanan.paket', function ($paket) use ($search) {
                        $paket->where('nama_paket', 'like', "%{$search}%");
                    });
            });
        }

        $invoices = $query->paginate(10)->withQueryString();

        return view('client.invoice.index', compact('invoices'));
    }

    public function show($id)
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

        return view('client.invoice.show', compact('invoice'));
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

        $pdf = Pdf::loadView('pdf.invoice', [
            'invoice' => $invoice,
        ])->setPaper('a4');

        return $pdf->download('invoice-' . $invoice->nomor_invoice . '.pdf');
    }
}
