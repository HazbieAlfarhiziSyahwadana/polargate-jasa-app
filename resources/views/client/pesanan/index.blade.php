@extends('layouts.client')

@section('title', 'Pesanan Saya')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Pesanan Saya</h1>
    <p class="text-gray-600">Kelola dan pantau pesanan Anda</p>
</div>

<!-- Filter -->
<div class="card mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Filter Status</label>
            <select name="status" id="status" class="input-field" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="Menunggu Pembayaran DP" {{ request('status') == 'Menunggu Pembayaran DP' ? 'selected' : '' }}>Menunggu Pembayaran DP</option>
                <option value="Sedang Diproses" {{ request('status') == 'Sedang Diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                <option value="Preview Siap" {{ request('status') == 'Preview Siap' ? 'selected' : '' }}>Preview Siap</option>
                <option value="Menunggu Pelunasan" {{ request('status') == 'Menunggu Pelunasan' ? 'selected' : '' }}>Menunggu Pelunasan</option>
                <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>
        <div class="flex items-end">
            <a href="{{ route('client.pesanan.index') }}" class="btn-secondary">Reset</a>
        </div>
    </form>
</div>

<!-- Pesanan List -->
@if($pesanan->count() > 0)
<div class="space-y-4">
    @foreach($pesanan as $item)
    <div class="card hover:shadow-lg transition-shadow">
        @php
            $statusClass = 'badge-info';
            if($item->status == 'Selesai') $statusClass = 'badge-success';
            if(in_array($item->status, ['Menunggu Pembayaran DP', 'Menunggu Pelunasan'])) $statusClass = 'badge-warning';
            if($item->status == 'Dibatalkan') $statusClass = 'badge-danger';

            $invoiceDP = $item->invoices->where('tipe', 'DP')->first();
            $invoicePelunasan = $item->invoices->where('tipe', 'Pelunasan')->first();
            $dpLunas = $invoiceDP && $invoiceDP->status === 'Lunas';
            $previewExpiry = $item->preview_expired_at ? \Carbon\Carbon::parse($item->preview_expired_at) : null;
            $previewAktif = $previewExpiry && $previewExpiry->isFuture() && $item->is_preview_active;
        @endphp

        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
            <div>
                <h3 class="text-lg font-bold text-gray-800">{{ $item->kode_pesanan }}</h3>
                <p class="text-sm text-gray-600">{{ $item->created_at->format('d M Y H:i') }}</p>
            </div>
            <span class="{{ $statusClass }}">{{ $item->status }}</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <p class="text-sm text-gray-500">Layanan</p>
                <p class="font-semibold text-gray-900">{{ $item->layanan->nama_layanan }}</p>
                <p class="text-sm text-gray-600">{{ $item->paket->nama_paket ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Harga</p>
                <p class="font-semibold text-gray-900 text-lg">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Invoice</p>
                @if($invoiceDP)
                <p class="text-xs">DP: 
                    <span class="{{ $invoiceDP->status == 'Lunas' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $invoiceDP->status }}
                    </span>
                </p>
                @endif
                @if($invoicePelunasan)
                <p class="text-xs">Pelunasan: 
                    <span class="{{ $invoicePelunasan->status == 'Lunas' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $invoicePelunasan->status }}
                    </span>
                </p>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-2 pt-4 border-t">
            <a href="{{ route('client.pesanan.show', $item) }}" class="btn-primary text-sm">
                Lihat Detail
            </a>

            @if(in_array($item->status, ['Menunggu Pembayaran DP', 'Menunggu Pelunasan']))
            @php
                $invoice = $item->status == 'Menunggu Pembayaran DP' ? $invoiceDP : $invoicePelunasan;
            @endphp
            @if($invoice && $invoice->status != 'Lunas')
            <a href="{{ route('client.pembayaran.invoice', $invoice) }}" class="btn-success text-sm">
                Bayar Sekarang
            </a>
            @endif
            @endif

            @if($item->status == 'Preview Siap')
                @if(!$dpLunas)
                    @if($invoiceDP && $invoiceDP->status !== 'Lunas')
                    <a href="{{ route('client.pembayaran.invoice', $invoiceDP) }}" class="btn-secondary text-sm">
                        Bayar DP untuk Preview
                    </a>
                    @endif
                @elseif($previewAktif)
                <a href="{{ $item->preview_link }}" target="_blank" class="btn-secondary text-sm">
                    Lihat Preview
                </a>
                @else
                <button class="btn-secondary text-sm cursor-not-allowed opacity-60" disabled>
                    Preview Kadaluarsa
                </button>
                @endif
            @endif

            @if($item->status == 'Selesai' && $item->file_final)
            <a href="{{ route('client.pesanan.download-final', $item) }}" class="btn-secondary text-sm">
                Download File
            </a>
            @endif
        </div>
    </div>
    @endforeach
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $pesanan->links() }}
</div>

@else
<div class="card text-center py-12">
    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
    </svg>
    <h3 class="mt-4 text-lg font-medium text-gray-900">Belum Ada Pesanan</h3>
    <p class="mt-2 text-sm text-gray-500">Mulai pesan layanan kami sekarang</p>
    <a href="{{ route('client.layanan.index') }}" class="btn-primary mt-4 inline-block">
        Lihat Layanan
    </a>
</div>
@endif
@endsection
