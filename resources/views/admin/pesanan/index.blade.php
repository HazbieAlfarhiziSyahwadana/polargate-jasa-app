@extends('layouts.admin')

@section('title', 'Kelola Pesanan')

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

    .input-field, select {
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .input-field:focus, select:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .btn-primary, .btn-secondary {
        transition: all 0.2s ease;
    }

    .btn-primary:hover, .btn-secondary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.12);
    }

    .btn-primary:active, .btn-secondary:active {
        transform: translateY(0);
    }

    table tbody tr {
        transition: background-color 0.2s ease;
    }

    table tbody tr:hover {
        background-color: #f9fafb;
    }

    .search-input {
        transition: all 0.3s ease;
    }

    .search-input:focus {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }

    .form-label {
        @apply flex items-center gap-2 text-gray-700 text-sm font-semibold mb-2;
    }

    @media (max-width: 768px) {
        .overflow-x-auto {
            -webkit-overflow-scrolling: touch;
        }
    }
</style>

<div class="animate-fade">
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Kelola Pesanan</h1>
        <p class="text-sm text-gray-600">Manajemen pesanan client</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6 animate-slide">
        <div class="card bg-gradient-to-br from-blue-500 to-blue-600 text-white stats-card cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Total Pesanan</p>
                    <p class="text-3xl font-bold">{{ $pesanan->total() }}</p>
                </div>
                <div class="bg-white/20 p-4 rounded-xl backdrop-blur-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="card bg-gradient-to-br from-yellow-500 to-yellow-600 text-white stats-card cursor-pointer animate-slide delay-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium mb-1">Menunggu Proses</p>
                    <p class="text-3xl font-bold">{{ $pesanan->whereIn('status', ['Menunggu Konfirmasi', 'Menunggu Pembayaran DP', 'DP Dibayar - Menunggu Verifikasi'])->count() }}</p>
                </div>
                <div class="bg-white/20 p-4 rounded-xl backdrop-blur-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="card bg-gradient-to-br from-purple-500 to-purple-600 text-white stats-card cursor-pointer animate-slide delay-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium mb-1">Sedang Dikerjakan</p>
                    <p class="text-3xl font-bold">{{ $pesanan->whereIn('status', ['Sedang Diproses', 'Preview Siap', 'Revisi Diminta'])->count() }}</p>
                </div>
                <div class="bg-white/20 p-4 rounded-xl backdrop-blur-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="card bg-gradient-to-br from-green-500 to-green-600 text-white stats-card cursor-pointer animate-slide delay-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">Selesai</p>
                    <p class="text-3xl font-bold">{{ $pesanan->where('status', 'Selesai')->count() }}</p>
                </div>
                <div class="bg-white/20 p-4 rounded-xl backdrop-blur-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="card mb-6 animate-slide delay-100">
        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Search Bar -->
                <div class="lg:col-span-2">
                    <label for="realTimeSearch" class="form-label">
                        <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Cari Pesanan Real-time
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
                               placeholder="Ketik kode pesanan, nama client, email, atau layanan...">
                    </div>
                </div>
                
                <!-- Filter Status -->
                <div>
                    <label class="form-label">
                        <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Filter Status
                    </label>
                    
                    <!-- Desktop: Native Select -->
                    <select id="statusFilter" class="input-field w-full hidden md:block">
                        <option value="">Semua Status</option>
                        <option value="Menunggu Konfirmasi">Menunggu Konfirmasi</option>
                        <option value="Menunggu Pembayaran DP">Menunggu Pembayaran DP</option>
                        <option value="DP Dibayar - Menunggu Verifikasi">DP Dibayar - Menunggu Verifikasi</option>
                        <option value="Sedang Diproses">Sedang Diproses</option>
                        <option value="Preview Siap">Preview Siap</option>
                        <option value="Revisi Diminta">Revisi Diminta</option>
                        <option value="Menunggu Pelunasan">Menunggu Pelunasan</option>
                        <option value="Pelunasan Dibayar - Menunggu Verifikasi">Pelunasan Dibayar - Menunggu Verifikasi</option>
                        <option value="Selesai">Selesai</option>
                        <option value="Dibatalkan">Dibatalkan</option>
                    </select>
                    
                    <!-- Mobile: Custom Dropdown -->
                    <div class="relative md:hidden" x-data="{ open: false, selected: '' }">
                        <input type="hidden" id="statusFilterMobile" :value="selected">
                        
                        <button type="button" @click="open = !open" 
                                class="w-full input-field text-left flex items-center justify-between"
                                :class="{ 'border-primary-500': open }">
                            <span x-show="!selected" class="text-gray-400">Semua Status</span>
                            <span x-show="selected == 'Menunggu Konfirmasi'" class="text-gray-900">Menunggu Konfirmasi</span>
                            <span x-show="selected == 'Menunggu Pembayaran DP'" class="text-gray-900">Menunggu Pembayaran DP</span>
                            <span x-show="selected == 'DP Dibayar - Menunggu Verifikasi'" class="text-gray-900">DP Dibayar - Menunggu Verifikasi</span>
                            <span x-show="selected == 'Sedang Diproses'" class="text-gray-900">Sedang Diproses</span>
                            <span x-show="selected == 'Preview Siap'" class="text-gray-900">Preview Siap</span>
                            <span x-show="selected == 'Revisi Diminta'" class="text-gray-900">Revisi Diminta</span>
                            <span x-show="selected == 'Menunggu Pelunasan'" class="text-gray-900">Menunggu Pelunasan</span>
                            <span x-show="selected == 'Pelunasan Dibayar - Menunggu Verifikasi'" class="text-gray-900">Pelunasan Dibayar - Menunggu Verifikasi</span>
                            <span x-show="selected == 'Selesai'" class="text-gray-900">Selesai</span>
                            <span x-show="selected == 'Dibatalkan'" class="text-gray-900">Dibatalkan</span>
                            
                            <svg class="w-5 h-5 text-gray-400 transition-transform flex-shrink-0" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <div x-show="open" 
                             @click.away="open = false" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute z-50 w-full mt-1 bg-white rounded-lg shadow-lg border border-gray-200 max-h-60 overflow-y-auto"
                             style="display: none;">
                            
                            <button type="button" 
                                    @click="selected = ''; open = false"
                                    :class="{ 'bg-primary-50 border-l-4 border-primary-500': selected == '' }"
                                    class="w-full px-4 py-3 text-left hover:bg-gray-50 transition-colors border-b border-gray-100">
                                <span class="font-medium text-gray-900 text-sm">Semua Status</span>
                            </button>
                            
                            <button type="button" 
                                    @click="selected = 'Menunggu Konfirmasi'; open = false"
                                    :class="{ 'bg-primary-50 border-l-4 border-primary-500': selected == 'Menunggu Konfirmasi' }"
                                    class="w-full px-4 py-3 text-left hover:bg-gray-50 transition-colors border-b border-gray-100">
                                <span class="font-medium text-gray-900 text-sm">Menunggu Konfirmasi</span>
                            </button>
                            
                            <button type="button" 
                                    @click="selected = 'Menunggu Pembayaran DP'; open = false"
                                    :class="{ 'bg-primary-50 border-l-4 border-primary-500': selected == 'Menunggu Pembayaran DP' }"
                                    class="w-full px-4 py-3 text-left hover:bg-gray-50 transition-colors border-b border-gray-100">
                                <span class="font-medium text-gray-900 text-sm">Menunggu Pembayaran DP</span>
                            </button>
                            
                            <button type="button" 
                                    @click="selected = 'DP Dibayar - Menunggu Verifikasi'; open = false"
                                    :class="{ 'bg-primary-50 border-l-4 border-primary-500': selected == 'DP Dibayar - Menunggu Verifikasi' }"
                                    class="w-full px-4 py-3 text-left hover:bg-gray-50 transition-colors border-b border-gray-100">
                                <span class="font-medium text-gray-900 text-sm">DP Dibayar - Menunggu Verifikasi</span>
                            </button>
                            
                            <button type="button" 
                                    @click="selected = 'Sedang Diproses'; open = false"
                                    :class="{ 'bg-primary-50 border-l-4 border-primary-500': selected == 'Sedang Diproses' }"
                                    class="w-full px-4 py-3 text-left hover:bg-gray-50 transition-colors border-b border-gray-100">
                                <span class="font-medium text-gray-900 text-sm">Sedang Diproses</span>
                            </button>
                            
                            <button type="button" 
                                    @click="selected = 'Preview Siap'; open = false"
                                    :class="{ 'bg-primary-50 border-l-4 border-primary-500': selected == 'Preview Siap' }"
                                    class="w-full px-4 py-3 text-left hover:bg-gray-50 transition-colors border-b border-gray-100">
                                <span class="font-medium text-gray-900 text-sm">Preview Siap</span>
                            </button>
                            
                            <button type="button" 
                                    @click="selected = 'Revisi Diminta'; open = false"
                                    :class="{ 'bg-primary-50 border-l-4 border-primary-500': selected == 'Revisi Diminta' }"
                                    class="w-full px-4 py-3 text-left hover:bg-gray-50 transition-colors border-b border-gray-100">
                                <span class="font-medium text-gray-900 text-sm">Revisi Diminta</span>
                            </button>
                            
                            <button type="button" 
                                    @click="selected = 'Menunggu Pelunasan'; open = false"
                                    :class="{ 'bg-primary-50 border-l-4 border-primary-500': selected == 'Menunggu Pelunasan' }"
                                    class="w-full px-4 py-3 text-left hover:bg-gray-50 transition-colors border-b border-gray-100">
                                <span class="font-medium text-gray-900 text-sm">Menunggu Pelunasan</span>
                            </button>
                            
                            <button type="button" 
                                    @click="selected = 'Pelunasan Dibayar - Menunggu Verifikasi'; open = false"
                                    :class="{ 'bg-primary-50 border-l-4 border-primary-500': selected == 'Pelunasan Dibayar - Menunggu Verifikasi' }"
                                    class="w-full px-4 py-3 text-left hover:bg-gray-50 transition-colors border-b border-gray-100">
                                <span class="font-medium text-gray-900 text-sm">Pelunasan Dibayar - Menunggu Verifikasi</span>
                            </button>
                            
                            <button type="button" 
                                    @click="selected = 'Selesai'; open = false"
                                    :class="{ 'bg-primary-50 border-l-4 border-primary-500': selected == 'Selesai' }"
                                    class="w-full px-4 py-3 text-left hover:bg-gray-50 transition-colors border-b border-gray-100">
                                <span class="font-medium text-gray-900 text-sm">Selesai</span>
                            </button>
                            
                            <button type="button" 
                                    @click="selected = 'Dibatalkan'; open = false"
                                    :class="{ 'bg-primary-50 border-l-4 border-primary-500': selected == 'Dibatalkan' }"
                                    class="w-full px-4 py-3 text-left hover:bg-gray-50 transition-colors border-b border-gray-100 last:border-b-0">
                                <span class="font-medium text-gray-900 text-sm">Dibatalkan</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2">
                <button id="resetFilter" class="btn-secondary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Reset Filter
                </button>
            </div>

            <!-- Search Results Info -->
            <div id="searchInfo" class="text-sm text-gray-600 hidden">
                Menampilkan <span id="resultCount" class="font-semibold text-primary-600"></span> hasil dari <span id="totalCount" class="font-semibold">{{ $pesanan->count() }}</span> pesanan
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card animate-slide delay-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-4 lg:px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Kode Pesanan</th>
                        <th class="px-4 lg:px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Client</th>
                        <th class="px-4 lg:px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider hidden md:table-cell">Layanan</th>
                        <th class="px-4 lg:px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Total Harga</th>
                        <th class="px-4 lg:px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-4 lg:px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider hidden lg:table-cell">Tanggal</th>
                        <th class="px-4 lg:px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="pesananTableBody">
                    @forelse($pesanan as $item)
                    <tr class="pesanan-row"
                        data-kode="{{ strtolower($item->kode_pesanan) }}"
                        data-client="{{ strtolower($item->client->name) }}"
                        data-email="{{ strtolower($item->client->email) }}"
                        data-layanan="{{ strtolower($item->layanan->nama_layanan) }}"
                        data-paket="{{ $item->paket ? strtolower($item->paket->nama_paket) : '' }}"
                        data-status="{{ $item->status }}">
                        <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                            <div class="flex items-start gap-3">
                                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-2 flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900 text-sm">{{ $item->kode_pesanan }}</div>
                                    <div class="text-xs text-gray-500 lg:hidden">{{ $item->created_at->format('d/m/Y') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 lg:px-6 py-4">
                            <div class="text-sm text-gray-900 font-medium">{{ $item->client->name }}</div>
                            <div class="text-xs text-gray-500 truncate max-w-xs">{{ $item->client->email }}</div>
                        </td>
                        <td class="px-4 lg:px-6 py-4 hidden md:table-cell">
                            <div class="text-sm text-gray-900">{{ $item->layanan->nama_layanan }}</div>
                            <div class="text-xs text-gray-500">{{ $item->paket->nama_paket ?? '-' }}</div>
                        </td>
                        <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-1 text-sm font-semibold text-gray-900">
                                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                            </div>
                        </td>
                        <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                            @php
                                $statusClass = 'badge-info';
                                if(in_array($item->status, ['Selesai'])) $statusClass = 'badge-success';
                                if(in_array($item->status, ['Menunggu Pembayaran DP', 'Menunggu Pelunasan', 'Menunggu Konfirmasi'])) $statusClass = 'badge-warning';
                                if(in_array($item->status, ['Dibatalkan'])) $statusClass = 'badge-danger';
                            @endphp
                            <span class="{{ $statusClass }} text-xs whitespace-normal inline-flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full {{ $statusClass == 'badge-success' ? 'bg-green-400' : ($statusClass == 'badge-warning' ? 'bg-yellow-400' : ($statusClass == 'badge-danger' ? 'bg-red-400' : 'bg-blue-400')) }}"></span>
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden lg:table-cell">
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $item->created_at->format('d/m/Y') }}
                            </div>
                        </td>
                        <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.pesanan.show', $item) }}" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-900 hover:bg-blue-50 px-3 py-1.5 rounded-lg transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr id="emptyRow">
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center gap-4">
                                <div class="bg-gray-100 rounded-full p-6">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-gray-500 font-medium">Tidak ada pesanan</p>
                                    <p class="text-gray-400 text-sm mt-1">Belum ada pesanan yang masuk</p>
                                </div>
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
        @if($pesanan->hasPages())
        <div class="px-4 lg:px-6 py-4 border-t border-gray-200">
            {{ $pesanan->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // First load animation
        const isFirstLoad = !sessionStorage.getItem('pesanan_loaded');
        
        if (isFirstLoad) {
            document.body.classList.add('page-load');
            sessionStorage.setItem('pesanan_loaded', 'true');
            
            setTimeout(() => {
                document.body.classList.remove('page-load');
            }, 1000);
        }

        // Real-time Search and Filter
        const realTimeSearch = document.getElementById('realTimeSearch');
        const statusFilter = document.getElementById('statusFilter');
        const statusFilterMobile = document.getElementById('statusFilterMobile');
        const resetButton = document.getElementById('resetFilter');
        const pesananRows = document.querySelectorAll('.pesanan-row');
        const searchInfo = document.getElementById('searchInfo');
        const resultCount = document.getElementById('resultCount');
        const noResults = document.getElementById('noResults');
        const emptyRow = document.getElementById('emptyRow');

        function filterPesanan() {
            const searchTerm = realTimeSearch.value.toLowerCase();
            const statusValue = statusFilter ? statusFilter.value : (statusFilterMobile ? statusFilterMobile.value : '');
            let visibleCount = 0;

            pesananRows.forEach(row => {
                const kode = row.dataset.kode;
                const client = row.dataset.client;
                const email = row.dataset.email;
                const layanan = row.dataset.layanan;
                const paket = row.dataset.paket;
                const status = row.dataset.status;

                const matchSearch = kode.includes(searchTerm) || 
                                  client.includes(searchTerm) || 
                                  email.includes(searchTerm) ||
                                  layanan.includes(searchTerm) ||
                                  paket.includes(searchTerm);
                const matchStatus = statusValue === '' || status === statusValue;

                if (matchSearch && matchStatus) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
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
            if (visibleCount === 0 && pesananRows.length > 0) {
                noResults.classList.remove('hidden');
                if (emptyRow) emptyRow.style.display = 'none';
            } else {
                noResults.classList.add('hidden');
            }
        }

        // Event listeners
        realTimeSearch.addEventListener('input', filterPesanan);
        
        // Desktop select
        if (statusFilter) {
            statusFilter.addEventListener('change', filterPesanan);
        }
        
        // Mobile custom dropdown
        if (statusFilterMobile) {
            const observer = new MutationObserver(filterPesanan);
            observer.observe(statusFilterMobile, { attributes: true, attributeFilter: ['value'] });
        }
        
        resetButton.addEventListener('click', () => {
            realTimeSearch.value = '';
            
            // Reset desktop select
            if (statusFilter) {
                statusFilter.value = '';
            }
            
            // Reset mobile custom dropdown
            if (statusFilterMobile) {
                statusFilterMobile.value = '';
                const alpineComponent = statusFilterMobile.closest('[x-data]');
                if (alpineComponent && alpineComponent.__x) {
                    alpineComponent.__x.$data.selected = '';
                }
            }
            
            filterPesanan();
        });
    });

    // Reset flag ketika pindah ke halaman lain
    window.addEventListener('beforeunload', function(e) {
        if (e.target.activeElement.tagName === 'A' && 
            e.target.activeElement.getAttribute('href') !== '#') {
            sessionStorage.removeItem('pesanan_loaded');
        }
    });
</script>
@endpush
@endsection