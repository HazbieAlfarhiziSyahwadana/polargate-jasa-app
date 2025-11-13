@extends('layouts.admin')

@section('title', 'Edit Layanan')

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
        backdrop-filter: blur(5px);
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

    .full-card-image {
        width: 100%;
        height: 300px;
        object-fit: cover;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 3px solid transparent;
    }

    .full-card-image:hover {
        transform: scale(1.02);
        border-color: #3b82f6;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    }

    .media-preview-container {
        position: relative;
        margin-bottom: 1rem;
    }

    .play-button-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 80px;
        height: 80px;
        background: rgba(0, 0, 0, 0.7);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        opacity: 0;
    }

    .media-preview-container:hover .play-button-overlay {
        opacity: 1;
    }

    .play-button-overlay:hover {
        background: rgba(220, 38, 38, 0.9);
        transform: translate(-50%, -50%) scale(1.1);
    }

    .play-icon {
        color: white;
        font-size: 2rem;
        margin-left: 5px;
    }

    .media-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
    }

    .media-info {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        padding: 2rem 1.5rem 1rem;
        color: white;
    }

    .media-title {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .media-description {
        font-size: 0.9rem;
        opacity: 0.9;
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

<div class="mb-6 animate-fadeIn">
    <h1 class="text-3xl font-bold text-gray-800">Edit Layanan</h1>
    <p class="text-gray-600">Update informasi layanan</p>
</div>

<div class="card max-w-6xl animate-fadeInUp hover:shadow-xl transition-shadow duration-300">
    <form action="{{ route('admin.layanan.update', $layanan) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Media Preview Section -->
        <div class="mb-8 animate-slideInLeft">
            <label class="block text-gray-700 text-sm font-medium mb-4">
                <i class="fas fa-eye text-primary-600 mr-2"></i>Preview Media Layanan
            </label>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Current Media Display -->
                <div class="media-preview-container">
                    @if($layanan->video_url)
                        <!-- Video Thumbnail -->
                        <div class="relative rounded-xl overflow-hidden shadow-2xl">
                            <img src="{{ $layanan->youtube_thumbnail ?? $layanan->gambar_url }}" 
                                 alt="{{ $layanan->nama_layanan }}" 
                                 class="full-card-image"
                                 onclick="openMediaModal('{{ $layanan->nama_layanan }}', 'video', '{{ $layanan->youtube_embed_url ?? $layanan->video_url }}')">
                            
                            <div class="play-button-overlay" 
                                 onclick="openMediaModal('{{ $layanan->nama_layanan }}', 'video', '{{ $layanan->youtube_embed_url ?? $layanan->video_url }}')">
                                <svg class="play-icon w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                            
                            <div class="media-badge">
                                <i class="fas fa-video mr-1"></i>Video YouTube
                            </div>
                            
                            <div class="media-info">
                                <div class="media-title">{{ $layanan->nama_layanan }}</div>
                                <div class="media-description">Klik untuk memutar video</div>
                            </div>
                        </div>
                    @elseif($layanan->gambar)
                        <!-- Image Display -->
                        <div class="relative rounded-xl overflow-hidden shadow-2xl">
                            <img src="{{ $layanan->gambar_url }}" 
                                 alt="{{ $layanan->nama_layanan }}" 
                                 class="full-card-image"
                                 onclick="openMediaModal('{{ $layanan->nama_layanan }}', 'image', '{{ $layanan->gambar_url }}')">
                            
                            <div class="media-badge">
                                <i class="fas fa-image mr-1"></i>Gambar
                            </div>
                            
                            <div class="media-info">
                                <div class="media-title">{{ $layanan->nama_layanan }}</div>
                                <div class="media-description">Klik untuk melihat gambar full size</div>
                            </div>
                        </div>
                    @else
                        <!-- Placeholder -->
                        <div class="relative rounded-xl overflow-hidden shadow-2xl bg-gradient-to-br from-gray-200 to-gray-300">
                            <div class="full-card-image flex items-center justify-center">
                                <div class="text-center text-gray-500">
                                    <svg class="w-16 h-16 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="font-medium">Belum ada media</p>
                                    <p class="text-sm">Upload gambar atau tambahkan video URL</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Media Information -->
                <div class="bg-gradient-to-br from-primary-50 to-blue-50 p-6 rounded-xl border border-primary-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-info-circle text-primary-600"></i>
                        Informasi Media
                    </h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center py-2 border-b border-primary-100">
                            <span class="text-gray-600">Tipe Media:</span>
                            <span class="font-semibold text-primary-600">
                                @if($layanan->video_url)
                                    Video YouTube
                                @elseif($layanan->gambar)
                                    Gambar
                                @else
                                    Tidak ada
                                @endif
                            </span>
                        </div>
                        
                        @if($layanan->video_url)
                        <div class="flex justify-between items-center py-2 border-b border-primary-100">
                            <span class="text-gray-600">URL Video:</span>
                            <span class="font-medium text-gray-700 text-xs truncate max-w-[200px]">
                                {{ $layanan->video_url }}
                            </span>
                        </div>
                        @endif
                        
                        @if($layanan->gambar)
                        <div class="flex justify-between items-center py-2 border-b border-primary-100">
                            <span class="text-gray-600">File Gambar:</span>
                            <span class="font-medium text-gray-700">{{ $layanan->gambar }}</span>
                        </div>
                        @endif
                        
                        <div class="mt-4 p-3 bg-primary-100 rounded-lg">
                            <p class="text-xs text-primary-700">
                                <i class="fas fa-lightbulb mr-1"></i>
                                <strong>Tips:</strong> Klik pada gambar di samping untuk melihat preview full size atau memutar video.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
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
                        <option value="Multimedia" {{ old('kategori', $layanan->kategori) == 'Multimedia' ? 'selected' : '' }}>Multimedia</option>
                        <option value="IT" {{ old('kategori', $layanan->kategori) == 'IT' ? 'selected' : '' }}>IT</option>
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
                    <input type="text" name="nama_layanan" id="nama_layanan" value="{{ old('nama_layanan', $layanan->nama_layanan) }}" class="input-field @error('nama_layanan') border-red-500 @enderror" required>
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
                <textarea name="deskripsi" id="deskripsi" rows="4" class="input-field @error('deskripsi') border-red-500 @enderror" required>{{ old('deskripsi', $layanan->deskripsi) }}</textarea>
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
                
                @if($layanan->gambar)
                <div class="mb-3">
                    <p class="text-xs text-gray-500 mb-2">Gambar saat ini: <span class="font-medium">{{ $layanan->gambar }}</span></p>
                </div>
                @endif
                
                <div class="input-wrapper">
                    <input type="file" name="gambar" id="gambar" accept="image/*" class="input-field @error('gambar') border-red-500 @enderror" onchange="previewImage(event, 'previewImage')">
                    <div class="input-border"></div>
                </div>
                @error('gambar')
                <p class="text-red-500 text-xs mt-1 animate-shake">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Max 2MB (jpg, jpeg, png). Kosongkan jika tidak ingin mengubah</p>
                <img id="previewImage" class="mt-3 w-32 h-32 object-cover rounded-lg shadow-md hidden animate-fadeIn">
            </div>

            <!-- Video URL -->
            <div class="form-group animate-slideInLeft" style="animation-delay: 0.5s;">
                <label for="video_url" class="block text-gray-700 text-sm font-medium mb-2">
                    <i class="fas fa-video text-primary-600 mr-2"></i>URL Video YouTube
                </label>
                <div class="input-wrapper">
                    <input type="url" name="video_url" id="video_url" 
                           value="{{ old('video_url', $layanan->video_url) }}" 
                           class="input-field @error('video_url') border-red-500 @enderror" 
                           placeholder="https://youtube.com/watch?v=... atau https://youtu.be/...">
                    <div class="input-border"></div>
                </div>
                @error('video_url')
                <p class="text-red-500 text-xs mt-1 animate-shake">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Masukkan URL video YouTube. Jika diisi, akan menimpa gambar.</p>
                
                <!-- Video Preview -->
                <div id="videoPreviewContainer" class="mt-3 hidden">
                    <div class="video-preview-container">
                        <div class="video-container">
                            <iframe id="videoPreview" src="" frameborder="0" allowfullscreen></iframe>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Preview video</p>
                </div>
            </div>
        </div>

        <!-- Harga Mulai -->
        <div class="mt-4 form-group animate-slideInLeft" style="animation-delay: 0.6s;">
            <label for="harga_mulai" class="block text-gray-700 text-sm font-medium mb-2">
                <i class="fas fa-money-bill-wave text-primary-600 mr-2"></i>Harga Mulai <span class="text-red-500">*</span>
            </label>
            <div class="input-wrapper">
                <input type="number" name="harga_mulai" id="harga_mulai" value="{{ old('harga_mulai', $layanan->harga_mulai) }}" class="input-field @error('harga_mulai') border-red-500 @enderror" required>
                <div class="input-border"></div>
            </div>
            @error('harga_mulai')
            <p class="text-red-500 text-xs mt-1 animate-shake">{{ $message }}</p>
            @enderror
        </div>

        <!-- Status Aktif -->
        <div class="mt-4 animate-slideInLeft" style="animation-delay: 0.7s;">
            <label class="flex items-center cursor-pointer group">
                <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 transition-all duration-300" {{ old('is_active', $layanan->is_active) ? 'checked' : '' }}>
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
                <i class="fas fa-save mr-2"></i>Update Layanan
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Media Modal Functions
function openMediaModal(title, type, url) {
    const modal = document.getElementById('mediaModal');
    const modalTitle = document.getElementById('modalTitle');
    const mediaContent = document.getElementById('mediaContent');

    modalTitle.textContent = title;
    
    if (type === 'video') {
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

function previewImage(event, previewId) {
    const preview = document.getElementById(previewId);
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}

// YouTube URL processing and preview
document.getElementById('video_url').addEventListener('input', function(e) {
    const url = e.target.value;
    const previewContainer = document.getElementById('videoPreviewContainer');
    const videoPreview = document.getElementById('videoPreview');
    
    if (url) {
        const youtubeId = getYouTubeId(url);
        if (youtubeId) {
            const embedUrl = `https://www.youtube.com/embed/${youtubeId}`;
            videoPreview.src = embedUrl;
            previewContainer.classList.remove('hidden');
        } else {
            previewContainer.classList.add('hidden');
        }
    } else {
        previewContainer.classList.add('hidden');
    }
});

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

// Add focus effect to inputs
document.querySelectorAll('input[type="text"], input[type="number"], input[type="file"], input[type="url"], select, textarea').forEach(input => {
    input.addEventListener('focus', function() {
        this.closest('.input-wrapper')?.classList.add('input-focused');
    });
    
    input.addEventListener('blur', function() {
        this.closest('.input-wrapper')?.classList.remove('input-focused');
    });
});

// Initialize video preview if there's existing video URL
document.addEventListener('DOMContentLoaded', function() {
    const videoUrl = document.getElementById('video_url').value;
    if (videoUrl) {
        document.getElementById('video_url').dispatchEvent(new Event('input'));
    }
});
</script>
@endpush

<style>
/* Existing styles remain the same */
.video-preview-container {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.video-container {
    position: relative;
    padding-bottom: 56.25%;
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

/* Fade In Animation */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.animate-fadeIn {
    animation: fadeIn 0.6s ease-out;
}

/* Fade In Up Animation */
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

/* Slide In Left Animation */
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

/* Shake Animation for Errors */
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}

.animate-shake {
    animation: shake 0.5s ease-in-out;
}

/* Input Focus Effect */
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

/* Form Group Animation */
.form-group {
    transition: transform 0.3s ease;
}

.form-group:hover {
    transform: translateX(3px);
}
</style>
@endsection