@extends('layouts.admin')

@section('title', 'Laporan Keuangan')

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
        transition: box-shadow 0.3s ease, transform 0.3s ease;
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

    .btn-primary {
        transition: all 0.2s ease;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
    }

    .btn-secondary {
        transition: all 0.2s ease;
    }

    .btn-secondary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
    }

    .btn-success {
        transition: all 0.2s ease;
    }

    .btn-success:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
    }

    table tbody tr {
        transition: background-color 0.2s ease;
    }

    table tbody tr:hover {
        background-color: #f9fafb;
    }
</style>

<div class="animate-fade">
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Laporan Keuangan</h1>
        <p class="text-sm text-gray-600 flex items-center gap-2">
            <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            Laporan pendapatan dari pesanan yang sudah selesai
        </p>
    </div>

   <!-- Filter Card -->
<div class="card mb-6 animate-slide">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Invoice</label>
            <select name="tipe" class="input-field">
                <option value="">Semua Tipe</option>
                <option value="DP" {{ request('tipe') == 'DP' ? 'selected' : '' }}>DP</option>
                <option value="Pelunasan" {{ request('tipe') == 'Pelunasan' ? 'selected' : '' }}>Pelunasan</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status Invoice</label>
            <select name="status_invoice" class="input-field">
                <option value="">Semua Status</option>
                <option value="Lunas" {{ request('status_invoice') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                <option value="Belum Lunas" {{ request('status_invoice') == 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
            <select name="bulan" class="input-field">
                <option value="">Semua Bulan</option>
                @for($i = 1; $i <= 12; $i++)
                <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                    {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                </option>
                @endfor
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
            <select name="tahun" class="input-field">
                @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                <option value="{{ $i }}" {{ request('tahun', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="flex items-end gap-2 md:col-span-4">
            <button type="submit" class="btn-primary flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                Filter
            </button>
            <a href="{{ route('admin.laporan.keuangan') }}" class="btn-secondary flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Stats Cards untuk DP dan Pelunasan -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-6 animate-slide delay-100">
    <!-- DP Card -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white stats-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium mb-1">DP</p>
                <h3 class="text-3xl font-bold">{{ $statistikTipe['dp']['count'] }}</h3>
                <p class="text-blue-100 text-xs mt-1">Rp {{ number_format($statistikTipe['dp']['total'], 0, ',', '.') }}</p>
                <p class="text-blue-100 text-xs mt-1">{{ $statistikTipe['dp']['lunas'] }} invoice lunas</p>
            </div>
            <div class="bg-white/20 p-4 rounded-xl backdrop-blur-sm">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Pelunasan Card -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white stats-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium mb-1">Pelunasan</p>
                <h3 class="text-3xl font-bold">{{ $statistikTipe['pelunasan']['count'] }}</h3>
                <p class="text-green-100 text-xs mt-1">Rp {{ number_format($statistikTipe['pelunasan']['total'], 0, ',', '.') }}</p>
                <p class="text-green-100 text-xs mt-1">{{ $statistikTipe['pelunasan']['lunas'] }} invoice lunas</p>
            </div>
            <div class="bg-white/20 p-4 rounded-xl backdrop-blur-sm">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Total Pendapatan Card -->
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white stats-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium mb-1">Total Pendapatan</p>
                <h3 class="text-2xl font-bold">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                <p class="text-purple-100 text-xs mt-1">Dari DP & Pelunasan yang lunas</p>
            </div>
            <div class="bg-white/20 p-4 rounded-xl backdrop-blur-sm">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
        </div>
    </div>
</div>
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-6 animate-slide delay-100">
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white stats-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">Total Pendapatan</p>
                    <h3 class="text-3xl font-bold">Rp {{ number_format($pendapatanPerBulan->sum('total'), 0, ',', '.') }}</h3>
                    <p class="text-green-100 text-xs mt-1">Dari pesanan selesai</p>
                </div>
                <div class="bg-white/20 p-4 rounded-xl backdrop-blur-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white stats-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Total Invoice</p>
                    <h3 class="text-3xl font-bold">{{ $invoices->count() }}</h3>
                    <p class="text-blue-100 text-xs mt-1">Invoice pesanan selesai</p>
                </div>
                <div class="bg-white/20 p-4 rounded-xl backdrop-blur-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white stats-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium mb-1">Invoice Lunas</p>
                    <h3 class="text-3xl font-bold">{{ $invoices->where('status', 'Lunas')->count() }}</h3>
                    <p class="text-purple-100 text-xs mt-1">{{ $invoices->count() > 0 ? round(($invoices->where('status', 'Lunas')->count() / $invoices->count()) * 100) : 0 }}% dari total</p>
                </div>
                <div class="bg-white/20 p-4 rounded-xl backdrop-blur-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card overflow-hidden animate-slide delay-200">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-4">
            <h2 class="text-xl font-bold text-gray-800">Daftar Invoice (Pesanan Selesai)</h2>
            <a href="{{ route('admin.laporan.export-keuangan', request()->all()) }}" 
               class="btn-success text-sm inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export CSV
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">No Invoice</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Layanan</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Tipe</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($invoices as $invoice)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-2 flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div class="font-semibold text-gray-900">{{ $invoice->nomor_invoice }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $invoice->pesanan->client->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $invoice->pesanan->layanan->nama_layanan ?? $invoice->pesanan->layanan->nama }}</td>
                        <td class="px-6 py-4">
                            <span class="badge-info">{{ $invoice->tipe }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1 text-sm font-semibold text-gray-900">
                                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Rp {{ number_format($invoice->jumlah, 0, ',', '.') }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($invoice->status == 'Lunas')
                            <span class="badge-success inline-flex items-center gap-1">
                                <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                                Lunas
                            </span>
                            @else
                            <span class="badge-danger inline-flex items-center gap-1">
                                <span class="w-2 h-2 bg-red-400 rounded-full"></span>
                                Belum Lunas
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $invoice->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center gap-4">
                                <div class="bg-gray-100 rounded-full p-6">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-gray-500 font-medium">Tidak ada data invoice</p>
                                    <p class="text-gray-400 text-sm mt-1">Belum ada pesanan yang selesai pada periode ini</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isFirstLoad = !sessionStorage.getItem('laporan_keuangan_loaded');
        
        if (isFirstLoad) {
            document.body.classList.add('page-load');
            sessionStorage.setItem('laporan_keuangan_loaded', 'true');
            
            setTimeout(() => {
                document.body.classList.remove('page-load');
            }, 1000);
        }
    });

    window.addEventListener('beforeunload', function(e) {
        if (e.target.activeElement.tagName === 'A' && 
            e.target.activeElement.getAttribute('href') !== '#') {
            sessionStorage.removeItem('laporan_keuangan_loaded');
        }
    });
</script>
@endsection