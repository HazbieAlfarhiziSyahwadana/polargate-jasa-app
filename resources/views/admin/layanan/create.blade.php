@extends('layouts.admin')

@section('title', 'Tambah Layanan')

@section('content')
<style>
/* Modal Styles */
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

.media-preview-modal {
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

/* Preview Card Styles */
.preview-card {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    cursor: pointer;
    transition: all 0.3s ease;
    border: 3px solid transparent;
}

.preview-card:hover {
    transform: scale(1.02);
    border-color: #3b82f6;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
}

.preview-card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.preview-card .play-overlay {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 60px;
    height: 60px;
    background: rgba(220, 38, 38, 0.9);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
    border: 3px solid white;
}

.preview-card:hover .play-overlay {
    opacity: 1;
}

.preview-card .media-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 0.7rem;
    font-weight: 600;
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    gap: 5px;
}

.video-preview-container {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Animation Styles */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.animate-fadeIn {
    animation: fadeIn 0.6s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fadeInUp {
    animation: fadeInUp 0.6s ease-out;
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.animate-slideInLeft {
    animation: slideInLeft 0.6s ease-out forwards;
    opacity: 0;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}

.animate-shake {
    animation: shake 0.5s ease-in-out;
}

.input-wrapper {
    position: relative;
}

.input-border {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background: linear-gradient(to right, #2563eb, #3b82f6);
    transition: width 0.4s ease;
    border-radius: 0 0 12px 12px;
}

.input-focused .input-border {
    width: 100%;
}

.form-group {
    transition: transform 0.3s ease;
}

.form-group:hover {
    transform: translateX(3px);
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
            <div id="mediaContent"></div>
        </div>
    </div>
</div>

<div class="mb-6 animate-fadeIn">
    <h1 class="text-3xl font-bold text-gray-800">Tambah Layanan Baru</h1>
    <p class="text-gray-600">Isi form di bawah untuk menambah layanan</p>
</div>

<div class="card max-w-4xl animate-fadeInUp hover:shadow-xl transition-shadow duration-300">
    <form action="{{ route('admin.layanan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Live Preview Section -->
        <div id="livePreviewSection" class="mb-6 hidden">
            <label class="block text-gray-700 text-sm font-medium mb-3">
                <i class="fas fa-eye text-primary-600 mr-2"></i>Preview Media
            </label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Image Preview Card -->
                <div id="imagePreviewCard" class="preview-card hidden">
                    <img id="previewImageLarge" src="" alt="Preview">
                    <div class="media-badge">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Gambar</span>
                    </div>
                </div>
                
                <!-- Video Preview Card -->
                <div id="videoPreviewCard" class="preview-card hidden">
                    <div class="bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center" style="height: 200px;">
                        <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                    </div>
                    <div class="play-overlay">
                        <svg class="w-8 h-8 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                    </div>
                    <div class="media-badge">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                        <span>Video YouTube</span>
                    </div>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2">
                <i class="fas fa-hand-pointer mr-1"></i> Klik preview untuk melihat full size
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Kategori -->
            <div class="form-group animate-slideInLeft" style="animation-delay: 0.1s;">
                <label for="kategori" class="block text-gray-700 text-sm font-medium mb-2">
                    <i class="fas fa-folder text-primary-600 mr-2"></i>Kategori <span class="text-red-500">*</span>
                </label>
                <div class="input-wrapper">
                    <select name="kategori" id="kategori" class="input-field @error('kategori') border-red-500 @enderror" required>
                        <option value="">Pilih Kategori</option>
                        <option value="Multimedia" {{ old('kategori') == 'Multimedia' ? 'selected' : '' }}>Multimedia</option>
                        <option value="IT" {{ old('kategori') == 'IT' ? 'selected' : '' }}>IT</option>
                    </select>
                    <div class="input-border"></div>
                </div>
                @error('kategori')
                <p class="text-red-500 text-xs mt-1 animate-shake">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nama Layanan -->
            <div class="form-group animate-slideInLeft" style="animation-delay: 0.2s;">
                <label for="nama_layanan" class="block text-gray-700 text-sm font-medium mb-2">
                    <i class="fas fa-tag text-primary-600 mr-2"></i>Nama Layanan <span class="text-red-500">*</span>
                </label>
                <div class="input-wrapper">
                    <input type="text" name="nama_layanan" id="nama_layanan" value="{{ old('nama_layanan') }}" class="input-field @error('nama_layanan') border-red-500 @enderror" placeholder="Contoh: Animasi 3D" required>
                    <div class="input-border"></div>
                </div>
                @error('nama_layanan')
                <p class="text-red-500 text-xs mt-1 animate-shake">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Deskripsi -->
        <div class="mt-4 form-group animate-slideInLeft" style="animation-delay: 0.3s;">
            <label for="deskripsi" class="block text-gray-700 text-sm font-medium mb-2">
                <i class="fas fa-align-left text-primary-600 mr-2"></i>Deskripsi <span class="text-red-500">*</span>
            </label>
            <div class="input-wrapper">
                <textarea name="deskripsi" id="deskripsi" rows="4" class="input-field @error('deskripsi') border-red-500 @enderror" placeholder="Deskripsi lengkap layanan" required>{{ old('deskripsi') }}</textarea>
                <div class="input-border"></div>
            </div>
            @error('deskripsi')
            <p class="text-red-500 text-xs mt-1 animate-shake">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
            <!-- Gambar -->
            <div class="form-group animate-slideInLeft" style="animation-delay: 0.4s;">
                <label for="gambar" class="block text-gray-700 text-sm font-medium mb-2">
                    <i class="fas fa-image text-primary-600 mr-2"></i>Gambar Layanan
                </label>
                <div class="input-wrapper">
                    <input type="file" name="gambar" id="gambar" accept="image/*" class="input-field @error('gambar') border-red-500 @enderror">
                    <div class="input-border"></div>
                </div>
                @error('gambar')
                <p class="text-red-500 text-xs mt-1 animate-shake">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Max 2MB (jpg, jpeg, png)</p>
            </div>

            <!-- Video URL -->
            <div class="form-group animate-slideInLeft" style="animation-delay: 0.5s;">
                <label for="video_url" class="block text-gray-700 text-sm font-medium mb-2">
                    <i class="fas fa-video text-primary-600 mr-2"></i>URL Video YouTube
                </label>
                <div class="input-wrapper">
                    <input type="url" name="video_url" id="video_url" value="{{ old('video_url') }}" class="input-field @error('video_url') border-red-500 @enderror" placeholder="https://youtube.com/watch?v=...">
                    <div class="input-border"></div>
                </div>
                @error('video_url')
                <p class="text-red-500 text-xs mt-1 animate-shake">{{ $message }}</p>
                @enderror
                <p class="text-xs text-blue-600 mt-1">
                    <i class="fas fa-info-circle"></i> Video akan diputar saat preview diklik
                </p>
            </div>
        </div>

        <!-- Harga Mulai -->
        <div class="mt-4 form-group animate-slideInLeft" style="animation-delay: 0.6s;">
            <label for="harga_mulai" class="block text-gray-700 text-sm font-medium mb-2">
                <i class="fas fa-money-bill-wave text-primary-600 mr-2"></i>Harga Mulai <span class="text-red-500">*</span>
            </label>
            <div class="input-wrapper">
                <input type="number" name="harga_mulai" id="harga_mulai" value="{{ old('harga_mulai') }}" class="input-field @error('harga_mulai') border-red-500 @enderror" placeholder="5000000" required>
                <div class="input-border"></div>
            </div>
            @error('harga_mulai')
            <p class="text-red-500 text-xs mt-1 animate-shake">{{ $message }}</p>
            @enderror
        </div>

        <!-- Status Aktif -->
        <div class="mt-4 animate-slideInLeft" style="animation-delay: 0.7s;">
            <label class="flex items-center cursor-pointer group">
                <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 transition-all duration-300" {{ old('is_active', true) ? 'checked' : '' }}>
                <span class="ml-2 text-sm text-gray-700 group-hover:text-primary-600 transition-colors duration-300">
                    <i class="fas fa-check-circle mr-1"></i>Aktifkan layanan
                </span>
            </label>
        </div>

        <!-- Buttons -->
        <div class="flex flex-col sm:flex-row justify-end gap-3 mt-6 animate-fadeIn" style="animation-delay: 0.8s;">
            <a href="{{ route('admin.layanan.index') }}" class="btn-secondary hover:scale-105 transition-transform duration-300 text-center">
                <i class="fas fa-times mr-2"></i>Batal
            </a>
            <button type="submit" class="btn-primary hover:scale-105 transition-transform duration-300 hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Simpan Layanan
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentImageUrl = null;
    let currentVideoUrl = null;

    const livePreviewSection = document.getElementById('livePreviewSection');
    const imagePreviewCard = document.getElementById('imagePreviewCard');
    const videoPreviewCard = document.getElementById('videoPreviewCard');
    const previewImageLarge = document.getElementById('previewImageLarge');

    // Image input handler
    document.getElementById('gambar').addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                currentImageUrl = e.target.result;
                previewImageLarge.src = currentImageUrl;
                imagePreviewCard.classList.remove('hidden');
                updatePreviewSection();
            }
            reader.readAsDataURL(file);
        } else {
            currentImageUrl = null;
            imagePreviewCard.classList.add('hidden');
            updatePreviewSection();
        }
    });

    // Video URL input handler
    document.getElementById('video_url').addEventListener('input', function(e) {
        const url = e.target.value;
        const youtubeId = getYouTubeId(url);
        
        if (youtubeId) {
            currentVideoUrl = url;
            videoPreviewCard.classList.remove('hidden');
        } else {
            currentVideoUrl = null;
            videoPreviewCard.classList.add('hidden');
        }
        updatePreviewSection();
    });

    // Update preview section visibility
    function updatePreviewSection() {
        if (currentImageUrl || currentVideoUrl) {
            livePreviewSection.classList.remove('hidden');
        } else {
            livePreviewSection.classList.add('hidden');
        }
    }

    // Image preview card click
    imagePreviewCard.addEventListener('click', function() {
        if (currentImageUrl) {
            openMediaModal('Preview Gambar', 'image', currentImageUrl);
        }
    });

    // Video preview card click
    videoPreviewCard.addEventListener('click', function() {
        if (currentVideoUrl) {
            openMediaModal('Preview Video', 'video', currentVideoUrl);
        }
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

    // Input focus effects
    document.querySelectorAll('input, select, textarea').forEach(input => {
        input.addEventListener('focus', function() {
            this.closest('.input-wrapper')?.classList.add('input-focused');
        });
        
        input.addEventListener('blur', function() {
            this.closest('.input-wrapper')?.classList.remove('input-focused');
        });
    });
});

// Open media modal
function openMediaModal(title, type, url) {
    const modal = document.getElementById('mediaModal');
    const modalTitle = document.getElementById('modalTitle');
    const mediaContent = document.getElementById('mediaContent');

    modalTitle.textContent = title;
    
    if (type === 'video') {
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
            <img src="${url}" alt="${title}" class="media-preview-modal">
        `;
    }

    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

// Close modal
function closeModal() {
    const modal = document.getElementById('mediaModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    
    const iframe = document.querySelector('#mediaContent iframe');
    if (iframe) {
        iframe.src = '';
    }
}

// Get YouTube ID from URL
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
</script>
@endsection