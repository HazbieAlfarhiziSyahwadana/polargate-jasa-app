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

    /* Enhanced Card Styling */
    .layanan-card {
        overflow: hidden;
    }

    .layanan-card .card {
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    /* Full-width Image Container */
    .media-wrapper {
        margin: -1.5rem -1.5rem 1.5rem -1.5rem;
        position: relative;
        overflow: hidden;
        height: 400px;
        background: #f3f4f6;
    }

    .media-wrapper img,
    .media-wrapper .placeholder-content {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Content Section */
    .card-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        padding: 0 1.5rem 1.5rem 1.5rem;
    }

    /* Modal Styles - Same as admin */
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
        padding-bottom: 56.25%;
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

    /* Choice Modal */
    .choice-modal {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        max-width: 500px;
        width: 90%;
        margin: 15vh auto;
    }

    .choice-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1rem;
        text-align: center;
    }

    .choice-subtitle {
        color: #6b7280;
        text-align: center;
        margin-bottom: 2rem;
    }

    .choice-buttons {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .choice-btn {
        padding: 1.5rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        background: white;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
    }

    .choice-btn:hover {
        border-color: #3b82f6;
        background: #eff6ff;
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(59, 130, 246, 0.2);
    }

    .choice-icon {
        width: 48px;
        height: 48px;
        margin: 0 auto 0.75rem;
        color: #3b82f6;
    }

    .choice-label {
        font-weight: 600;
        color: #1f2937;
        font-size: 1rem;
    }

    /* Video Link Badge */
    .video-link-badge {
        position: absolute;
        bottom: 16px;
        right: 16px;
        background: linear-gradient(135deg, rgba(220, 38, 38, 0.95) 0%, rgba(239, 68, 68, 0.95) 100%);
        color: white;
        padding: 10px 18px;
        border-radius: 25px;
        font-size: 0.8rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
        backdrop-filter: blur(10px);
        z-index: 5;
        transition: all 0.3s ease;
        pointer-events: none;
        box-shadow: 0 4px 15px rgba(220, 38, 38, 0.4);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .video-link-badge svg {
        width: 16px;
        height: 16px;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    .media-container {
        position: relative;
        cursor: pointer;
        overflow: hidden;
        width: 100%;
        height: 100%;
    }

    .media-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, transparent 60%, rgba(0,0,0,0.3));
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 1;
        pointer-events: none;
    }

    .media-container:hover::before {
        opacity: 1;
    }

    .media-container:hover .video-link-badge {
        transform: scale(1.05);
        box-shadow: 0 6px 20px rgba(220, 38, 38, 0.6);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .media-wrapper {
            height: 300px;
        }

        .choice-buttons {
            grid-template-columns: 1fr;
        }

        .video-link-badge {
            bottom: 12px;
            right: 12px;
            padding: 8px 14px;
            font-size: 0.7rem;
        }

        .video-link-badge svg {
            width: 14px;
            height: 14px;
        }
    }

    @media (min-width: 1024px) {
        .media-wrapper {
            height: 450px;
        }
    }
</style>

<!-- Modal for Media Preview - Same structure as admin -->
<div id="mediaModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="text-lg font-semibold text-white" id="modalTitle">Preview Media</h3>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <div id="mediaContent"></div>
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

        <div class="md:w-48">
            <select id="kategoriFilter" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition">
                <option value="">Semua Kategori</option>
                <option value="multimedia">Multimedia</option>
                <option value="it">IT</option>
            </select>
        </div>

        <button id="resetFilter" 
                class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-all duration-200 flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Reset
        </button>
    </div>

    <div id="searchInfo" class="mt-4 text-sm text-gray-600 hidden">
        Menampilkan <span id="resultCount" class="font-semibold text-primary-600"></span> hasil
    </div>
</div>

<!-- Layanan Grid -->
@if($layanan->count() > 0)
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="layananGrid">
    @foreach($layanan as $index => $item)
    @php
        $hasImage = !empty($item->gambar);
        $hasVideo = !empty($item->video_url);
    @endphp
    <div class="layanan-card animate-slide delay-{{ min($index * 100 + 200, 600) }}"
         data-nama="{{ strtolower($item->nama_layanan) }}"
         data-kategori="{{ strtolower($item->kategori) }}"
         data-harga="{{ $item->harga_mulai }}">
        
        <div class="card hover:shadow-xl transition-all duration-300">
            <!-- Full-width Media Display -->
            <div class="media-wrapper">
                @if($hasImage || $hasVideo)
                    <div class="media-container media-display"
                         data-title="{{ $item->nama_layanan }}"
                         data-has-image="{{ $hasImage ? 'true' : 'false' }}"
                         data-has-video="{{ $hasVideo ? 'true' : 'false' }}"
                         data-image-url="{{ $hasImage ? $item->gambar_url : '' }}"
                         data-video-url="{{ $hasVideo ? $item->video_url : '' }}">
                        
                        @if($hasImage)
                            <img src="{{ $item->gambar_url }}" 
                                 alt="{{ $item->nama_layanan }}" 
                                 class="image-hover">
                        @else
                            <!-- Video only placeholder -->
                            <div class="placeholder-content bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center">
                                <svg class="w-24 h-24 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                        @endif
                        
                        @if($hasVideo)
                            <div class="video-link-badge">
                                <svg fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                                <span>Lihat Video</span>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="placeholder-content bg-gray-200 flex items-center justify-center">
                        <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif
            </div>

            <!-- Card Content -->
            <div class="card-content">
                <span class="badge-info text-xs mb-2">{{ $item->kategori }}</span>

                <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $item->nama_layanan }}</h3>
                <p class="text-sm text-gray-600 mb-4 line-clamp-3">{{ $item->deskripsi }}</p>

                <div class="flex justify-between items-center mb-4 pb-4 border-b">
                    <div>
                        <p class="text-xs text-gray-500">Mulai dari</p>
                        <p class="text-xl font-bold text-primary-600">Rp {{ number_format($item->harga_mulai, 0, ',', '.') }}</p>
                    </div>
                </div>

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

                <a href="{{ route('client.layanan.show', $item) }}" class="btn-primary w-full">
                    Lihat Detail & Pesan
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>

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

    // Search and Filter
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

        if (searchTerm || kategoriValue) {
            searchInfo.classList.remove('hidden');
            resultCount.textContent = visibleCount;
        } else {
            searchInfo.classList.add('hidden');
        }

        if (visibleCount === 0 && layananCards.length > 0) {
            noResults.classList.remove('hidden');
            if (layananGrid) layananGrid.style.display = 'none';
        } else {
            noResults.classList.add('hidden');
            if (layananGrid) layananGrid.style.display = 'grid';
        }
    }

    if (searchInput) searchInput.addEventListener('input', filterLayanan);
    if (kategoriFilter) kategoriFilter.addEventListener('change', filterLayanan);
    
    if (resetButton) {
        resetButton.addEventListener('click', () => {
            searchInput.value = '';
            kategoriFilter.value = '';
            filterLayanan();
        });
    }

    // Media Display Click Handler - Same pattern as admin
    document.querySelectorAll('.media-display').forEach(mediaDiv => {
        mediaDiv.addEventListener('click', function() {
            const title = this.getAttribute('data-title');
            const hasImage = this.getAttribute('data-has-image') === 'true';
            const hasVideo = this.getAttribute('data-has-video') === 'true';
            const imageUrl = this.getAttribute('data-image-url');
            const videoUrl = this.getAttribute('data-video-url');
            
            console.log('Media clicked:', { title, hasImage, hasVideo, imageUrl, videoUrl });
            
            if (hasImage && hasVideo) {
                // Both image and video - show choice modal
                showChoiceModal(title, imageUrl, videoUrl);
            } else if (hasVideo) {
                // Video only
                openMediaModal(title, 'video', videoUrl);
            } else if (hasImage) {
                // Image only
                openMediaModal(title, 'image', imageUrl);
            }
        });
    });

    // Close modal button
    document.querySelector('.close-modal').addEventListener('click', closeModal);

    // Click outside modal to close
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('mediaModal');
        if (event.target === modal) {
            closeModal();
        }
    });

    // ESC key to close modal
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal();
        }
    });
});

// Show choice modal when both image and video exist
function showChoiceModal(title, imageUrl, videoUrl) {
    const modal = document.getElementById('mediaModal');
    const modalTitle = document.getElementById('modalTitle');
    const mediaContent = document.getElementById('mediaContent');

    modalTitle.textContent = title;
    
    mediaContent.innerHTML = `
        <div class="choice-modal">
            <h3 class="choice-title">Pilih Media</h3>
            <p class="choice-subtitle">Layanan ini memiliki gambar dan video</p>
            
            <div class="choice-buttons">
                <button class="choice-btn" onclick="openMediaModal('${title.replace(/'/g, "\\'")}', 'image', '${imageUrl}')">
                    <svg class="choice-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <div class="choice-label">Lihat Gambar</div>
                </button>
                
                <button class="choice-btn" onclick="openMediaModal('${title.replace(/'/g, "\\'")}', 'video', '${videoUrl}')">
                    <svg class="choice-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="choice-label">Putar Video</div>
                </button>
            </div>
        </div>
    `;

    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

// Open media modal - Same as admin
function openMediaModal(title, type, url) {
    console.log('Opening modal:', { title, type, url });
    
    const modal = document.getElementById('mediaModal');
    const modalTitle = document.getElementById('modalTitle');
    const mediaContent = document.getElementById('mediaContent');

    modalTitle.textContent = title;
    
    if (type === 'video') {
        // Convert YouTube URL to embed format
        const youtubeId = getYouTubeId(url);
        if (youtubeId) {
            url = `https://www.youtube.com/embed/${youtubeId}?autoplay=1&rel=0`;
        }
        
        mediaContent.innerHTML = `
            <div class="video-container">
                <iframe src="${url}" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                </iframe>
            </div>
        `;
    } else {
        mediaContent.innerHTML = `
            <img src="${url}" alt="${title}" class="media-preview">
        `;
    }

    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

// Close modal - Same as admin
function closeModal() {
    const modal = document.getElementById('mediaModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    
    // Stop video if playing
    const iframe = document.querySelector('#mediaContent iframe');
    if (iframe) {
        iframe.src = '';
    }
}

// Get YouTube ID from URL - Same as admin
function getYouTubeId(url) {
    if (!url) return null;
    
    const patterns = [
        /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/|youtube\.com\/v\/|youtube\.com\/\?v=)([a-zA-Z0-9_-]{11})/,
        /youtube\.com\/watch\?.*v=([a-zA-Z0-9_-]{11})/,
        /youtu\.be\/([a-zA-Z0-9_-]{11})/,
        /youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/,
        /^([a-zA-Z0-9_-]{11})$/
    ];
    
    for (let pattern of patterns) {
        const match = url.match(pattern);
        if (match && match[1]) {
            return match[1];
        }
    }
    
    return null;
}

window.addEventListener('beforeunload', function(e) {
    if (e.target.activeElement.tagName === 'A' && 
        e.target.activeElement.getAttribute('href') !== '#') {
        sessionStorage.removeItem('layanan_client_loaded');
    }
});
</script>
@endsection