@extends('layouts.client')

@section('title', 'Revisi Saya')

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

    .revisi-card:hover {
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
        transform: translateY(-4px);
    }

    .revisi-card.hidden-filter {
        display: none;
    }

    .btn-primary, .btn-success, .btn-secondary, .btn-warning {
        transition: all 0.2s ease;
    }

    .btn-primary:hover, .btn-success:hover, .btn-secondary:hover, .btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
    }

    .btn-warning {
        background-color: #f59e0b;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-warning:hover {
        background-color: #d97706;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(245, 158, 11, 0.3);
    }

    .search-input, .filter-input {
        transition: all 0.3s ease;
    }

    .search-input:focus, .filter-input:focus {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }

    /* Badge untuk file hasil */
    .file-hasil-badge {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.9; transform: scale(1.05); }
    }
</style>

<div class="mb-6 animate-fade">
    <h1 class="text-3xl font-bold text-gray-800">Revisi Saya</h1>
    <p class="text-gray-600">Kelola dan pantau permintaan revisi Anda</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 animate-slide delay-100">
    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-yellow-100 text-sm">Menunggu</p>
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
                <p class="text-blue-100 text-sm">Diproses</p>
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
</div>

<!-- Search and Filter -->
<div class="card mb-6 animate-slide delay-200">
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
                       placeholder="Cari berdasarkan kode pesanan atau catatan...">
            </div>
        </div>

        <!-- Filter Status -->
        <div class="md:w-64">
            <select id="statusFilter" 
                    class="filter-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition">
                <option value="">Semua Status</option>
                <option value="diminta">Diminta</option>
                <option value="sedang dikerjakan">Sedang Dikerjakan</option>
                <option value="selesai">Selesai</option>
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

<!-- Revisi List -->
@if($revisi->count() > 0)
<div class="space-y-4" id="revisiContainer">
    @foreach($revisi as $index => $item)
    <div class="card revisi-card animate-slide delay-{{ min($index * 100 + 300, 500) }}"
         data-kode="{{ strtolower($item->pesanan->kode_pesanan) }}"
         data-status="{{ strtolower($item->status) }}"
         data-catatan="{{ strtolower($item->catatan_revisi) }}">
        @php
            $statusClass = 'badge-warning';
            if($item->status == 'Selesai') $statusClass = 'badge-success';
            if($item->status == 'Sedang Dikerjakan') $statusClass = 'badge-info';
        @endphp

        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 pb-4 border-b border-gray-200">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <h3 class="text-lg font-bold text-gray-800">Revisi #{{ $item->revisi_ke }} - {{ $item->pesanan->kode_pesanan }}</h3>
                    
                    {{-- ✅ BADGE FILE HASIL --}}
                    @if($item->status == 'Selesai' && $item->hasFileHasil())
                    <span class="file-hasil-badge">
                        {{ $item->file_hasil_count }} File Hasil
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

        {{-- ✅ CATATAN ADMIN (jika ada) --}}
        @if($item->status == 'Selesai' && $item->catatan_admin)
        <div class="bg-green-50 p-4 rounded-lg mb-4 border-l-4 border-green-500">
            <p class="text-xs text-green-700 mb-2 font-medium flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Catatan dari Admin:
            </p>
            <p class="text-sm text-green-800">{{ $item->catatan_admin }}</p>
        </div>
        @endif

        <div class="flex flex-wrap gap-2 pt-4 border-t border-gray-200">
            <a href="{{ route('client.revisi.show', $item) }}" class="btn-primary text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Lihat Detail
            </a>

            <a href="{{ route('client.pesanan.show', $item->pesanan) }}" class="btn-secondary text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Lihat Pesanan
            </a>

            {{-- ✅ BUTTON DOWNLOAD FILE HASIL --}}
            @if($item->status == 'Selesai' && $item->hasFileHasil())
            <button onclick="showFileHasilModal({{ json_encode($item->id) }})" class="btn-success text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Download File Hasil ({{ $item->file_hasil_count }})
            </button>
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
            <p class="text-sm text-gray-500 mt-1">Permintaan revisi Anda akan muncul di sini</p>
        </div>
        <a href="{{ route('client.pesanan.index') }}" class="btn-primary mt-4 inline-flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Lihat Pesanan
        </a>
    </div>
</div>
@endif

{{-- ✅ MODAL UNTUK DOWNLOAD FILE HASIL --}}
<div id="fileHasilModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-2xl w-full max-h-[80vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-800">File Hasil Revisi</h3>
                <button onclick="closeFileHasilModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        <div id="fileHasilContent" class="p-6">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isFirstLoad = !sessionStorage.getItem('revisi_index_loaded');
        
        if (isFirstLoad) {
            document.body.classList.add('page-load');
            sessionStorage.setItem('revisi_index_loaded', 'true');
            
            setTimeout(() => {
                document.body.classList.remove('page-load');
            }, 1000);
        }

        // Search and Filter Functionality
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const resetButton = document.getElementById('resetFilter');
        const revisiCards = document.querySelectorAll('.revisi-card');
        const searchInfo = document.getElementById('searchInfo');
        const resultCount = document.getElementById('resultCount');
        const noResults = document.getElementById('noResults');
        const revisiContainer = document.getElementById('revisiContainer');

        function filterRevisi() {
            const searchTerm = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value.toLowerCase();
            let visibleCount = 0;

            revisiCards.forEach(card => {
                const kode = card.dataset.kode;
                const status = card.dataset.status;
                const catatan = card.dataset.catatan;

                const matchSearch = kode.includes(searchTerm) || 
                                  catatan.includes(searchTerm) ||
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
            if (visibleCount === 0 && revisiCards.length > 0) {
                noResults.classList.remove('hidden');
                if (revisiContainer) revisiContainer.style.display = 'none';
            } else {
                noResults.classList.add('hidden');
                if (revisiContainer) revisiContainer.style.display = 'block';
            }
        }

        // Event listeners
        if (searchInput) searchInput.addEventListener('input', filterRevisi);
        if (statusFilter) statusFilter.addEventListener('change', filterRevisi);
        
        if (resetButton) {
            resetButton.addEventListener('click', () => {
                searchInput.value = '';
                statusFilter.value = '';
                filterRevisi();
            });
        }
    });

    // ✅ FUNCTION UNTUK MODAL FILE HASIL
    function showFileHasilModal(revisiId) {
        const modal = document.getElementById('fileHasilModal');
        const content = document.getElementById('fileHasilContent');
        
        // Show loading
        content.innerHTML = '<div class="text-center py-8"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600 mx-auto"></div></div>';
        modal.classList.remove('hidden');
        
        // Fetch file data
        fetch(`/client/revisi/${revisiId}/file-hasil`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let html = '<div class="space-y-3">';
                    data.files.forEach((file, index) => {
                        html += `
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200 hover:border-primary-300 transition">
                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                    ${getFileIcon(file.extension)}
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-900 text-sm truncate">${file.name}</p>
                                        <p class="text-xs text-gray-500 uppercase">${file.extension} • ${file.size}</p>
                                    </div>
                                </div>
                                <a href="/client/revisi/${revisiId}/download-hasil/${index}" 
                                   class="text-primary-600 hover:text-primary-700 p-2 hover:bg-primary-50 rounded-lg transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                </a>
                            </div>
                        `;
                    });
                    html += '</div>';
                    content.innerHTML = html;
                } else {
                    content.innerHTML = '<p class="text-center text-red-500">Gagal memuat file</p>';
                }
            })
            .catch(error => {
                content.innerHTML = '<p class="text-center text-red-500">Terjadi kesalahan</p>';
            });
    }

    function closeFileHasilModal() {
        document.getElementById('fileHasilModal').classList.add('hidden');
    }

    function getFileIcon(extension) {
        const imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        const pdfExts = ['pdf'];
        
        if (imageExts.includes(extension.toLowerCase())) {
            return `<svg class="w-8 h-8 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>`;
        } else if (pdfExts.includes(extension.toLowerCase())) {
            return `<svg class="w-8 h-8 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>`;
        } else {
            return `<svg class="w-8 h-8 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>`;
        }
    }

    // Close modal on outside click
    document.getElementById('fileHasilModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeFileHasilModal();
        }
    });

    window.addEventListener('beforeunload', function(e) {
        if (e.target.activeElement.tagName === 'A' && 
            e.target.activeElement.getAttribute('href') !== '#') {
            sessionStorage.removeItem('revisi_index_loaded');
        }
    });
</script>
@endsection