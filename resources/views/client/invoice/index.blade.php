@extends('layouts.client')

@section('title', 'Invoice Saya')

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

    .invoice-card {
        transition: opacity 0.3s ease;
    }

    .invoice-card:hover {
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
        transform: translateY(-4px);
    }

    .invoice-card.hidden-filter {
        display: none;
    }

    .btn-primary, .btn-success, .btn-secondary {
        transition: all 0.2s ease;
    }

    .btn-primary:hover, .btn-success:hover, .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
    }

    .search-input, .filter-input {
        transition: all 0.3s ease;
    }

    .search-input:focus, .filter-input:focus {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }
</style>

<div class="mb-6 animate-fade">
    <h1 class="text-3xl font-bold text-gray-800">Invoice Saya</h1>
    <p class="text-gray-600">Kelola dan pantau invoice pembayaran Anda</p>
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
                       placeholder="Cari berdasarkan nomor invoice, kode pesanan, atau layanan...">
            </div>
        </div>

        <!-- Filter Status -->
        <div class="md:w-64">
            <select id="statusFilter" 
                    class="filter-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition">
                <option value="">Semua Status</option>
                <option value="belum lunas">Belum Lunas</option>
                <option value="menunggu verifikasi">Menunggu Verifikasi</option>
                <option value="ditolak">Ditolak</option>
                <option value="dibatalkan">Dibatalkan</option>
                <option value="lunas">Lunas</option>
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

<!-- Invoice List -->
@if($invoices->count() > 0)
<div class="space-y-4" id="invoiceContainer">
    @foreach($invoices as $index => $invoice)
    @php
        // Jika pesanan dibatalkan, maka status invoice otomatis dibatalkan
        $statusInvoice = $invoice->status;
        $statusClass = 'badge-warning';
        
        if($invoice->pesanan->status == 'Dibatalkan') {
            $statusInvoice = 'Dibatalkan';
            $statusClass = 'badge-secondary';
        } elseif($invoice->status == 'Lunas') {
            $statusClass = 'badge-success';
        } elseif($invoice->status == 'Menunggu Verifikasi') {
            $statusClass = 'badge-info';
        } elseif($invoice->status == 'Ditolak') {
            $statusClass = 'badge-danger';
        } elseif($invoice->status == 'Dibatalkan') {
            $statusClass = 'badge-secondary';
        }
    @endphp

    <div class="card invoice-card animate-slide delay-{{ min($index * 100 + 200, 500) }}"
         data-nomor="{{ strtolower($invoice->nomor_invoice) }}"
         data-kode="{{ strtolower($invoice->pesanan->kode_pesanan) }}"
         data-layanan="{{ strtolower($invoice->pesanan->layanan->nama_layanan ?? $invoice->pesanan->layanan->nama) }}"
         data-status="{{ strtolower($statusInvoice) }}">
        
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 pb-4 border-b border-gray-200">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                    </svg>
                    <h3 class="text-lg font-bold text-gray-800">{{ $invoice->nomor_invoice }}</h3>
                </div>
                <p class="text-sm text-gray-600 flex items-center gap-2 ml-8">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ $invoice->created_at->format('d M Y H:i') }}
                </p>
            </div>
            <span class="{{ $statusClass }}">{{ $statusInvoice }}</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-xs text-gray-500 mb-1 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Pesanan
                </p>
                <p class="font-semibold text-gray-900">{{ $invoice->pesanan->kode_pesanan }}</p>
                <p class="text-sm text-gray-600">{{ $invoice->pesanan->layanan->nama_layanan ?? $invoice->pesanan->layanan->nama }}</p>
            </div>
            <div class="bg-blue-50 p-4 rounded-lg">
                <p class="text-xs text-gray-500 mb-1 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Tipe Invoice
                </p>
                <p class="font-semibold text-gray-900">{{ $invoice->tipe }}</p>
            </div>
            <div class="bg-primary-50 p-4 rounded-lg">
                <p class="text-xs text-gray-500 mb-1 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Jumlah
                </p>
                <p class="font-semibold text-gray-900 text-xl">Rp {{ number_format($invoice->jumlah, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Jatuh Tempo - Hanya tampil jika status bukan Dibatalkan -->
        @if($statusInvoice != 'Dibatalkan')
        <div class="bg-{{ $invoice->is_jatuh_tempo ? 'red' : 'gray' }}-50 p-3 rounded-lg mb-4">
            <p class="text-sm {{ $invoice->is_jatuh_tempo ? 'text-red-700' : 'text-gray-700' }} flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                @if($invoice->is_jatuh_tempo)
                    <span class="font-semibold">⚠️ Jatuh tempo:</span>
                @else
                    <span class="font-semibold">Jatuh tempo:</span>
                @endif
                {{ $invoice->tanggal_jatuh_tempo->format('d M Y') }}
            </p>
        </div>
        @endif

        <!-- Status Pesanan Dibatalkan -->
        @if($invoice->pesanan->status == 'Dibatalkan')
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded-r-lg">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-red-800 mb-1">Pesanan Dibatalkan</h4>
                    <p class="text-sm text-red-700">
                        <span class="font-medium">Alasan:</span> {{ $invoice->pesanan->alasan_pembatalan ?? 'Pesanan telah dibatalkan oleh client' }}
                    </p>
                    <p class="text-xs text-red-600 mt-1">
                        Status invoice otomatis berubah menjadi Dibatalkan
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Pesan Pembatalan Invoice -->
        @if($invoice->status == 'Dibatalkan' && $invoice->pesanan->status != 'Dibatalkan')
        <div class="bg-gray-50 border-l-4 border-gray-500 p-4 mb-4 rounded-r-lg">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-gray-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-gray-800 mb-1">Invoice Dibatalkan</h4>
                    <p class="text-sm text-gray-700">
                        <span class="font-medium">Alasan:</span> {{ $invoice->alasan_penolakan ?? 'Invoice dibatalkan karena melewati batas jatuh tempo' }}
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Pesan Penolakan Pembayaran -->
        @if($invoice->status == 'Ditolak' && $invoice->alasan_penolakan)
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded-r-lg">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-red-800 mb-1">Pembayaran Ditolak</h4>
                    <p class="text-sm text-red-700">
                        <span class="font-medium">Alasan:</span> {{ $invoice->alasan_penolakan }}
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-2 pt-4 border-t border-gray-200">
            <a href="{{ route('client.invoice.download', $invoice) }}" class="btn-secondary text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Download PDF
            </a>
            
            @if($statusInvoice == 'Belum Lunas' && $invoice->pesanan->status != 'Dibatalkan')
                <a href="{{ route('client.pembayaran.invoice', $invoice) }}" class="btn-primary text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Bayar Sekarang
                </a>
            @elseif($statusInvoice == 'Ditolak' && $invoice->pesanan->status != 'Dibatalkan')
                <a href="{{ route('client.pembayaran.invoice', $invoice) }}" class="btn-primary text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Bayar Ulang
                </a>
            @elseif($statusInvoice == 'Dibatalkan' || $invoice->pesanan->status == 'Dibatalkan')
                <a href="{{ route('client.layanan.show', $invoice->pesanan->layanan_id) }}" class="btn-success text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Pesan Lagi
                </a>
            @elseif($statusInvoice == 'Menunggu Verifikasi')
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-blue-100 text-blue-700 rounded-lg font-medium text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Menunggu Verifikasi
                </span>
            @elseif($statusInvoice == 'Lunas')
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-green-100 text-green-700 rounded-lg font-medium text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Lunas
                </span>
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
    {{ $invoices->links() }}
</div>

@else
<div class="card text-center py-12 animate-slide delay-200">
    <div class="flex flex-col items-center justify-center gap-4">
        <div class="bg-gray-100 rounded-full p-6">
            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-medium text-gray-900">Belum Ada Invoice</h3>
            <p class="text-sm text-gray-500 mt-1">Invoice akan muncul setelah Anda melakukan pemesanan</p>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isFirstLoad = !sessionStorage.getItem('invoice_index_loaded');
        
        if (isFirstLoad) {
            document.body.classList.add('page-load');
            sessionStorage.setItem('invoice_index_loaded', 'true');
            
            setTimeout(() => {
                document.body.classList.remove('page-load');
            }, 1000);
        }

        // Search and Filter Functionality
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const resetButton = document.getElementById('resetFilter');
        const invoiceCards = document.querySelectorAll('.invoice-card');
        const searchInfo = document.getElementById('searchInfo');
        const resultCount = document.getElementById('resultCount');
        const noResults = document.getElementById('noResults');
        const invoiceContainer = document.getElementById('invoiceContainer');

        function filterInvoice() {
            const searchTerm = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value.toLowerCase();
            let visibleCount = 0;

            invoiceCards.forEach(card => {
                const nomor = card.dataset.nomor;
                const kode = card.dataset.kode;
                const layanan = card.dataset.layanan;
                const status = card.dataset.status;

                const matchSearch = nomor.includes(searchTerm) || 
                                  kode.includes(searchTerm) ||
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
            if (visibleCount === 0 && invoiceCards.length > 0) {
                noResults.classList.remove('hidden');
                if (invoiceContainer) invoiceContainer.style.display = 'none';
            } else {
                noResults.classList.add('hidden');
                if (invoiceContainer) invoiceContainer.style.display = 'block';
            }
        }

        // Event listeners
        if (searchInput) searchInput.addEventListener('input', filterInvoice);
        if (statusFilter) statusFilter.addEventListener('change', filterInvoice);
        
        if (resetButton) {
            resetButton.addEventListener('click', () => {
                searchInput.value = '';
                statusFilter.value = '';
                filterInvoice();
            });
        }
    });

    window.addEventListener('beforeunload', function(e) {
        if (e.target.activeElement.tagName === 'A' && 
            e.target.activeElement.getAttribute('href') !== '#') {
            sessionStorage.removeItem('invoice_index_loaded');
        }
    });
</script>
@endsection