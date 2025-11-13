@extends('layouts.admin')

@section('title', 'Kelola Invoice')

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

    .card {
        transition: box-shadow 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .stats-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stats-card:hover {
        transform: scale(1.03);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
    }

    table tbody tr {
        transition: background-color 0.2s ease;
    }

    table tbody tr:hover {
        background-color: #f9fafb;
    }

    /* ✅ Row dibatalkan */
    table tbody tr.cancelled-row {
        opacity: 0.6;
        background-color: #f9fafb;
    }

    table tbody tr.cancelled-row:hover {
        background-color: #f3f4f6;
    }

    .search-input {
        transition: all 0.3s ease;
    }

    .search-input:focus {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }

    .jatuh-tempo-badge {
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
</style>

<div class="mb-6 animate-fade">
    <h1 class="text-3xl font-bold text-gray-800">Kelola Invoice</h1>
    <p class="text-gray-600">Manajemen invoice pembayaran</p>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="card bg-gradient-to-br from-blue-500 to-blue-600 text-white stats-card animate-slide cursor-pointer">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium mb-1">Total Invoice</p>
                <p class="text-3xl font-bold">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-white/20 p-4 rounded-xl backdrop-blur-sm">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="card bg-gradient-to-br from-green-500 to-green-600 text-white stats-card animate-slide delay-100 cursor-pointer">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium mb-1">Lunas</p>
                <p class="text-3xl font-bold">{{ $stats['lunas'] }}</p>
            </div>
            <div class="bg-white/20 p-4 rounded-xl backdrop-blur-sm">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="card bg-gradient-to-br from-yellow-500 to-yellow-600 text-white stats-card animate-slide delay-200 cursor-pointer">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-yellow-100 text-sm font-medium mb-1">Menunggu Verifikasi</p>
                <p class="text-3xl font-bold">{{ $stats['menunggu_verifikasi'] }}</p>
            </div>
            <div class="bg-white/20 p-4 rounded-xl backdrop-blur-sm">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="card bg-gradient-to-br from-red-500 to-red-600 text-white stats-card animate-slide delay-300 cursor-pointer">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-red-100 text-sm font-medium mb-1">Belum Dibayar</p>
                <p class="text-3xl font-bold">{{ $stats['belum_dibayar'] }}</p>
            </div>
            <div class="bg-white/20 p-4 rounded-xl backdrop-blur-sm">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Filter & Search -->
<div class="card mb-6 animate-slide delay-100">
    <div class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Real-time Search -->
            <div class="lg:col-span-2">
                <label for="realTimeSearch" class="block text-sm font-medium text-gray-700 mb-2">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Cari Invoice Real-time
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" 
                           id="realTimeSearch" 
                           class="search-input w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition" 
                           placeholder="Ketik nomor invoice, nama client, atau kode pesanan...">
                </div>
            </div>

            <!-- Filter Tipe -->
            <div>
                <label for="tipeFilter" class="block text-sm font-medium text-gray-700 mb-2">Tipe Invoice</label>
                <select id="tipeFilter" class="input-field w-full">
                    <option value="">Semua Tipe</option>
                    <option value="DP">DP</option>
                    <option value="Pelunasan">Pelunasan</option>
                </select>
            </div>

            <!-- Filter Status -->
            <div>
                <label for="statusFilter" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="statusFilter" class="input-field w-full">
                    <option value="">Semua Status</option>
                    <option value="Belum Dibayar">Belum Dibayar</option>
                    <option value="Menunggu Verifikasi">Menunggu Verifikasi</option>
                    <option value="Lunas">Lunas</option>
                    <option value="Dibatalkan">Dibatalkan</option>
                </select>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-2">
            <button id="resetFilter" class="btn-secondary flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Reset Filter
            </button>
        </div>

        <!-- Search Results Info -->
        <div id="searchInfo" class="text-sm text-gray-600 hidden">
            Menampilkan <span id="resultCount" class="font-semibold text-primary-600"></span> hasil dari <span id="totalCount" class="font-semibold">{{ $stats['total'] }}</span> invoice
        </div>
    </div>
</div>

<!-- Table -->
<div class="card animate-slide delay-200">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Invoice</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pesanan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jatuh Tempo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($invoices as $invoice)
                @php
                    // 🔥 LOGIKA BARU: Status invoice mengikuti status pesanan
                    // Jika pesanan dibatalkan, maka invoice otomatis dibatalkan
                    if ($invoice->pesanan->status === 'Dibatalkan') {
                        $displayStatus = 'Dibatalkan';
                    } else {
                        // Jika pesanan tidak dibatalkan, gunakan logika normal
                        $hasPendingPayment = $invoice->pembayaran && $invoice->pembayaran->where('status', 'Menunggu Verifikasi')->count() > 0;
                        $displayStatus = $hasPendingPayment ? 'Menunggu Verifikasi' : $invoice->status;
                    }
                    
                    // ✅ Class untuk row yang dibatalkan
                    $rowClass = $displayStatus === 'Dibatalkan' ? 'cancelled-row' : '';
                @endphp
                <tr class="invoice-row {{ $rowClass }}"
                    data-nomor="{{ strtolower($invoice->nomor_invoice) }}"
                    data-client="{{ strtolower($invoice->pesanan->client->name) }}"
                    data-email="{{ strtolower($invoice->pesanan->client->email) }}"
                    data-pesanan="{{ strtolower($invoice->pesanan->kode_pesanan) }}"
                    data-tipe="{{ $invoice->tipe }}"
                    data-status="{{ $displayStatus }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-gray-900">{{ $invoice->nomor_invoice }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $invoice->pesanan->client->name }}</div>
                        <div class="text-sm text-gray-500">{{ $invoice->pesanan->client->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $invoice->pesanan->kode_pesanan }}
                        @if($invoice->pesanan->status === 'Dibatalkan')
                        <div class="text-xs text-red-600 mt-1">
                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Pesanan Dibatalkan
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="badge-{{ $invoice->tipe == 'DP' ? 'warning' : 'info' }} text-xs">{{ $invoice->tipe }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Rp {{ number_format($invoice->jumlah, 0, ',', '.') }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($displayStatus == 'Lunas')
                        <span class="badge-success inline-flex items-center gap-1">
                            <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                            Lunas
                        </span>
                        @elseif($displayStatus == 'Menunggu Verifikasi')
                        <span class="badge-warning inline-flex items-center gap-1">
                            <span class="w-2 h-2 bg-yellow-400 rounded-full"></span>
                            Menunggu Verifikasi
                        </span>
                        @elseif($displayStatus == 'Dibatalkan')
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                            Dibatalkan
                        </span>
                        @else
                        <span class="badge-danger inline-flex items-center gap-1">
                            <span class="w-2 h-2 bg-red-400 rounded-full"></span>
                            Belum Dibayar
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $invoice->tanggal_jatuh_tempo->format('d/m/Y') }}
                        </div>
                        @if($invoice->is_jatuh_tempo && $displayStatus != 'Lunas' && $displayStatus != 'Dibatalkan')
                        <span class="text-red-600 text-xs flex items-center gap-1 mt-1 jatuh-tempo-badge">
                            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                            Jatuh Tempo
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.invoice.show', $invoice) }}" class="text-blue-600 hover:text-blue-900 transition-colors">Detail</a>
                            @if($displayStatus == 'Menunggu Verifikasi')
                            <a href="{{ route('admin.pembayaran.pending') }}" class="text-orange-600 hover:text-orange-900 transition-colors">Verifikasi</a>
                            @endif
                            <a href="{{ route('admin.invoice.download', $invoice) }}" class="text-green-600 hover:text-green-900 transition-colors">Download</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr id="emptyRow">
                    <td colspan="8" class="px-6 py-8 text-center">
                        <div class="flex flex-col items-center justify-center gap-4">
                            <div class="bg-gray-100 rounded-full p-6">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <p class="text-gray-500">Tidak ada invoice</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- No Results Message -->
    <div id="noResults" class="hidden px-6 py-12 text-center">
        <div class="flex flex-col items-center justify-center gap-4">
            <div class="bg-gray-100 rounded-full p-6">
                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-gray-500 font-medium">Tidak ada hasil ditemukan</p>
                <p class="text-gray-400 text-sm mt-1">Coba ubah kata kunci pencarian atau filter Anda</p>
            </div>
        </div>
    </div>
    
    <!-- Pagination -->
    @if($invoices->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $invoices->links() }}
    </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // First load animation
        const isFirstLoad = !sessionStorage.getItem('invoice_loaded');
        
        if (isFirstLoad) {
            document.body.classList.add('page-load');
            sessionStorage.setItem('invoice_loaded', 'true');
            
            setTimeout(() => {
                document.body.classList.remove('page-load');
            }, 1000);
        }

        // Real-time Search and Filter
        const realTimeSearch = document.getElementById('realTimeSearch');
        const tipeFilter = document.getElementById('tipeFilter');
        const statusFilter = document.getElementById('statusFilter');
        const resetButton = document.getElementById('resetFilter');
        const invoiceRows = document.querySelectorAll('.invoice-row');
        const searchInfo = document.getElementById('searchInfo');
        const resultCount = document.getElementById('resultCount');
        const noResults = document.getElementById('noResults');
        const emptyRow = document.getElementById('emptyRow');

        function filterInvoice() {
            const searchTerm = realTimeSearch.value.toLowerCase();
            const tipeValue = tipeFilter.value;
            const statusValue = statusFilter.value;
            let visibleCount = 0;

            invoiceRows.forEach(row => {
                const nomor = row.dataset.nomor;
                const client = row.dataset.client;
                const email = row.dataset.email;
                const pesanan = row.dataset.pesanan;
                const tipe = row.dataset.tipe;
                const status = row.dataset.status;

                const matchSearch = nomor.includes(searchTerm) || 
                                  client.includes(searchTerm) || 
                                  email.includes(searchTerm) ||
                                  pesanan.includes(searchTerm);
                const matchTipe = tipeValue === '' || tipe === tipeValue;
                const matchStatus = statusValue === '' || status === statusValue;

                if (matchSearch && matchTipe && matchStatus) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Update search info
            if (searchTerm || tipeValue || statusValue) {
                searchInfo.classList.remove('hidden');
                resultCount.textContent = visibleCount;
            } else {
                searchInfo.classList.add('hidden');
            }

            // Show/hide no results message
            if (visibleCount === 0 && invoiceRows.length > 0) {
                noResults.classList.remove('hidden');
                if (emptyRow) emptyRow.style.display = 'none';
            } else {
                noResults.classList.add('hidden');
            }
        }

        // Event listeners
        realTimeSearch.addEventListener('input', filterInvoice);
        tipeFilter.addEventListener('change', filterInvoice);
        statusFilter.addEventListener('change', filterInvoice);
        
        resetButton.addEventListener('click', () => {
            realTimeSearch.value = '';
            tipeFilter.value = '';
            statusFilter.value = '';
            filterInvoice();
        });
    });

    window.addEventListener('beforeunload', function(e) {
        if (e.target.activeElement.tagName === 'A' && 
            e.target.activeElement.getAttribute('href') !== '#') {
            sessionStorage.removeItem('invoice_loaded');
        }
    });
</script>
@endsection