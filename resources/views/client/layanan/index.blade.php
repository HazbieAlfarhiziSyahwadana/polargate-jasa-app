@extends('layouts.client')

@section('title', 'Lihat Layanan')

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
    .page-load .delay-600 { animation-delay: 0.6s; }

    .card {
        transition: box-shadow 0.3s ease, transform 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        transform: translateY(-4px);
    }

    .search-input {
        transition: all 0.3s ease;
    }

    .search-input:focus {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }

    .image-hover {
        transition: transform 0.3s ease;
        cursor: pointer;
    }

    .image-hover:hover {
        transform: scale(1.05);
    }

    .btn-primary {
        transition: all 0.2s ease;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
    }

    .layanan-card {
        opacity: 1;
        transition: opacity 0.3s ease;
    }

    .layanan-card.hidden-filter {
        display: none;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(5px);
    }

    .modal-content {
        position: relative;
        margin: 5% auto;
        width: 90%;
        max-width: 800px;
        background: white;
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
        padding: 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: between;
        align-items: center;
    }

    .modal-body {
        padding: 0;
    }

    .close-modal {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #6b7280;
        transition: color 0.2s ease;
    }

    .close-modal:hover {
        color: #374151;
    }

    .media-preview {
        width: 100%;
        height: 400px;
        object-fit: contain;
        background: #f8fafc;
    }

    .video-container {
        position: relative;
        padding-bottom: 56.25%; /* 16:9 aspect ratio */
        height: 0;
        overflow: hidden;
    }

    .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
    }

    .media-badge {
        position: absolute;
        top: 8px;
        right: 8px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .video-thumbnail {
        position: relative;
        cursor: pointer;
    }

    .video-thumbnail::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 60px;
        height: 60px;
        background: rgba(0, 0, 0, 0.7);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .video-thumbnail::before {
        content: '▶';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 24px;
        z-index: 2;
    }
</style>

<!-- Modal for Media Preview -->
<div id="mediaModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="text-lg font-semibold text-gray-800" id="modalTitle">Preview Media</h3>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <div id="mediaContent">
                <!-- Content will be inserted here -->
            </div>
        </div>
    </div>
</div>

<div class="mb-6 animate-fade">
    <h1 class="text-3xl font-bold text-gray-800">Layanan Kami</h1>
    <p class="text-gray-600">Pilih layanan yang sesuai dengan kebutuhan Anda</p>
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
                       placeholder="Cari layanan berdasarkan nama atau kategori...">
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

<!-- Layanan Grid -->
@if($layanan->count() > 0)
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="layananGrid">
    @foreach($layanan as $index => $item)
    <div class="card hover:shadow-xl transition-all duration-300 layanan-card animate-slide delay-{{ min($index * 100 + 200, 600) }}"
         data-nama="{{ strtolower($item->nama_layanan) }}"
         data-kategori="{{ strtolower($item->kategori) }}"
         data-harga="{{ $item->harga_mulai }}">
        <!-- Media (Gambar/Video) -->
        <div class="mb-4 overflow-hidden rounded-lg relative">
            @if($item->video_url)
                <div class="video-thumbnail w-full h-48 rounded-lg bg-gradient-to-br from-red-500 to-red-600 overflow-hidden image-hover" 
                     onclick="openMediaModal('{{ $item->nama_layanan }}', 'video', '{{ $item->video_url }}')">
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                    </div>
                    <div class="media-badge">Video</div>
                </div>
            @elseif($item->gambar_url)
                <div class="cursor-pointer" onclick="openMediaModal('{{ $item->nama_layanan }}', 'image', '{{ $item->gambar_url }}')">
                    <img src="{{ $item->gambar_url }}" 
                         alt="{{ $item->nama_layanan }}" 
                         class="w-full h-48 rounded-lg object-contain bg-gray-100 border border-gray-200 image-hover">
                    <div class="media-badge">Gambar</div>
                </div>
            @else
                <div class="w-full h-48 bg-gray-200 rounded-lg shadow-md flex items-center justify-center">
                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            @endif
        </div>

        <!-- Badge Kategori -->
        <span class="badge-info text-xs mb-2">{{ $item->kategori }}</span>

        <!-- Nama & Deskripsi -->
        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $item->nama_layanan }}</h3>
        <p class="text-sm text-gray-600 mb-4 line-clamp-3">{{ $item->deskripsi }}</p>

        <!-- Harga -->
        <div class="flex justify-between items-center mb-4 pb-4 border-b">
            <div>
                <p class="text-xs text-gray-500">Mulai dari</p>
                <p class="text-xl font-bold text-primary-600">Rp {{ number_format($item->harga_mulai, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Paket -->
        @if($item->paket->count() > 0)
        <div class="mb-4">
            <p class="text-xs font-semibold text-gray-700 mb-2">Paket Tersedia:</p>
            <div class="flex flex-wrap gap-1">
                @foreach($item->paket as $paket)
                <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">{{ $paket->nama_paket }}</span>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Action Button -->
        <a href="{{ route('client.layanan.show', $item) }}" class="btn-primary w-full">
            Lihat Detail & Pesan
        </a>
    </div>
    @endforeach
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $layanan->links() }}
</div>

@else
<div class="card text-center py-12 animate-slide delay-200">
    <div class="flex flex-col items-center justify-center gap-4">
        <div class="bg-gray-100 rounded-full p-6">
            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-medium text-gray-900">Tidak Ada Layanan Ditemukan</h3>
            <p class="text-sm text-gray-500 mt-1">Coba ubah filter atau kata kunci pencarian</p>
        </div>
    </div>
</div>
@endif

<!-- No Results Message (untuk filter) -->
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isFirstLoad = !sessionStorage.getItem('layanan_client_loaded');
        
        if (isFirstLoad) {
            document.body.classList.add('page-load');
            sessionStorage.setItem('layanan_client_loaded', 'true');
            
            setTimeout(() => {
                document.body.classList.remove('page-load');
            }, 1000);
        }

        // Search and Filter Functionality
        const searchInput = document.getElementById('searchInput');
        const kategoriFilter = document.getElementById('kategoriFilter');
        const resetButton = document.getElementById('resetFilter');
        const layananCards = document.querySelectorAll('.layanan-card');
        const searchInfo = document.getElementById('searchInfo');
        const resultCount = document.getElementById('resultCount');
        const noResults = document.getElementById('noResults');
        const layananGrid = document.getElementById('layananGrid');

        function filterLayanan() {
            const searchTerm = searchInput.value.toLowerCase();
            const kategoriValue = kategoriFilter.value.toLowerCase();
            let visibleCount = 0;

            layananCards.forEach(card => {
                const nama = card.dataset.nama;
                const kategori = card.dataset.kategori;

                const matchSearch = nama.includes(searchTerm) || kategori.includes(searchTerm);
                const matchKategori = kategoriValue === '' || kategori === kategoriValue;

                if (matchSearch && matchKategori) {
                    card.classList.remove('hidden-filter');
                    visibleCount++;
                } else {
                    card.classList.add('hidden-filter');
                }
            });

            // Update search info
            if (searchTerm || kategoriValue) {
                searchInfo.classList.remove('hidden');
                resultCount.textContent = visibleCount;
            } else {
                searchInfo.classList.add('hidden');
            }

            // Show/hide no results message
            if (visibleCount === 0 && layananCards.length > 0) {
                noResults.classList.remove('hidden');
                if (layananGrid) layananGrid.style.display = 'none';
            } else {
                noResults.classList.add('hidden');
                if (layananGrid) layananGrid.style.display = 'grid';
            }
        }

        // Event listeners
        if (searchInput) searchInput.addEventListener('input', filterLayanan);
        if (kategoriFilter) kategoriFilter.addEventListener('change', filterLayanan);
        
        if (resetButton) {
            resetButton.addEventListener('click', () => {
                searchInput.value = '';
                kategoriFilter.value = '';
                filterLayanan();
            });
        }
    });

    // Media Modal Functions
    function openMediaModal(title, type, url) {
        const modal = document.getElementById('mediaModal');
        const modalTitle = document.getElementById('modalTitle');
        const mediaContent = document.getElementById('mediaContent');

        modalTitle.textContent = title;
        
        if (type === 'video') {
            // Check if it's a YouTube URL
            if (url.includes('youtube.com') || url.includes('youtu.be')) {
                const videoId = getYouTubeId(url);
                mediaContent.innerHTML = `
                    <div class="video-container">
                        <iframe src="https://www.youtube.com/embed/${videoId}" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen>
                        </iframe>
                    </div>
                `;
            } else {
                // For other video URLs
                mediaContent.innerHTML = `
                    <div class="video-container">
                        <video controls class="media-preview">
                            <source src="${url}" type="video/mp4">
                            Browser Anda tidak mendukung pemutar video.
                        </video>
                    </div>
                `;
            }
        } else {
            // For images
            mediaContent.innerHTML = `
                <img src="${url}" alt="${title}" class="media-preview">
            `;
        }

        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function getYouTubeId(url) {
        const regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
        const match = url.match(regExp);
        return (match && match[7].length === 11) ? match[7] : null;
    }

    // Close modal
    document.querySelector('.close-modal').addEventListener('click', function() {
        document.getElementById('mediaModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    });

    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('mediaModal');
        if (event.target === modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });

    window.addEventListener('beforeunload', function(e) {
        if (e.target.activeElement.tagName === 'A' && 
            e.target.activeElement.getAttribute('href') !== '#') {
            sessionStorage.removeItem('layanan_client_loaded');
        }
    });
</script>
@endsection