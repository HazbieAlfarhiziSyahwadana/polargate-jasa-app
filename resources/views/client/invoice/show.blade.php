@extends('layouts.client')

@section('title', 'Detail Invoice')

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

    .btn-primary, .btn-success, .btn-secondary {
        transition: all 0.2s ease;
    }

    .btn-primary:hover, .btn-success:hover, .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
    }

    .info-card {
        transition: all 0.3s ease;
    }

    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    @media print {
        .no-print {
            display: none;
        }
    }
</style>

<!-- Header with Back Button -->
<div class="mb-6 animate-fade">
    <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('client.invoice.index') }}" class="inline-flex items-center text-gray-600 hover:text-primary-600 transition-colors">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>
    <h1 class="text-3xl font-bold text-gray-800">Detail Invoice</h1>
    <p class="text-gray-600">Informasi lengkap invoice pembayaran</p>
</div>

<!-- Invoice Header Card -->
<div class="card mb-6 animate-slide delay-100">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 pb-6 border-b border-gray-200">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                </svg>
                <h2 class="text-2xl font-bold text-gray-800">{{ $invoice->nomor_invoice }}</h2>
            </div>
            <p class="text-sm text-gray-600 flex items-center gap-2 ml-9">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Dibuat: {{ $invoice->created_at->format('d M Y H:i') }}
            </p>
        </div>
        <div class="mt-4 md:mt-0 flex flex-wrap gap-2">
            @php
                $statusClass = 'badge-warning';
                if($invoice->status == 'Lunas') $statusClass = 'badge-success';
                if($invoice->status == 'Menunggu Verifikasi') $statusClass = 'badge-info';
            @endphp
            <span class="{{ $statusClass }} text-lg">{{ $invoice->status }}</span>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-wrap gap-3 no-print">
        <a href="{{ route('client.invoice.download', $invoice->id) }}" class="btn-primary flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Download PDF
        </a>
        @if($invoice->status != 'Lunas')
        <a href="{{ route('client.pembayaran.invoice', $invoice) }}" class="btn-success flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            Bayar Sekarang
        </a>
        @endif
        <button onclick="window.print()" class="btn-secondary flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Cetak
        </button>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Informasi Pesanan -->
    <div class="card animate-slide delay-200">
        <div class="flex items-center gap-3 mb-4 pb-3 border-b border-gray-200">
            <div class="bg-primary-100 p-2 rounded-lg">
                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800">Informasi Pesanan</h3>
        </div>
        <div class="space-y-3">
            <div class="info-card bg-gray-50 p-4 rounded-lg">
                <p class="text-xs text-gray-500 mb-1">Kode Pesanan</p>
                <p class="font-semibold text-gray-900">{{ $invoice->pesanan->kode_pesanan }}</p>
            </div>
            <div class="info-card bg-gray-50 p-4 rounded-lg">
                <p class="text-xs text-gray-500 mb-1">Layanan</p>
                <p class="font-semibold text-gray-900">{{ $invoice->pesanan->layanan->nama }}</p>
            </div>
            <div class="info-card bg-gray-50 p-4 rounded-lg">
                <p class="text-xs text-gray-500 mb-1">Paket</p>
                <p class="font-semibold text-gray-900">{{ $invoice->pesanan->paket->nama }}</p>
            </div>
        </div>
    </div>

    <!-- Informasi Invoice -->
    <div class="card animate-slide delay-300">
        <div class="flex items-center gap-3 mb-4 pb-3 border-b border-gray-200">
            <div class="bg-blue-100 p-2 rounded-lg">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800">Informasi Invoice</h3>
        </div>
        <div class="space-y-3">
            <div class="info-card bg-blue-50 p-4 rounded-lg">
                <p class="text-xs text-gray-500 mb-1">Tipe Pembayaran</p>
                <p class="font-semibold text-gray-900">{{ $invoice->tipe }}</p>
            </div>
            <div class="info-card bg-blue-50 p-4 rounded-lg">
                <p class="text-xs text-gray-500 mb-1">Status Pembayaran</p>
                <span class="{{ $statusClass }}">{{ $invoice->status }}</span>
            </div>
            <div class="info-card bg-{{ $invoice->is_jatuh_tempo ? 'red' : 'blue' }}-50 p-4 rounded-lg">
                <p class="text-xs text-gray-500 mb-1">Jatuh Tempo</p>
                <p class="font-semibold {{ $invoice->is_jatuh_tempo ? 'text-red-600' : 'text-gray-900' }} flex items-center gap-2">
                    @if($invoice->is_jatuh_tempo)
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    @endif
                    {{ \Carbon\Carbon::parse($invoice->jatuh_tempo)->format('d M Y') }}
                    @if($invoice->is_jatuh_tempo)
                    <span class="text-xs">(Lewat Jatuh Tempo)</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Detail Pembayaran -->
<div class="card animate-slide delay-400">
    <div class="flex items-center gap-3 mb-4 pb-3 border-b border-gray-200">
        <div class="bg-green-100 p-2 rounded-lg">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-800">Detail Pembayaran</h3>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span class="font-medium text-gray-900">{{ $invoice->tipe }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right font-semibold text-gray-900">
                        Rp {{ number_format($invoice->jumlah, 0, ',', '.') }}
                    </td>
                </tr>
                @if($invoice->pesanan->addons->count() > 0)
                    @foreach($invoice->pesanan->addons as $addon)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                <span class="text-gray-700">{{ $addon->nama }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right text-gray-900">
                            Rp {{ number_format($addon->pivot->harga, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
            <tfoot class="bg-primary-50">
                <tr>
                    <th class="px-6 py-4 text-left text-base font-bold text-gray-900 uppercase">Total</th>
                    <th class="px-6 py-4 text-right text-xl font-bold text-primary-600">
                        Rp {{ number_format($invoice->jumlah, 0, ',', '.') }}
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- Bukti Pembayaran -->
@if($invoice->bukti_pembayaran)
<div class="card animate-slide delay-500">
    <div class="flex items-center gap-3 mb-4 pb-3 border-b border-gray-200">
        <div class="bg-purple-100 p-2 rounded-lg">
            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-800">Bukti Pembayaran</h3>
    </div>
    <div class="bg-gray-50 p-6 rounded-lg">
        <div class="max-w-md mx-auto">
            <img src="{{ asset('storage/' . $invoice->bukti_pembayaran) }}" 
                 alt="Bukti Pembayaran" 
                 class="w-full h-auto rounded-lg shadow-lg hover:shadow-xl transition-shadow cursor-pointer"
                 onclick="openImageModal(this.src)">
            <p class="text-sm text-gray-500 text-center mt-3">
                <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Klik gambar untuk memperbesar
            </p>
        </div>
    </div>
</div>

<!-- Modal Preview Gambar -->
<div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4" onclick="closeImageModal()">
    <div class="relative max-w-4xl max-h-screen">
        <button onclick="closeImageModal()" class="absolute -top-10 right-0 text-white hover:text-gray-300 transition-colors">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <img id="modalImage" src="" alt="Preview" class="max-w-full max-h-screen rounded-lg shadow-2xl">
    </div>
</div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isFirstLoad = !sessionStorage.getItem('invoice_show_loaded');
        
        if (isFirstLoad) {
            document.body.classList.add('page-load');
            sessionStorage.setItem('invoice_show_loaded', 'true');
            
            setTimeout(() => {
                document.body.classList.remove('page-load');
            }, 1000);
        }
    });

    window.addEventListener('beforeunload', function(e) {
        if (e.target.activeElement.tagName === 'A' && 
            e.target.activeElement.getAttribute('href') !== '#') {
            sessionStorage.removeItem('invoice_show_loaded');
        }
    });

    // Modal Image Preview
    function openImageModal(src) {
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        modalImage.src = src;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
        }
    });
</script>
@endsection