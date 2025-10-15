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

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->paginate(10);

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
