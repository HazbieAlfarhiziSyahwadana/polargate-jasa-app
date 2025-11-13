@extends('layouts.admin')

@section('title', 'Detail Revisi')

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

    .btn-primary, .btn-info, .btn-success, .btn-danger {
        transition: all 0.2s ease;
    }

    .btn-primary:hover, .btn-info:hover, .btn-success:hover, .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
    }

    .link-preview-card {
        transition: all 0.3s ease;
        border: 2px solid #e5e7eb;
    }

    .link-preview-card:hover {
        border-color: #3b82f6;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }

    .copy-btn:hover {
        background-color: #f3f4f6;
    }
</style>

<div class="mb-6 animate-fade">
    <a href="{{ route('admin.revisi.index') }}" class="inline-flex items-center text-primary-600 hover:text-primary-700 mb-4">
        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali ke Daftar Revisi
    </a>
    <h1 class="text-3xl font-bold text-gray-800">Detail Revisi #{{ $revisi->revisi_ke }}</h1>
    <p class="text-gray-600">{{ optional($revisi->pesanan)->kode_pesanan ?? 'N/A' }}</p>
</div>

<!-- Status Card -->
<div class="card mb-6 animate-slide delay-100">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold text-gray-800">Status Revisi</h2>
        @php
            $statusClass = 'badge-warning';
            if($revisi->status == 'Selesai') $statusClass = 'badge-success';
            if($revisi->status == 'Sedang Dikerjakan') $statusClass = 'badge-info';
        @endphp
        <span class="{{ $statusClass }} text-base">{{ $revisi->status }}</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-xs text-gray-500 mb-1">Tanggal Diminta</p>
            <p class="font-semibold text-gray-900">{{ $revisi->created_at->format('d M Y H:i') }}</p>
        </div>
        <div class="bg-purple-50 p-4 rounded-lg">
            <p class="text-xs text-gray-500 mb-1">Revisi Ke</p>
            <p class="font-semibold text-gray-900 text-xl">#{{ $revisi->revisi_ke }}</p>
        </div>
        <div class="bg-blue-50 p-4 rounded-lg">
            <p class="text-xs text-gray-500 mb-1">Tanggal Selesai</p>
            <p class="font-semibold text-gray-900">{{ $revisi->formatted_tanggal_selesai }}</p>
        </div>
    </div>
</div>

<!-- Informasi Pesanan -->
<div class="card mb-6 animate-slide delay-200">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Pesanan</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <div class="space-y-4">
                <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                    <svg class="w-5 h-5 text-primary-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <div>
                        <p class="text-xs text-gray-500">Kode Pesanan</p>
                        <p class="font-semibold text-gray-900">{{ optional($revisi->pesanan)->kode_pesanan ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                    <svg class="w-5 h-5 text-primary-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <div>
                        <p class="text-xs text-gray-500">Pelanggan</p>
                        <p class="font-semibold text-gray-900">{{ optional($revisi->pesanan)->client?->name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600">{{ optional($revisi->pesanan)->client?->email ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="space-y-4">
                <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                    <svg class="w-5 h-5 text-primary-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <div>
                        <p class="text-xs text-gray-500">Layanan</p>
                        <p class="font-semibold text-gray-900">{{ optional($revisi->pesanan)->layanan?->nama_layanan ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600">{{ optional($revisi->pesanan)->paket?->nama_paket ?? '-' }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                    <svg class="w-5 h-5 text-primary-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-xs text-gray-500">Status Pesanan</p>
                        <span class="badge-info text-sm">{{ optional($revisi->pesanan)->status ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($revisi->pesanan)
    <div class="mt-4 pt-4 border-t border-gray-200">
        <a href="{{ route('admin.pesanan.show', $revisi->pesanan) }}" class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            Lihat Detail Pesanan
        </a>
    </div>
    @endif
</div>

<!-- Catatan Revisi dari Client -->
<div class="card mb-6 animate-slide delay-300">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Catatan Revisi dari Client</h2>
    <div class="bg-gray-50 p-6 rounded-lg">
        <p class="text-gray-700 whitespace-pre-line">{{ $revisi->catatan_revisi ?? 'Tidak ada catatan' }}</p>
    </div>
</div>

<!-- File Referensi dari Client -->
@if($revisi->hasFiles())
<div class="card mb-6 animate-slide delay-300">
    <h2 class="text-xl font-bold text-gray-800 mb-4">File Referensi dari Client ({{ $revisi->file_count }})</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($revisi->file_referensi as $index => $file)
        @php
            $filename = basename($file);
            $extension = pathinfo($file, PATHINFO_EXTENSION);
        @endphp
        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200 hover:border-primary-300 transition">
            <div class="flex items-center gap-3 flex-1 min-w-0">
                @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                <svg class="w-8 h-8 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                @elseif($extension === 'pdf')
                <svg class="w-8 h-8 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                @else
                <svg class="w-8 h-8 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-gray-900 text-sm truncate">{{ $filename }}</p>
                    <p class="text-xs text-gray-500 uppercase">{{ $extension }}</p>
                </div>
            </div>
            <a href="{{ route('admin.revisi.download-file', [$revisi, $index]) }}" class="text-primary-600 hover:text-primary-700 p-2 hover:bg-primary-50 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Link Preview Hasil Revisi (Admin Input) -->
<div class="card mb-6 animate-slide delay-400">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold text-gray-800">Link Preview Hasil Revisi</h2>
        @if($revisi->hasLinkPreview())
        <span class="badge-success text-sm">✓ Link Tersedia</span>
        @else
        <span class="badge-warning text-sm">⚠ Belum Ada Link</span>
        @endif
    </div>

    @if(!$revisi->hasLinkPreview() || $revisi->status != 'Selesai')
    <!-- Form Upload Link Preview -->
    <form action="{{ route('admin.revisi.upload-link', $revisi) }}" method="POST" class="space-y-4">
        @csrf
        @method('PATCH')
        
        <div>
            <label for="link_preview" class="block text-sm font-medium text-gray-700 mb-2">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                    Link Preview (Google Drive, Dropbox, Figma, dll)
                </span>
            </label>
            <input type="url" 
                   name="link_preview" 
                   id="link_preview" 
                   value="{{ old('link_preview', $revisi->catatan_admin) }}"
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition"
                   placeholder="https://drive.google.com/file/d/..."
                   required>
            <p class="text-xs text-gray-500 mt-2">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Masukkan link file preview dari Google Drive, Dropbox, Figma, atau platform lainnya
            </p>
        </div>

        <div>
            <label for="catatan_hasil" class="block text-sm font-medium text-gray-700 mb-2">
                Catatan untuk Client (Opsional)
            </label>
            <textarea name="catatan_hasil" 
                      id="catatan_hasil" 
                      rows="4"
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition"
                      placeholder="Contoh: Hasil revisi sudah sesuai permintaan. Mohon dicek dan berikan feedback.">{{ old('catatan_hasil', $revisi->getCatatanHasil()) }}</textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="btn-primary flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                {{ $revisi->hasLinkPreview() ? 'Update Link Preview' : 'Simpan Link Preview' }}
            </button>
        </div>
    </form>
    @else
    <!-- Display Link Preview -->
    <div class="space-y-4">
        <div class="link-preview-card bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-lg">
            <div class="flex items-start gap-4">
                <div class="bg-white p-3 rounded-lg shadow-sm">
                    <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-700 mb-2">Link Preview:</p>
                    <div class="flex items-center gap-2 bg-white p-3 rounded-lg shadow-sm">
                        <a href="{{ $revisi->catatan_admin }}" 
                           target="_blank" 
                           class="text-primary-600 hover:text-primary-700 flex-1 truncate font-medium">
                            {{ $revisi->catatan_admin }}
                        </a>
                        <button onclick="copyLink('{{ $revisi->catatan_admin }}')" 
                                class="copy-btn p-2 rounded-lg transition flex-shrink-0"
                                title="Copy link"
                                id="copyBtn">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </button>
                        <a href="{{ $revisi->catatan_admin }}" 
                           target="_blank"
                           class="copy-btn p-2 rounded-lg transition flex-shrink-0"
                           title="Buka link">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if($revisi->getCatatanHasil())
        <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500">
            <p class="text-sm font-medium text-blue-800 mb-2">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Catatan untuk Client:
            </p>
            <p class="text-sm text-blue-700">{{ $revisi->getCatatanHasil() }}</p>
        </div>
        @endif

        <!-- Edit Link Button -->
        <button onclick="showEditForm()" id="editBtn" class="btn-info text-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit Link Preview
        </button>

        <!-- Hidden Edit Form -->
        <div id="editLinkForm" style="display: none;">
            <form action="{{ route('admin.revisi.upload-link', $revisi) }}" method="POST" class="space-y-4 mt-4 p-4 bg-gray-50 rounded-lg border-2 border-primary-200">
                @csrf
                @method('PATCH')
                
                <div>
                    <label for="link_preview_edit" class="block text-sm font-medium text-gray-700 mb-2">
                        Update Link Preview
                    </label>
                    <input type="url" 
                           name="link_preview" 
                           id="link_preview_edit" 
                           value="{{ $revisi->catatan_admin }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition"
                           required>
                </div>

                <div>
                    <label for="catatan_hasil_edit" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan untuk Client (Opsional)
                    </label>
                    <textarea name="catatan_hasil" 
                              id="catatan_hasil_edit" 
                              rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition"
                              placeholder="Tambahkan catatan...">{{ $revisi->getCatatanHasil() }}</textarea>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="btn-primary text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Link
                    </button>
                    <button type="button" onclick="hideEditForm()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>

<!-- Update Status -->
<div class="card animate-slide delay-400">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Update Status Revisi</h2>
    
    @if($revisi->status != 'Selesai')
    <form action="{{ route('admin.revisi.update-status', $revisi) }}" method="POST" class="space-y-4">
        @csrf
        @method('PATCH')
        
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                Status Revisi
            </label>
            <select name="status" id="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition" required>
                <option value="Diminta" {{ $revisi->status == 'Diminta' ? 'selected' : '' }}>Diminta</option>
                <option value="Sedang Dikerjakan" {{ $revisi->status == 'Sedang Dikerjakan' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                <option value="Selesai" {{ $revisi->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
            @if(!$revisi->hasLinkPreview())
            <p class="text-xs text-red-600 mt-2 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                ⚠ Upload link preview terlebih dahulu sebelum mengubah status ke "Selesai"
            </p>
            @else
            <p class="text-xs text-green-600 mt-2 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                ✓ Link preview sudah tersedia
                        </p>
            @endif
        </div>

        <div id="selesaiFields" style="{{ $revisi->status == 'Selesai' ? 'display: block;' : 'display: none;' }}">
            <div>
                <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-2">
                    Tanggal Penyelesaian
                </label>
                <input type="datetime-local" 
                       name="tanggal_selesai" 
                       id="tanggal_selesai"
                       value="{{ old('tanggal_selesai', $revisi->tanggal_selesai ? $revisi->tanggal_selesai->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition">
            </div>

            <div>
                <label for="catatan_admin" class="block text-sm font-medium text-gray-700 mb-2">
                    Catatan Internal (Opsional)
                </label>
                <textarea name="catatan_admin" 
                          id="catatan_admin" 
                          rows="3"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition"
                          placeholder="Catatan internal untuk tim...">{{ old('catatan_admin', $revisi->catatan_admin) }}</textarea>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="btn-primary flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Update Status
            </button>
            
            @if($revisi->status == 'Selesai')
            <a href="{{ route('admin.revisi.index') }}" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Kembali
            </a>
            @endif
        </div>
    </form>
    @else
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
        <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <h3 class="text-lg font-semibold text-green-800">Revisi Telah Selesai</h3>
                <p class="text-green-700">
                    Revisi ini telah diselesaikan pada {{ $revisi->formatted_tanggal_selesai }}
                </p>
                @if($revisi->catatan_admin)
                <div class="mt-2 p-3 bg-white rounded-lg border border-green-200">
                    <p class="text-sm font-medium text-gray-700 mb-1">Catatan Internal:</p>
                    <p class="text-sm text-gray-600">{{ $revisi->catatan_admin }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Notifikasi untuk Client -->
@if($revisi->status == 'Selesai' && $revisi->hasLinkPreview())
<div class="card mt-6 bg-blue-50 border-2 border-blue-200 animate-slide delay-500">
    <div class="flex items-center gap-3 mb-3">
        <svg class="w-6 h-6 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
        </svg>
        <h3 class="text-lg font-bold text-gray-800">Notifikasi untuk Client</h3>
    </div>
    
    <p class="text-sm text-gray-700 mb-4">
        Kirim notifikasi kepada client bahwa hasil revisi sudah siap untuk dilihat.
    </p>
    
    <form action="{{ route('admin.revisi.send-notification', $revisi) }}" method="POST" class="space-y-3">
        @csrf
        
        <div>
            <label for="notification_message" class="block text-sm font-medium text-gray-700 mb-2">
                Pesan Notifikasi (Opsional)
            </label>
            <textarea name="message" 
                      id="notification_message" 
                      rows="3"
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                      placeholder="Halo, hasil revisi ke-{{ $revisi->revisi_ke }} sudah siap. Silakan cek link preview yang telah kami sediakan...">{{ old('message') }}</textarea>
            <p class="text-xs text-gray-500 mt-1">
                Kosongkan untuk menggunakan pesan default
            </p>
        </div>
        
        <button type="submit" class="btn-info flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
            </svg>
            Kirim Notifikasi ke Client
        </button>
    </form>
</div>
@endif

<!-- Riwayat Status -->
<div class="card mt-6 animate-slide delay-600">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Riwayat Status</h2>
    
    <div class="space-y-4">
        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
            <div class="flex items-center gap-3">
                <div class="bg-green-100 rounded-full p-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Revisi Diminta</p>
                    <p class="text-sm text-gray-600">{{ $revisi->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>
            <span class="badge-warning">Diminta</span>
        </div>

        @if($revisi->status_history && count($revisi->status_history) > 0)
            @foreach($revisi->status_history as $history)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-100 rounded-full p-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Status Diperbarui</p>
                        <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($history['timestamp'])->format('d M Y H:i') }}</p>
                        <p class="text-xs text-gray-500">Oleh: {{ $history['user'] ?? 'System' }}</p>
                    </div>
                </div>
                <span class="badge-info">{{ $history['status'] }}</span>
            </div>
            @endforeach
        @endif

        @if($revisi->status == 'Selesai')
        <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg border-l-4 border-green-500">
            <div class="flex items-center gap-3">
                <div class="bg-green-100 rounded-full p-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Revisi Selesai</p>
                    <p class="text-sm text-gray-600">{{ $revisi->formatted_tanggal_selesai }}</p>
                </div>
            </div>
            <span class="badge-success">Selesai</span>
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animasi page load
    const isFirstLoad = !sessionStorage.getItem('revisi_detail_loaded');
    if (isFirstLoad) {
        document.body.classList.add('page-load');
        sessionStorage.setItem('revisi_detail_loaded', 'true');
        setTimeout(() => {
            document.body.classList.remove('page-load');
        }, 1000);
    }

    // Toggle form edit status selesai
    const statusSelect = document.getElementById('status');
    const selesaiFields = document.getElementById('selesaiFields');
    
    if (statusSelect && selesaiFields) {
        statusSelect.addEventListener('change', function() {
            if (this.value === 'Selesai') {
                selesaiFields.style.display = 'block';
                // Validasi link preview sebelum mengizinkan status selesai
                if (!{{ $revisi->hasLinkPreview() ? 'true' : 'false' }}) {
                    alert('⚠ Harap upload link preview terlebih dahulu sebelum mengubah status ke "Selesai"');
                    this.value = '{{ $revisi->status }}';
                    selesaiFields.style.display = 'none';
                }
            } else {
                selesaiFields.style.display = 'none';
            }
        });
    }

    // Handle form submission validation
    const statusForm = document.querySelector('form[action*="update-status"]');
    if (statusForm) {
        statusForm.addEventListener('submit', function(e) {
            const status = document.getElementById('status').value;
            const hasLinkPreview = {{ $revisi->hasLinkPreview() ? 'true' : 'false' }};
            
            if (status === 'Selesai' && !hasLinkPreview) {
                e.preventDefault();
                alert('⚠ Tidak dapat menyelesaikan revisi tanpa link preview. Harap upload link preview terlebih dahulu.');
                return false;
            }
        });
    }
});

// Copy link functionality
function copyLink(link) {
    navigator.clipboard.writeText(link).then(function() {
        const copyBtn = document.getElementById('copyBtn');
        const originalHTML = copyBtn.innerHTML;
        
        copyBtn.innerHTML = `
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        `;
        
        setTimeout(() => {
            copyBtn.innerHTML = originalHTML;
        }, 2000);
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
        alert('Gagal menyalin link');
    });
}

// Show/hide edit form
function showEditForm() {
    document.getElementById('editLinkForm').style.display = 'block';
    document.getElementById('editBtn').style.display = 'none';
}

function hideEditForm() {
    document.getElementById('editLinkForm').style.display = 'none';
    document.getElementById('editBtn').style.display = 'block';
}

// Auto-expand textareas
document.querySelectorAll('textarea').forEach(textarea => {
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
});

// Preview link validation
document.addEventListener('DOMContentLoaded', function() {
    const linkInputs = document.querySelectorAll('input[type="url"]');
    linkInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value && !isValidUrl(this.value)) {
                this.style.borderColor = '#ef4444';
                this.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.1)';
            } else {
                this.style.borderColor = '#d1d5db';
                this.style.boxShadow = 'none';
            }
        });
    });
});

function isValidUrl(string) {
    try {
        new URL(string);
        return true;
    } catch (_) {
        return false;
    }
}

// Handle notification form submission
document.addEventListener('DOMContentLoaded', function() {
    const notificationForm = document.querySelector('form[action*="send-notification"]');
    if (notificationForm) {
        notificationForm.addEventListener('submit', function(e) {
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;
            
            button.innerHTML = `
                <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Mengirim...
            `;
            button.disabled = true;
            
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 3000);
        });
    }
});
</script>

<style>
.badge-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.badge-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.badge-info {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.btn-primary {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    padding: 12px 24px;
    border-radius: 10px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-info {
    background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    padding: 12px 24px;
    border-radius: 10px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Responsive design */
@media (max-width: 768px) {
    .card {
        padding: 20px;
    }
    
    .grid-cols-1 {
        grid-template-columns: 1fr;
    }
    
    .md\:grid-cols-2 {
        grid-template-columns: 1fr;
    }
    
    .md\:grid-cols-3 {
        grid-template-columns: 1fr;
    }
    
    .flex-col-mobile {
        flex-direction: column;
        gap: 10px;
    }
}
</style>
@endsection