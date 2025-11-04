@extends('layouts.admin')

@section('title', 'Dashboard Admin')

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

    .card {
        transition: box-shadow 0.3s ease, transform 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        transform: translateY(-2px);
    }

    .stats-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stats-card:hover {
        transform: scale(1.03);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    }

    .item-row {
        transition: all 0.2s ease;
    }

    .item-row:hover {
        background-color: #f9fafb;
        transform: translateX(4px);
    }

    @media (max-width: 768px) {
        .overflow-x-auto {
            -webkit-overflow-scrolling: touch;
        }
    }
</style>

<div class="mb-6 animate-fade">
    <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
    <p class="text-gray-600">Selamat datang, {{ Auth::user()->name }}!</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6">
    <!-- Total Pesanan -->
    <div class="card bg-gradient-to-br from-blue-500 to-blue-600 text-white stats-card animate-slide cursor-pointer">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium">Total Pesanan</p>
                <p class="text-3xl font-bold mt-2">{{ $total_pesanan }}</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Total Client -->
    <div class="card bg-gradient-to-br from-green-500 to-green-600 text-white stats-card animate-slide delay-100 cursor-pointer">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium">Total Client</p>
                <p class="text-3xl font-bold mt-2">{{ $total_client }}</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Pesanan Aktif -->
    <div class="card bg-gradient-to-br from-yellow-500 to-yellow-600 text-white stats-card animate-slide delay-200 cursor-pointer">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-yellow-100 text-sm font-medium">Pesanan Aktif</p>
                <p class="text-3xl font-bold mt-2">{{ $pesanan_aktif }}</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Pendapatan Total -->
    <div class="card bg-gradient-to-br from-purple-500 to-purple-600 text-white stats-card animate-slide delay-300 cursor-pointer">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium">Pendapatan Total</p>
                <p class="text-2xl md:text-3xl font-bold mt-2">Rp {{ number_format($pendapatan_total, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
    <!-- Pesanan Terbaru -->
    <div class="card animate-slide delay-100">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-800">Pesanan Terbaru</h2>
            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
        <div class="space-y-3">
            @forelse($pesanan_terbaru as $pesanan)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg item-row">
                <div class="flex-1">
                    <p class="font-semibold text-gray-800">{{ $pesanan->kode_pesanan }}</p>
                    <p class="text-sm text-gray-600">{{ $pesanan->client->name }}</p>
                    <p class="text-sm text-gray-500">{{ $pesanan->layanan->nama_layanan }}</p>
                </div>
                <div class="text-right">
                    <span class="badge-info text-xs">{{ $pesanan->status }}</span>
                    <p class="text-xs text-gray-500 mt-1">{{ $pesanan->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <div class="text-center py-8">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-gray-500">Belum ada pesanan</p>
            </div>
            @endforelse
        </div>
        <a href="{{ route('admin.pesanan.index') }}" class="block text-center text-primary-600 hover:text-primary-700 font-medium mt-4 transition-colors">
            Lihat Semua Pesanan →
        </a>
    </div>

    <!-- Pembayaran Pending -->
    <div class="card animate-slide delay-200">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-800">Pembayaran Menunggu Verifikasi</h2>
            <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <div class="space-y-3">
            @forelse($pembayaran_pending as $pembayaran)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg item-row">
                <div class="flex-1">
                    <p class="font-semibold text-gray-800">{{ $pembayaran->invoice->nomor_invoice }}</p>
                    <p class="text-sm text-gray-600">{{ $pembayaran->invoice->pesanan->client->name }}</p>
                    <p class="text-sm font-medium text-gray-800">Rp {{ number_format($pembayaran->jumlah_dibayar, 0, ',', '.') }}</p>
                </div>
                <div class="text-right">
                    <span class="badge-warning text-xs">Pending</span>
                    <p class="text-xs text-gray-500 mt-1">{{ $pembayaran->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <div class="text-center py-8">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-gray-500">Tidak ada pembayaran pending</p>
            </div>
            @endforelse
        </div>
        <a href="{{ route('admin.pembayaran.pending') }}" class="block text-center text-primary-600 hover:text-primary-700 font-medium mt-4 transition-colors">
            Lihat Semua Pembayaran →
        </a>
    </div>
</div>

<!-- Layanan Populer -->
<div class="card mt-6 animate-slide delay-300">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold text-gray-800">Layanan Paling Populer</h2>
        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
            </svg>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Layanan</th>
                    <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Pesanan</th>
                    <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Mulai</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($layanan_populer as $layanan)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-gray-900">{{ $layanan->nama_layanan }}</div>
                    </td>
                    <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                        <span class="badge-info">{{ $layanan->kategori }}</span>
                    </td>
                    <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $layanan->pesanan_count }} pesanan
                    </td>
                    <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                        Rp {{ number_format($layanan->harga_mulai, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="text-gray-500">Belum ada data</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cek apakah ini first load
        const isFirstLoad = !sessionStorage.getItem('dashboard_loaded');
        
        if (isFirstLoad) {
            document.body.classList.add('page-load');
            sessionStorage.setItem('dashboard_loaded', 'true');
            
            setTimeout(() => {
                document.body.classList.remove('page-load');
            }, 1000);
        }
    });

    // Reset flag saat navigasi ke halaman lain
    window.addEventListener('beforeunload', function(e) {
        if (e.target.activeElement.tagName === 'A' && 
            e.target.activeElement.getAttribute('href') !== '#') {
            sessionStorage.removeItem('dashboard_loaded');
        }
    });
</script>
@endsection