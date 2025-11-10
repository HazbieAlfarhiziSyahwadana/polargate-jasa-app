@extends('layouts.client')

@section('title', 'Pesanan Saya')

@section('content')
<style>
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Animasi hanya untuk first load */
    .page-load .animate-fade {
        animation: fadeIn 0.4s ease-out;
    }

    .page-load .animate-slide {
        animation: slideUp 0.5s ease-out;
        opacity: 0;
        animation-fill-mode: forwards;
    }

    .page-load .delay-100 { animation-delay: 0.1s; }
    .page-load .delay-200 { animation-delay: 0.2s; }
    .page-load .delay-300 { animation-delay: 0.3s; }
    .page-load .delay-400 { animation-delay: 0.4s; }
    .page-load .delay-500 { animation-delay: 0.5s; }

    .card {
        transition: box-shadow 0.3s ease, transform 0.3s ease;
    }

    .pesanan-card {
        transition: opacity 0.3s ease;
    }

    .pesanan-card:hover {
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
        transform: translateY(-4px);
    }

    .pesanan-card.hidden-filter {
        display: none;
    }

    .btn-primary, .btn-success, .btn-secondary, .btn-warning {
        transition: all 0.2s ease;
    }

    .btn-primary:hover, .btn-success:hover, .btn-secondary:hover, .btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
    }

    .btn-danger {
        background-color: #ef4444;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-danger:hover {
        background-color: #dc2626;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(239, 68, 68, 0.3);
    }

    .btn-warning {
        background-color: #f59e0b;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 500;
    }

    .btn-warning:hover {
        background-color: #d97706;
    }

    .search-input, .filter-input {
        transition: all 0.3s ease;
    }

    .search-input:focus, .filter-input:focus {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }

    .revisi-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        font-weight: 500;
    }
</style>

<div class="mb-6 animate-fade">
    <h1 class="text-3xl font-bold text-gray-800">Pesanan Saya</h1>
    <p class="text-gray-600">Kelola dan pantau pesanan Anda</p>
</div>

<!-- Search and Filter -->
<div class="card mb-6 animate-slide delay-100">
    <div class="flex flex-col md:flex-row gap-4">
        <!-- Search Bar -->
        <div class="flex-1">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" 
                       id="searchInput"
                       class="search-input w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition"
                       placeholder="Cari berdasarkan kode pesanan, layanan, atau status...">
            </div>
        </div>

        <!-- Filter Status -->
        <div class="md:w-64">
            <select id="statusFilter" 
                    class="filter-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition">
                <option value="">Semua Status</option>
                <option value="menunggu pembayaran dp">Menunggu Pembayaran DP</option>
                <option value="sedang diproses">Sedang Diproses</option>
                <option value="preview siap">Preview Siap</option>
                <option value="menunggu pelunasan">Menunggu Pelunasan</option>
                <option value="selesai">Selesai</option>
                <option value="dibatalkan">Dibatalkan</option>
            </select>
        </div>

        <!-- Reset Button -->
        <button id="resetFilter" 
                class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-all duration-200 flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Reset
        </button>
    </div>

    <!-- Search Results Info -->
    <div id="searchInfo" class="mt-4 text-sm text-gray-600 hidden">
        Menampilkan <span id="resultCount" class="font-semibold text-primary-600"></span> hasil
    </div>
</div>

<!-- Pesanan List -->
@if($pesanan->count() > 0)
<div class="space-y-4" id="pesananContainer">
    @foreach($pesanan as $index => $item)
    <div class="card pesanan-card animate-slide delay-{{ min($index * 100 + 200, 500) }}"
         data-kode="{{ strtolower($item->kode_pesanan) }}"
         data-layanan="{{ strtolower($item->layanan->nama_layanan) }}"
         data-status="{{ strtolower($item->status) }}">
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
            
            // Cek invoice yang ditolak
            $invoiceDitolak = null;
            if($invoiceDP && $invoiceDP->status == 'Ditolak') {
                $invoiceDitolak = $invoiceDP;
            } elseif($invoicePelunasan && $invoicePelunasan->status == 'Ditolak') {
                $invoiceDitolak = $invoicePelunasan;
            }

            // Informasi Revisi
            $kuotaRevisi = $item->paket->jumlah_revisi ?? 0;
            $totalRevisi = $item->revisi()->count();
            $sisaRevisi = max(0, $kuotaRevisi - $totalRevisi);
            $revisiAktif = $item->revisi()->whereIn('status', ['Diminta', 'Sedang Dikerjakan'])->latest()->first();
            $bisaRevisi = in_array($item->status, ['Preview Siap', 'Menunggu Pelunasan']) && $dpLunas && $sisaRevisi > 0 && !$revisiAktif;
        @endphp

        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 pb-4 border-b border-gray-200">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="text-lg font-bold text-gray-800">{{ $item->kode_pesanan }}</h3>
                    
                    @if($kuotaRevisi > 0)
                    <span class="revisi-badge {{ $sisaRevisi > 0 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600' }}">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        {{ $sisaRevisi }}/{{ $kuotaRevisi }} Revisi
                    </span>
                    @endif
                </div>
                <p class="text-sm text-gray-600 flex items-center gap-2 ml-8">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ $item->created_at->format('d M Y H:i') }}
                </p>
            </div>
            <span class="{{ $statusClass }}">{{ $item->status }}</span>
        </div>

        <!-- Info Revisi Aktif -->
        @if($revisiAktif)
        <div class="bg-orange-50 border-l-4 border-orange-500 p-4 mb-4 rounded-r-lg">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-orange-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-orange-800 mb-1">
                        Revisi ke-{{ $revisiAktif->revisi_ke }} - {{ $revisiAktif->status }}
                    </h4>
                    <p class="text-xs text-orange-700 mb-2">
                        Diajukan: {{ $revisiAktif->created_at->format('d M Y H:i') }}
                    </p>
                    <a href="{{ route('client.revisi.show', $revisiAktif) }}" class="text-xs text-orange-600 hover:text-orange-800 font-medium">
                        Lihat Detail Revisi →
                    </a>
                </div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-xs text-gray-500 mb-1 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Layanan
                </p>
                <p class="font-semibold text-gray-900">{{ $item->layanan->nama_layanan }}</p>
                <p class="text-sm text-gray-600">{{ $item->paket->nama_paket ?? '-' }}</p>
            </div>
            <div class="bg-primary-50 p-4 rounded-lg">
                <p class="text-xs text-gray-500 mb-1 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Total Harga
                </p>
                <p class="font-semibold text-gray-900 text-xl">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</p>
            </div>
            <div class="bg-blue-50 p-4 rounded-lg">
                <p class="text-xs text-gray-500 mb-1 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Invoice
                </p>
                @if($invoiceDP)
                <p class="text-xs mb-1">DP: 
                    <span class="{{ $invoiceDP->status == 'Lunas' ? 'text-green-600 font-semibold' : ($invoiceDP->status == 'Ditolak' ? 'text-red-600 font-semibold' : 'text-yellow-600 font-semibold') }}">
                        {{ $invoiceDP->status }}
                    </span>
                </p>
                @endif
                @if($invoicePelunasan)
                <p class="text-xs">Pelunasan: 
                    <span class="{{ $invoicePelunasan->status == 'Lunas' ? 'text-green-600 font-semibold' : ($invoicePelunasan->status == 'Ditolak' ? 'text-red-600 font-semibold' : 'text-yellow-600 font-semibold') }}">
                        {{ $invoicePelunasan->status }}
                    </span>
                </p>
                @endif
            </div>
        </div>

        <!-- Pesan Penolakan Pembayaran -->
        @if($invoiceDitolak && $invoiceDitolak->alasan_penolakan)
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded-r-lg">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-red-800 mb-1">Pembayaran {{ $invoiceDitolak->tipe }} Ditolak</h4>
                    <p class="text-sm text-red-700">
                        <span class="font-medium">Alasan:</span> {{ $invoiceDitolak->alasan_penolakan }}
                    </p>
                    <p class="text-xs text-red-600 mt-2">
                        Silakan lakukan pembayaran ulang dengan data yang benar.
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-2 pt-4 border-t border-gray-200">
            <a href="{{ route('client.pesanan.show', $item) }}" class="btn-primary text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Lihat Detail
            </a>

            @if(in_array($item->status, ['Menunggu Pembayaran DP', 'Menunggu Pelunasan']))
            @php
                $invoice = $item->status == 'Menunggu Pembayaran DP' ? $invoiceDP : $invoicePelunasan;
            @endphp
            @if($invoice && $invoice->status != 'Lunas')
            <a href="{{ route('client.pembayaran.invoice', $invoice) }}" class="btn-success text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                {{ $invoice->status == 'Ditolak' ? 'Bayar Ulang' : 'Bayar Sekarang' }}
            </a>
            @endif
            @endif

            {{-- Tombol Ajukan Revisi --}}
            @if($bisaRevisi)
            <a href="{{ route('client.revisi.create', $item) }}" class="btn-warning text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Ajukan Revisi
            </a>
            @endif

            {{-- Tombol Batalkan Pesanan --}}
            @if($item->status == 'Menunggu Pembayaran DP' && (!$dpLunas || !$invoiceDP))
            <button onclick="confirmCancelOrder('{{ $item->id }}', '{{ $item->kode_pesanan }}')" 
                    class="btn-danger text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Batalkan Pesanan
            </button>
            @endif

            @if($item->status == 'Preview Siap')
                @if(!$dpLunas)
                    @if($invoiceDP && $invoiceDP->status !== 'Lunas')
                    <a href="{{ route('client.pembayaran.invoice', $invoiceDP) }}" class="btn-secondary text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        {{ $invoiceDP->status == 'Ditolak' ? 'Bayar Ulang DP' : 'Bayar DP untuk Preview' }}
                    </a>
                    @endif
                @elseif($previewAktif)
                <a href="{{ $item->preview_link }}" target="_blank" class="btn-secondary text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Lihat Preview
                </a>
                @else
                <button class="btn-secondary text-sm cursor-not-allowed opacity-60 flex items-center gap-2" disabled>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Preview Kadaluarsa
                </button>
                @endif
            @endif

            @if($item->status == 'Selesai' && $item->file_final)
            <a href="{{ route('client.pesanan.download-final', $item) }}" class="btn-secondary text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Download File
            </a>
            @endif
        </div>
    </div>
    @endforeach
</div>

<!-- No Results Message -->
<div id="noResults" class="hidden card text-center py-12">
    <div class="flex flex-col items-center justify-center gap-4">
        <div class="bg-gray-100 rounded-full p-6">
            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-gray-500 font-medium">Tidak ada hasil ditemukan</p>
            <p class="text-gray-400 text-sm mt-1">Coba ubah kata kunci pencarian Anda</p>
        </div>
    </div>
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $pesanan->links() }}
</div>

@else
<div class="card text-center py-12 animate-slide delay-200">
    <div class="flex flex-col items-center justify-center gap-4">
        <div class="bg-gray-100 rounded-full p-6">
        </div>
        <div>
            <h3 class="text-lg font-medium text-gray-900">Belum Ada Pesanan</h3>
            <p class="text-sm text-gray-500 mt-1">Mulai pesan layanan kami sekarang</p>
        </div>
        <a href="{{ route('client.layanan.index') }}" class="btn-primary mt-4 inline-flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            Lihat Layanan
        </a>
    </div>
</div>
@endif

<!-- Modal Konfirmasi Batalkan Pesanan -->
<div id="cancelOrderModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white animate-slide">
        <div class="mt-3">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 text-center mt-4 mb-2">Batalkan Pesanan?</h3>
            <p class="text-sm text-gray-600 text-center mb-1">Anda yakin ingin membatalkan pesanan</p>
            <p class="text-sm font-semibold text-gray-900 text-center mb-4" id="cancelOrderCode"></p>
            <p class="text-xs text-red-600 text-center mb-6">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Tindakan ini tidak dapat dibatalkan!
            </p>
            
            <form id="cancelOrderForm" method="POST" class="space-y-3">
                @csrf
                @method('PATCH')
                
                <div>
                    <label for="cancelReason" class="block text-sm font-medium text-gray-700 mb-2">
                        Alasan Pembatalan <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        id="cancelReason" 
                        name="alasan_pembatalan" 
                        rows="3" 
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition text-sm"
                        placeholder="Masukkan alasan pembatalan..."></textarea>
                </div>
                
                <div class="flex gap-3">
                    <button type="button" 
                            onclick="closeCancelModal()" 
                            class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition font-medium">
                        Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                        Ya, Batalkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isFirstLoad = !sessionStorage.getItem('pesanan_index_loaded');
        
        if (isFirstLoad) {
            document.body.classList.add('page-load');
            sessionStorage.setItem('pesanan_index_loaded', 'true');
            
            setTimeout(() => {
                document.body.classList.remove('page-load');
            }, 1000);
        }

        // Search and Filter Functionality
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const resetButton = document.getElementById('resetFilter');
        const pesananCards = document.querySelectorAll('.pesanan-card');
        const searchInfo = document.getElementById('searchInfo');
        const resultCount = document.getElementById('resultCount');
        const noResults = document.getElementById('noResults');
        const pesananContainer = document.getElementById('pesananContainer');

        function filterPesanan() {
            const searchTerm = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value.toLowerCase();
            let visibleCount = 0;

            pesananCards.forEach(card => {
                const kode = card.dataset.kode;
                const layanan = card.dataset.layanan;
                const status = card.dataset.status;

                const matchSearch = kode.includes(searchTerm) || 
                                  layanan.includes(searchTerm) ||
                                  status.includes(searchTerm);
                const matchStatus = statusValue === '' || status === statusValue;

                if (matchSearch && matchStatus) {
                    card.classList.remove('hidden-filter');
                    visibleCount++;
                } else {
                    card.classList.add('hidden-filter');
                }
            });

            // Update search info
            if (searchTerm || statusValue) {
                searchInfo.classList.remove('hidden');
                resultCount.textContent = visibleCount;
            } else {
                searchInfo.classList.add('hidden');
            }

            // Show/hide no results message
            if (visibleCount === 0 && pesananCards.length > 0) {
                noResults.classList.remove('hidden');
                if (pesananContainer) pesananContainer.style.display = 'none';
            } else {
                noResults.classList.add('hidden');
                if (pesananContainer) pesananContainer.style.display = 'block';
            }
        }

        // Event listeners
        if (searchInput) searchInput.addEventListener('input', filterPesanan);
        if (statusFilter) statusFilter.addEventListener('change', filterPesanan);
        
        if (resetButton) {
            resetButton.addEventListener('click', () => {
                searchInput.value = '';
                statusFilter.value = '';
                filterPesanan();
            });
        }
    });

    window.addEventListener('beforeunload', function(e) {
        if (e.target.activeElement.tagName === 'A' && 
            e.target.activeElement.getAttribute('href') !== '#') {
            sessionStorage.removeItem('pesanan_index_loaded');
        }
    });

    // Fungsi untuk menampilkan modal konfirmasi
    function confirmCancelOrder(pesananId, kodePesanan) {
        const modal = document.getElementById('cancelOrderModal');
        const form = document.getElementById('cancelOrderForm');
        const codeElement = document.getElementById('cancelOrderCode');
        
        codeElement.textContent = kodePesanan;
        form.action = `/client/pesanan/${pesananId}/cancel`;
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    // Fungsi untuk menutup modal
    function closeCancelModal() {
        const modal = document.getElementById('cancelOrderModal');
        const form = document.getElementById('cancelOrderForm');
        const textarea = document.getElementById('cancelReason');
        
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        form.reset();
        textarea.value = '';
    }

    // Tutup modal jika klik di luar modal
    document.getElementById('cancelOrderModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeCancelModal();
        }
    });

    // Tutup modal dengan tombol ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCancelModal();
        }
    });
</script>
@endsection