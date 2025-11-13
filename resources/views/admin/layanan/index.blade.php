@extends('layouts.admin')

@section('title', 'Kelola Layanan')

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

    .btn-primary {
        transition: all 0.2s ease;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
    }

    table tbody tr {
        transition: background-color 0.2s ease;
    }

    table tbody tr:hover {
        background-color: #f9fafb;
    }

    .image-hover {
        transition: transform 0.3s ease;
        cursor: pointer;
    }

    .image-hover:hover {
        transform: scale(1.1);
    }

    .search-input {
        transition: all 0.3s ease;
    }

    .search-input:focus {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }

    /* Enhanced Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
        backdrop-filter: blur(10px);
    }

    .modal-content {
        position: relative;
        margin: 2% auto;
        width: 95%;
        max-width: 1200px;
        background: transparent;
        border-radius: 12px;
        overflow: hidden;
        animation: modalFadeIn 0.3s ease-out;
    }

    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(-20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .modal-header {
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        z-index: 10;
        background: linear-gradient(to bottom, rgba(0,0,0,0.7), transparent);
    }

    .modal-body {
        padding: 0;
    }

    .close-modal {
        background: rgba(0, 0, 0, 0.7);
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .close-modal:hover {
        background: rgba(0, 0, 0, 0.9);
        transform: scale(1.1);
    }

    .media-preview {
        width: 100%;
        height: 70vh;
        object-fit: contain;
        background: #000;
    }

    .video-container {
        position: relative;
        padding-bottom: 56.25%; /* 16:9 aspect ratio */
        height: 0;
        overflow: hidden;
        background: #000;
    }

    .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
    }

    /* Enhanced Media Display in Table */
    .media-display {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        overflow: hidden;
        position: relative;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 3px solid transparent;
    }

    .media-display:hover {
        transform: scale(1.05);
        border-color: #3b82f6;
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
    }

    .media-display img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .video-thumbnail {
        background: linear-gradient(135deg, #dc2626, #ef4444);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .play-icon {
        color: white;
        width: 24px;
        height: 24px;
    }

    .media-badge {
        position: absolute;
        top: 6px;
        right: 6px;
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 0.65rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .no-media {
        background: linear-gradient(135deg, #6b7280, #9ca3af);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .no-media svg {
        color: #d1d5db;
    }

    /* Media Info Overlay */
    .media-info-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        padding: 1rem 0.75rem 0.5rem;
        color: white;
        transform: translateY(100%);
        transition: transform 0.3s ease;
    }

    .media-display:hover .media-info-overlay {
        transform: translateY(0);
    }

    .media-title {
        font-size: 0.7rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .media-type {
        font-size: 0.6rem;
        opacity: 0.8;
    }
</style>

<!-- Modal for Media Preview -->
<div id="mediaModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="text-lg font-semibold text-white" id="modalTitle">Preview Media</h3>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <div id="mediaContent">
                <!-- Content will be inserted here -->
            </div>
        </div>
    </div>
</div>

<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 animate-fade">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Kelola Layanan</h1>
        <p class="text-gray-600">Manajemen layanan yang ditawarkan</p>
    </div>
    <a href="{{ route('admin.layanan.create') }}" class="btn-primary">
        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
        Tambah Layanan
    </a>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-6">
    <div class="card bg-gradient-to-br from-blue-500 to-blue-600 text-white stats-card animate-slide cursor-pointer">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm">Total Layanan</p>
                <p class="text-3xl font-bold mt-2">{{ $layanan->count() }}</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="card bg-gradient-to-br from-green-500 to-green-600 text-white stats-card animate-slide delay-100 cursor-pointer">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm">Layanan Aktif</p>
                <p class="text-3xl font-bold mt-2">{{ $layanan->where('is_active', true)->count() }}</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="card bg-gradient-to-br from-purple-500 to-purple-600 text-white stats-card animate-slide delay-200 cursor-pointer sm:col-span-2 lg:col-span-1">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm">Total Pesanan</p>
                <p class="text-3xl font-bold mt-2">{{ $layanan->sum('pesanan_count') }}</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter Section -->
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
                       placeholder="Cari layanan berdasarkan nama, kategori, atau harga...">
            </div>
        </div>

        <!-- Filter Kategori -->
        <div class="md:w-48">
            <select id="kategoriFilter" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition">
                <option value="">Semua Kategori</option>
                <option value="multimedia">Multimedia</option>
                <option value="it">IT</option>
            </select>
        </div>

        <!-- Filter Status -->
        <div class="md:w-48">
            <select id="statusFilter" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition">
                <option value="">Semua Status</option>
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
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

<!-- Table -->
<div class="card animate-slide delay-300">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Media</th>
                    <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Layanan</th>
                    <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Mulai</th>
                    <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pesanan</th>
                    <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="layananTableBody">
                @forelse($layanan as $item)
                <tr class="layanan-row"
                    data-nama="{{ strtolower($item->nama_layanan) }}"
                    data-kategori="{{ strtolower($item->kategori) }}"
                    data-harga="{{ $item->harga_mulai }}"
                    data-status="{{ $item->is_active ? 'aktif' : 'nonaktif' }}">
                    <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                        <div class="media-display 
                            @if($item->video_url) video-thumbnail 
                            @elseif($item->gambar_url) 
                            @else no-media @endif"
                            onclick="openMediaModal('{{ $item->nama_layanan }}', 
                            '{{ $item->video_url ? 'video' : 'image' }}', 
                            '{{ $item->video_url ? ($item->youtube_embed_url ?? $item->video_url) : $item->gambar_url }}')">
                            
                            @if($item->video_url)
                                <svg class="play-icon" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                                <div class="media-badge">
                                    <i class="fas fa-video text-xs"></i>
                                </div>
                            @elseif($item->gambar_url)
                                <img src="{{ $item->gambar_url }}" alt="{{ $item->nama_layanan }}">
                                <div class="media-badge">
                                    <i class="fas fa-image text-xs"></i>
                                </div>
                            @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            @endif

                            <div class="media-info-overlay">
                                <div class="media-title">{{ Str::limit($item->nama_layanan, 15) }}</div>
                                <div class="media-type">
                                    @if($item->video_url)
                                        Video YouTube
                                    @elseif($item->gambar_url)
                                        Gambar
                                    @else
                                        Tidak ada media
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 md:px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $item->nama_layanan }}</div>
                        <div class="text-sm text-gray-500">{{ Str::limit($item->deskripsi, 50) }}</div>
                    </td>
                    <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                        <span class="badge-info">{{ $item->kategori }}</span>
                    </td>
                    <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                        Rp {{ number_format($item->harga_mulai, 0, ',', '.') }}
                    </td>
                    <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <span class="inline-flex items-center">
                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            {{ $item->pesanan_count }} pesanan
                        </span>
                    </td>
                    <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                        @if($item->is_active)
                        <span class="badge-success">Aktif</span>
                        @else
                        <span class="badge-danger">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex flex-col sm:flex-row gap-2">
                            <a href="{{ route('admin.layanan.edit', $item) }}" class="text-blue-600 hover:text-blue-900 transition-colors">Edit</a>
                            <form action="{{ route('admin.layanan.toggle-status', $item) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-yellow-600 hover:text-yellow-900 transition-colors">
                                    {{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                            <form action="{{ route('admin.layanan.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus layanan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 transition-colors">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr id="emptyRow">
                    <td colspan="7" class="px-6 py-8 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="text-gray-500">Belum ada layanan</p>
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
                <p class="text-gray-400 text-sm mt-1">Coba ubah kata kunci pencarian Anda</p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isFirstLoad = !sessionStorage.getItem('layanan_loaded');
        
        if (isFirstLoad) {
            document.body.classList.add('page-load');
            sessionStorage.setItem('layanan_loaded', 'true');
            
            setTimeout(() => {
                document.body.classList.remove('page-load');
            }, 1000);
        }

        // Search and Filter Functionality
        const searchInput = document.getElementById('searchInput');
        const kategoriFilter = document.getElementById('kategoriFilter');
        const statusFilter = document.getElementById('statusFilter');
        const resetButton = document.getElementById('resetFilter');
        const layananRows = document.querySelectorAll('.layanan-row');
        const searchInfo = document.getElementById('searchInfo');
        const resultCount = document.getElementById('resultCount');
        const noResults = document.getElementById('noResults');
        const emptyRow = document.getElementById('emptyRow');

        function filterLayanan() {
            const searchTerm = searchInput.value.toLowerCase();
            const kategoriValue = kategoriFilter.value.toLowerCase();
            const statusValue = statusFilter.value.toLowerCase();
            let visibleCount = 0;

            layananRows.forEach(row => {
                const nama = row.dataset.nama;
                const kategori = row.dataset.kategori;
                const harga = row.dataset.harga;
                const status = row.dataset.status;

                const matchSearch = nama.includes(searchTerm) || 
                                  kategori.includes(searchTerm) || 
                                  harga.includes(searchTerm);
                const matchKategori = kategoriValue === '' || kategori === kategoriValue;
                const matchStatus = statusValue === '' || status === statusValue;

                if (matchSearch && matchKategori && matchStatus) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Update search info
            if (searchTerm || kategoriValue || statusValue) {
                searchInfo.classList.remove('hidden');
                resultCount.textContent = visibleCount;
            } else {
                searchInfo.classList.add('hidden');
            }

            // Show/hide no results message
            if (visibleCount === 0 && layananRows.length > 0) {
                noResults.classList.remove('hidden');
                if (emptyRow) emptyRow.style.display = 'none';
            } else {
                noResults.classList.add('hidden');
            }
        }

        // Event listeners
        searchInput.addEventListener('input', filterLayanan);
        kategoriFilter.addEventListener('change', filterLayanan);
        statusFilter.addEventListener('change', filterLayanan);
        
        resetButton.addEventListener('click', () => {
            searchInput.value = '';
            kategoriFilter.value = '';
            statusFilter.value = '';
            filterLayanan();
        });
    });

    // Enhanced Media Modal Functions
    function openMediaModal(title, type, url) {
        const modal = document.getElementById('mediaModal');
        const modalTitle = document.getElementById('modalTitle');
        const mediaContent = document.getElementById('mediaContent');

        modalTitle.textContent = title;
        
        if (type === 'video') {
            // Check if it's a YouTube URL and convert to embed URL
            let embedUrl = url;
            if (url.includes('youtube.com/watch') || url.includes('youtu.be/')) {
                const videoId = getYouTubeId(url);
                if (videoId) {
                    embedUrl = `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0`;
                }
            }
            
            mediaContent.innerHTML = `
                <div class="video-container">
                    <iframe src="${embedUrl}" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen>
                    </iframe>
                </div>
            `;
        } else {
            // For images
            mediaContent.innerHTML = `
                <img src="${url}" alt="${title}" class="media-preview">
            `;
        }

        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
        
        // Add escape key listener
        const escapeHandler = function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        };
        document.addEventListener('keydown', escapeHandler);
        
        // Store the handler for cleanup
        modal._escapeHandler = escapeHandler;
    }

    function getYouTubeId(url) {
        const regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
        const match = url.match(regExp);
        return (match && match[7].length === 11) ? match[7] : null;
    }

    function closeModal() {
        const modal = document.getElementById('mediaModal');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
        
        // Clean up event listener
        if (modal._escapeHandler) {
            document.removeEventListener('keydown', modal._escapeHandler);
            delete modal._escapeHandler;
        }
        
        // Stop video if playing
        const iframe = document.querySelector('#mediaContent iframe');
        if (iframe) {
            iframe.src = iframe.src.replace('&autoplay=1', '');
        }
    }

    // Close modal
    document.querySelector('.close-modal').addEventListener('click', closeModal);

    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('mediaModal');
        if (event.target === modal) {
            closeModal();
        }
    });

    window.addEventListener('beforeunload', function(e) {
        if (e.target.activeElement.tagName === 'A' && 
            e.target.activeElement.getAttribute('href') !== '#') {
            sessionStorage.removeItem('layanan_loaded');
        }
    });
</script>
@endsection