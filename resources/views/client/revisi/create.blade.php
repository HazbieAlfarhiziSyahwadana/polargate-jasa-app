@extends('layouts.client')

@section('title', 'Ajukan Revisi')

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
        transition: box-shadow 0.3s ease;
    }

    .file-upload-area {
        transition: all 0.3s ease;
        border: 2px dashed #cbd5e1;
    }

    .file-upload-area:hover {
        border-color: #3b82f6;
        background-color: #eff6ff;
    }

    .file-upload-area.dragover {
        border-color: #3b82f6;
        background-color: #dbeafe;
        transform: scale(1.02);
    }

    .file-item {
        animation: slideUp 0.3s ease-out;
    }

    .btn-primary, .btn-secondary {
        transition: all 0.2s ease;
    }

    .btn-primary:hover, .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
    }
</style>

<div class="mb-6 animate-fade">
    <a href="{{ route('client.pesanan.show', $pesanan) }}" class="inline-flex items-center text-primary-600 hover:text-primary-700 mb-4">
        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali ke Detail Pesanan
    </a>
    <h1 class="text-3xl font-bold text-gray-800">Ajukan Revisi</h1>
    <p class="text-gray-600">Kirimkan permintaan revisi untuk pesanan Anda</p>
</div>

<!-- Informasi Pesanan -->
<div class="card mb-6 animate-slide delay-100">
    <div class="flex items-center gap-3 mb-4">
        <div class="bg-primary-100 rounded-full p-3">
            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <div>
            <h2 class="text-xl font-bold text-gray-800">{{ $pesanan->kode_pesanan }}</h2>
            <p class="text-sm text-gray-600">{{ $pesanan->layanan->nama_layanan }} - {{ $pesanan->paket->nama_paket ?? '-' }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-gray-50 rounded-lg">
        <div>
            <p class="text-xs text-gray-500 mb-1">Kuota Revisi</p>
            <p class="font-semibold text-gray-900">
                {{ $totalRevisi }} / {{ $kuotaRevisi }} digunakan
            </p>
        </div>
        <div>
            <p class="text-xs text-gray-500 mb-1">Sisa Revisi</p>
            <p class="font-semibold text-primary-600 text-xl">
                {{ $kuotaRevisi - $totalRevisi }}
            </p>
        </div>
        <div>
            <p class="text-xs text-gray-500 mb-1">Status Pesanan</p>
            <span class="badge-info">{{ $pesanan->status }}</span>
        </div>
    </div>
</div>

<!-- Form Revisi -->
<div class="card animate-slide delay-200">
    <form action="{{ route('client.revisi.store', $pesanan) }}" method="POST" enctype="multipart/form-data" id="revisiForm">
        @csrf

        <!-- Catatan Revisi -->
        <div class="mb-6">
            <label for="catatan_revisi" class="block text-sm font-medium text-gray-700 mb-2">
                Catatan Revisi <span class="text-red-500">*</span>
            </label>
            <p class="text-xs text-gray-500 mb-3">
                Jelaskan dengan detail apa yang perlu direvisi. Semakin detail, semakin mudah bagi kami untuk memahami kebutuhan Anda.
            </p>
            <textarea 
                id="catatan_revisi" 
                name="catatan_revisi" 
                rows="6" 
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition @error('catatan_revisi') border-red-500 @enderror"
                placeholder="Contoh: Mohon ubah warna background menjadi biru, ubah font judul menjadi lebih besar, dan tambahkan logo di pojok kanan atas...">{{ old('catatan_revisi') }}</textarea>
            @error('catatan_revisi')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            <div class="flex items-center justify-between mt-2">
                <p class="text-xs text-gray-500">Minimal 10 karakter</p>
                <p class="text-xs text-gray-500"><span id="charCount">0</span> karakter</p>
            </div>
        </div>

        <!-- File Referensi -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                File Referensi (Opsional)
            </label>
            <p class="text-xs text-gray-500 mb-3">
                Upload file referensi untuk memperjelas permintaan revisi Anda. Maksimal 5 file, masing-masing max 10MB.
            </p>
            
            <div class="file-upload-area bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:border-primary-500 transition-all" id="fileUploadArea">
                <input type="file" name="file_referensi[]" id="fileInput" class="hidden" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.zip">
                <div class="flex flex-col items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <p class="text-sm font-medium text-gray-700 mb-1">Klik atau drag & drop file ke sini</p>
                    <p class="text-xs text-gray-500">JPG, PNG, PDF, DOC, DOCX, ZIP (Max 10MB per file)</p>
                </div>
            </div>

            @error('file_referensi.*')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror

            <!-- File Preview -->
            <div id="filePreview" class="mt-4 space-y-2"></div>
        </div>

        <!-- Buttons -->
        <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
            <button type="submit" class="btn-primary flex-1 sm:flex-none px-8 py-3 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                Kirim Permintaan Revisi
            </button>
            <a href="{{ route('client.pesanan.show', $pesanan) }}" class="btn-secondary flex-1 sm:flex-none px-8 py-3 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Batal
            </a>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.body.classList.add('page-load');
        setTimeout(() => {
            document.body.classList.remove('page-load');
        }, 800);

        // Character counter
        const textarea = document.getElementById('catatan_revisi');
        const charCount = document.getElementById('charCount');
        
        textarea.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });

        // File upload handling
        const fileUploadArea = document.getElementById('fileUploadArea');
        const fileInput = document.getElementById('fileInput');
        const filePreview = document.getElementById('filePreview');
        let selectedFiles = [];

        fileUploadArea.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', handleFileSelect);

        // Drag and drop
        fileUploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            fileUploadArea.classList.add('dragover');
        });

        fileUploadArea.addEventListener('dragleave', () => {
            fileUploadArea.classList.remove('dragover');
        });

        fileUploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            fileUploadArea.classList.remove('dragover');
            const files = Array.from(e.dataTransfer.files);
            handleFiles(files);
        });

        function handleFileSelect(e) {
            const files = Array.from(e.target.files);
            handleFiles(files);
        }

        function handleFiles(files) {
            // Limit to 5 files
            if (selectedFiles.length + files.length > 5) {
                alert('Maksimal 5 file dapat diupload');
                return;
            }

            files.forEach(file => {
                // Check file size (10MB)
                if (file.size > 10 * 1024 * 1024) {
                    alert(`File ${file.name} terlalu besar. Maksimal 10MB per file.`);
                    return;
                }

                // Check file type
                const allowedTypes = ['image/jpeg', 'image/png', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip'];
                if (!allowedTypes.includes(file.type)) {
                    alert(`File ${file.name} tidak didukung.`);
                    return;
                }

                selectedFiles.push(file);
                displayFile(file, selectedFiles.length - 1);
            });

            updateFileInput();
        }

        function displayFile(file, index) {
            const fileItem = document.createElement('div');
            fileItem.className = 'file-item flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200';
            
            const fileIcon = getFileIcon(file.type);
            const fileSize = formatFileSize(file.size);

            fileItem.innerHTML = `
                <div class="flex items-center gap-3 flex-1">
                    ${fileIcon}
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">${file.name}</p>
                        <p class="text-xs text-gray-500">${fileSize}</p>
                    </div>
                </div>
                <button type="button" onclick="removeFile(${index})" class="text-red-500 hover:text-red-700 p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            `;

            filePreview.appendChild(fileItem);
        }

        window.removeFile = function(index) {
            selectedFiles.splice(index, 1);
            renderFilePreview();
            updateFileInput();
        };

        function renderFilePreview() {
            filePreview.innerHTML = '';
            selectedFiles.forEach((file, index) => {
                displayFile(file, index);
            });
        }

        function updateFileInput() {
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => dataTransfer.items.add(file));
            fileInput.files = dataTransfer.files;
        }

        function getFileIcon(type) {
            if (type.startsWith('image/')) {
                return `<svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>`;
            } else if (type === 'application/pdf') {
                return `<svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>`;
            } else {
                return `<svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>`;
            }
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }

        // Form validation
        const form = document.getElementById('revisiForm');
        form.addEventListener('submit', function(e) {
            const catatan = textarea.value.trim();
            if (catatan.length < 10) {
                e.preventDefault();
                alert('Catatan revisi minimal 10 karakter');
                textarea.focus();
            }
        });
    });
</script>
@endsection