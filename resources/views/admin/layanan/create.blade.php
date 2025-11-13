@extends('layouts.admin')

@section('title', 'Tambah Layanan')

@section('content')
<div class="mb-6 animate-fadeIn">
    <h1 class="text-3xl font-bold text-gray-800">Tambah Layanan Baru</h1>
    <p class="text-gray-600">Isi form di bawah untuk menambah layanan</p>
</div>

<div class="card max-w-4xl animate-fadeInUp hover:shadow-xl transition-shadow duration-300">
    <form action="{{ route('admin.layanan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

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
                    <input type="file" name="gambar" id="gambar" accept="image/*" class="input-field @error('gambar') border-red-500 @enderror" onchange="previewImage(event, 'previewImage')">
                    <div class="input-border"></div>
                </div>
                @error('gambar')
                <p class="text-red-500 text-xs mt-1 animate-shake">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Max 2MB (jpg, jpeg, png)</p>
                <img id="previewImage" class="mt-3 w-32 h-32 object-cover rounded-lg shadow-md hidden animate-fadeIn">
            </div>

            <!-- Video URL -->
            <div class="form-group animate-slideInLeft" style="animation-delay: 0.5s;">
                <label for="video_url" class="block text-gray-700 text-sm font-medium mb-2">
                    <i class="fas fa-video text-primary-600 mr-2"></i>URL Video (YouTube/Video)
                </label>
                <div class="input-wrapper">
                    <input type="url" name="video_url" id="video_url" value="{{ old('video_url') }}" class="input-field @error('video_url') border-red-500 @enderror" placeholder="https://youtube.com/embed/... atau URL video">
                    <div class="input-border"></div>
                </div>
                @error('video_url')
                <p class="text-red-500 text-xs mt-1 animate-shake">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Masukkan URL YouTube atau video</p>
                
                @if(old('video_url'))
                <div class="mt-3">
                    <div class="video-preview-container">
                        <div class="video-container">
                            <iframe src="{{ old('video_url') }}" frameborder="0" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
                @endif
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

@push('styles')
<style>
.video-preview-container {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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
</style>
@endpush

@push('scripts')
<script>
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

// Preview video when URL is entered
document.getElementById('video_url').addEventListener('input', function(e) {
    const url = e.target.value;
    const previewContainer = this.parentElement.parentElement.querySelector('.video-preview-container');
    
    if (url) {
        if (!previewContainer) {
            const container = document.createElement('div');
            container.className = 'mt-3';
            container.innerHTML = `
                <div class="video-preview-container">
                    <div class="video-container">
                        <iframe src="${url}" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
            `;
            this.parentElement.parentElement.appendChild(container);
        } else {
            previewContainer.querySelector('iframe').src = url;
        }
    } else if (previewContainer) {
        previewContainer.remove();
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
</script>
@endpush

<style>
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