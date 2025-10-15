@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Invoice #{{ $invoice->nomor_invoice }}</h4>
            <a href="{{ route('client.invoice.download', $invoice->id) }}" class="btn btn-primary btn-sm">
                <i class="bi bi-download"></i> Download PDF
            </a>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6>Informasi Pesanan</h6>
                    <p class="mb-1"><strong>Kode Pesanan:</strong> {{ $invoice->pesanan->kode_pesanan }}</p>
                    <p class="mb-1"><strong>Layanan:</strong> {{ $invoice->pesanan->layanan->nama }}</p>
                    <p class="mb-1"><strong>Paket:</strong> {{ $invoice->pesanan->paket->nama }}</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h6>Informasi Invoice</h6>
                    <p class="mb-1"><strong>Tipe:</strong> {{ $invoice->tipe }}</p>
                    <p class="mb-1"><strong>Status:</strong> 
                        <span class="badge bg-{{ $invoice->status == 'Lunas' ? 'success' : ($invoice->status == 'Belum Dibayar' ? 'warning' : 'danger') }}">
                            {{ $invoice->status }}
                        </span>
                    </p>
                    <p class="mb-1"><strong>Jatuh Tempo:</strong> {{ \Carbon\Carbon::parse($invoice->jatuh_tempo)->format('d M Y') }}</p>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-12">
                    <h6>Detail Pembayaran</h6>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Deskripsi</th>
                                <th class="text-end">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $invoice->tipe }}</td>
                                <td class="text-end">Rp {{ number_format($invoice->jumlah, 0, ',', '.') }}</td>
                            </tr>
                            @if($invoice->pesanan->addons->count() > 0)
                                @foreach($invoice->pesanan->addons as $addon)
                                <tr>
                                    <td>{{ $addon->nama }}</td>
                                    <td class="text-end">Rp {{ number_format($addon->pivot->harga, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Total</th>
                                <th class="text-end">Rp {{ number_format($invoice->jumlah, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            @if($invoice->bukti_pembayaran)
            <div class="row mt-3">
                <div class="col-12">
                    <h6>Bukti Pembayaran</h6>
                    <img src="{{ asset('storage/' . $invoice->bukti_pembayaran) }}" class="img-fluid" style="max-width: 300px;">
                </div>
            </div>
            @endif
        </div>
        <div class="card-footer">
            <a href="{{ route('client.invoice.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>
@endsection