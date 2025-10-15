@extends('layouts.client')

@section('title', 'Invoice Saya')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Invoice Saya</h1>
    <p class="text-gray-600">Daftar invoice pembayaran</p>
</div>

<!-- Filter -->
<div class="card mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Filter Status</label>
            <select name="status" id="status" class="input-field" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="Belum Dibayar" {{ request('status') == 'Belum Dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                <option value="Menunggu Verifikasi" {{ request('status') == 'Menunggu Verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                <option value="Lunas" {{ request('status') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
            </select>
        </div>
        <div class="flex items-end">
            <a href="{{ route('client.invoice.index') }}" class="btn-secondary">Reset</a>
        </div>
    </form>
</div>

<!-- Invoice List -->
@if($invoices->count() > 0)
<div class="space-y-4">
    @foreach($invoices as $invoice)
    <div class="card hover:shadow-lg transition-shadow">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
            <div>
                <h3 class="text-lg font-bold text-gray-800">{{ $invoice->nomor_invoice }}</h3>
                <p class="text-sm text-gray-600">{{ $invoice->created_at->format('d M Y H:i') }}</p>
            </div>
            @if($invoice->status == 'Lunas')
            <span class="badge-success">Lunas</span>
            @elseif($invoice->status == 'Menunggu Verifikasi')
            <span class="badge-warning">Menunggu Verifikasi</span>
            @else
            <span class="badge-danger">Belum Dibayar</span>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <p class="text-sm text-gray-500">Pesanan</p>
                <p class="font-semibold text-gray-900">{{ $invoice->pesanan->kode_pesanan }}</p>
                <p class="text-sm text-gray-600">{{ $invoice->pesanan->layanan->nama_layanan }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Tipe</p>
                <p class="font-semibold text-gray-900">{{ $invoice->tipe }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Jumlah</p>
                <p class="font-semibold text-primary-600 text-lg">Rp {{ number_format($invoice->jumlah, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="flex items-center justify-between pt-4 border-t">
            <div>
                @if($invoice->is_jatuh_tempo)
                <p class="text-sm text-red-600">⚠️ Jatuh tempo: {{ $invoice->tanggal_jatuh_tempo->format('d M Y') }}</p>
                @else
                <p class="text-sm text-gray-600">Jatuh tempo: {{ $invoice->tanggal_jatuh_tempo->format('d M Y') }}</p>
                @endif
            </div>
            
            @if($invoice->status != 'Lunas')
            <a href="{{ route('client.pembayaran.invoice', $invoice) }}" class="btn-primary">
                Bayar Sekarang
            </a>
            @else
            <span class="text-sm text-green-600 font-semibold">✓ Lunas</span>
            @endif
        </div>
    </div>
    @endforeach
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $invoices->links() }}
</div>

@else
<div class="card text-center py-12">
    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
    </svg>
    <h3 class="mt-4 text-lg font-medium text-gray-900">Belum Ada Invoice</h3>
    <p class="mt-2 text-sm text-gray-500">Invoice akan muncul setelah Anda melakukan pemesanan</p>
</div>
@endif
@endsection