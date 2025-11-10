@extends('layouts.admin')

@section('title', 'Kelola Revisi')

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
        transition: box-shadow 0.3s ease, transform 0.3s ease;
    }

    .revisi-card:hover {
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
        transform: translateY(-4px);
    }

    /* Badge Notifikasi */
    .notification-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 8px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
        animation: pulse 2s infinite;
        min-width: 24px;
        text-align: center;
        z-index: 10;
    }

    .notification-icon {
        animation: ringBell 3s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
            transform: scale(1);
        }
        50% {
            opacity: 0.8;
            transform: scale(1.05);
        }
    }

    @keyframes ringBell {
        0%, 100% { transform: rotate(0deg); }
        10%, 30%, 50%, 70%, 90% { transform: rotate(-10deg); }
        20%, 40%, 60%, 80% { transform: rotate(10deg); }
    }

    .revisi-card.hidden-filter {
        display: none;
    }

    .btn-primary, .btn-success, .btn-info, .btn-danger {
        transition: all 0.2s ease;
    }

    .btn-primary:hover, .btn-success:hover, .btn-info:hover {
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
    <h1 class="text-3xl font-bold text-gray-800">Kelola Revisi</h1>
    <p class="text-gray-600">Pantau dan kelola permintaan revisi dari pelanggan</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 animate-slide delay-100">
    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-yellow-100 text-sm">Diminta</p>
                <p class="text-3xl font-bold mt-1">{{ $revisi->where('status', 'Diminta')->count() }}</p>
            </div>
            <div class="bg-yellow-400 bg-opacity-30 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm">Sedang Dikerjakan</p>
                <p class="text-3xl font-bold mt-1">{{ $revisi->where('status', 'Sedang Dikerjakan')->count() }}</p>
            </div>
            <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm">Selesai</p>
                <p class="text-3xl font-bold mt-1">{{ $revisi->where('status', 'Selesai')->count() }}</p>
            </div>
            <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-6 text-white relative">
        @php
            $totalRevisi = $revisi->total();
        @endphp
        
        {{-- Badge Notifikasi --}}
        @if($totalRevisi > 0)
        <div class="notification-badge">
            {{ $totalRevisi }}
        </div>
        @endif
        
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm">Total Revisi</p>
                <p class="text-3xl font-bold mt-1">{{ $totalRevisi }}</p>
            </div>
            <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
                <svg class="w-8 h-8 {{ $totalRevisi > 0 ? 'notification-icon' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter -->
<div class="card mb-6 animate-slide delay-200">
    <form method="GET" action="{{ route('admin.revisi.index') }}">
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
                           name="search"
                           value="{{ request('search') }}"
                           class="search-input w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition"
                           placeholder="Cari berdasarkan kode pesanan atau pelanggan...">
                </div>
            </div>

            <!-- Filter Status -->
            <div class="md:w-64">
                <select name="status" 
                        class="filter-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition">
                    <option value="">Semua Status</option>
                    <option value="Diminta" {{ request('status') == 'Diminta' ? 'selected' : '' }}>Diminta</option>
                    <option value="Sedang Dikerjakan" {{ request('status') == 'Sedang Dikerjakan' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                    <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex gap-2">
                <button type="submit" class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-all duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filter
                </button>
                <a href="{{ route('admin.revisi.index') }}" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-all duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Reset
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Revisi List -->
@if($revisi->count() > 0)
<div class="space-y-4" id="revisiContainer">
    @foreach($revisi as $index => $item)
    <div class="card revisi-card animate-slide delay-{{ min($index * 100 + 300, 400) }}">
        @php
            $statusClass = 'badge-warning';
            if($item->status == 'Selesai') $statusClass = 'badge-success';
            if($item->status == 'Sedang Dikerjakan') $statusClass = 'badge-info';
            
            // Get client name - handle both user_id and client_id scenarios
            $clientName = 'Client';
            if ($item->pesanan) {
                if (isset($item->pesanan->client) && $item->pesanan->client) {
                    $clientName = $item->pesanan->client->name;
                } elseif (isset($item->pesanan->user) && $item->pesanan->user) {
                    $clientName = $item->pesanan->user->name;
                }
            }
        @endphp

        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 pb-4 border-b border-gray-200">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <h3 class="text-lg font-bold text-gray-800">Revisi #{{ $item->revisi_ke }} - {{ $item->pesanan->kode_pesanan }}</h3>
                </div>
                <p class="text-sm text-gray-600 flex items-center gap-2 ml-8">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    {{ $clientName }} • {{ $item->created_at->format('d M Y H:i') }}
                </p>
            </div>
            <span class="{{ $statusClass }}">{{ $item->status }}</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-xs text-gray-500 mb-1 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Layanan
                </p>
                <p class="font-semibold text-gray-900">{{ $item->pesanan->layanan->nama_layanan }}</p>
                <p class="text-sm text-gray-600">{{ $item->pesanan->paket->nama_paket ?? '-' }}</p>
            </div>

            <div class="bg-blue-50 p-4 rounded-lg">
                <p class="text-xs text-gray-500 mb-1 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    File Referensi
                </p>
                <p class="font-semibold text-gray-900 text-xl">{{ $item->file_count }} File</p>
            </div>

            <div class="bg-purple-50 p-4 rounded-lg">
                <p class="text-xs text-gray-500 mb-1 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Tanggal Selesai
                </p>
                <p class="font-semibold text-gray-900">{{ $item->formatted_tanggal_selesai }}</p>
            </div>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg mb-4">
            <p class="text-xs text-gray-500 mb-2 font-medium">Catatan Revisi:</p>
            <p class="text-sm text-gray-700">{{ Str::limit($item->catatan_revisi, 150) }}</p>
        </div>

        <div class="flex flex-wrap gap-2 pt-4 border-t border-gray-200">
            <a href="{{ route('admin.revisi.show', $item) }}" class="btn-primary text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Lihat Detail
            </a>

            @if($item->status != 'Selesai')
            <button onclick="updateStatus({{ $item->id }}, 'Sedang Dikerjakan')" class="btn-info text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Kerjakan
            </button>

            <button onclick="updateStatus({{ $item->id }}, 'Selesai')" class="btn-success text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Selesai
            </button>
            @endif
        </div>
    </div>
    @endforeach
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $revisi->links() }}
</div>

@else
<div class="card text-center py-12 animate-slide delay-300">
    <div class="flex flex-col items-center justify-center gap-4">
        <div class="bg-gray-100 rounded-full p-6">
            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-medium text-gray-900">Belum Ada Revisi</h3>
            <p class="text-sm text-gray-500 mt-1">Permintaan revisi dari pelanggan akan muncul di sini</p>
        </div>
    </div>
</div>
@endif

<!-- Form untuk update status (hidden) -->
<form id="updateStatusForm" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
    <input type="hidden" name="status" id="statusInput">
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isFirstLoad = !sessionStorage.getItem('revisi_admin_loaded');
        
        if (isFirstLoad) {
            document.body.classList.add('page-load');
            sessionStorage.setItem('revisi_admin_loaded', 'true');
            
            setTimeout(() => {
                document.body.classList.remove('page-load');
            }, 1000);
        }
    });

    function updateStatus(revisiId, status) {
        if (confirm('Apakah Anda yakin ingin mengubah status revisi ini?')) {
            const form = document.getElementById('updateStatusForm');
            form.action = `/admin/revisi/${revisiId}/update-status`;
            document.getElementById('statusInput').value = status;
            form.submit();
        }
    }

    window.addEventListener('beforeunload', function(e) {
        if (e.target.activeElement.tagName === 'A') {
            sessionStorage.removeItem('revisi_admin_loaded');
        }
    });
</script>
@endsection