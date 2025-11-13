@extends('layouts.client')

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

    .card {
        transition: box-shadow 0.3s ease;
    }

    .btn-primary, .btn-secondary, .btn-success {
        transition: all 0.2s ease;
    }

    .btn-primary:hover, .btn-secondary:hover, .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
    }

    .file-item {
        transition: all 0.2s ease;
    }

    .file-item:hover {
        transform: translateX(4px);
        border-color: #3b82f6;
    }

    .link-item {
        transition: all 0.2s ease;
        border-left: 4px solid #3b82f6;
    }

    .link-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
</style>

<div class="mb-6 animate-fade">
    <a href="{{ route('client.revisi.index') }}" class="inline-flex items-center text-primary-600 hover:text-primary-700 mb-4">
        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali ke Daftar Revisi
    </a>
    <h1 class="text-3xl font-bold text-gray-800">Detail Revisi #{{ $revisi->revisi_ke }}</h1>
    <p class="text-gray-600">{{ $revisi->pesanan->kode_pesanan }}</p>
</div>

<!-- Status Timeline -->
<div class="card mb-6 animate-slide delay-100">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Status Revisi</h2>
    
    <div class="relative">
        <!-- Timeline Line -->
        <div class="absolute left-4 top-4 bottom-4 w-0.5 bg-gray-200"></div>
        
        <!-- Timeline Steps -->
        <div class="relative space-y-6">
            <!-- Step 1: Diminta -->
            <div class="flex items-start gap-4">
                <div class="relative z-10 flex items-center justify-center w-8 h-8 rounded-full {{ $revisi->status == 'Diminta' ? 'bg-yellow-500' : 'bg-green-500' }}">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="flex-1 pb-6">
                    <h3 class="font-semibold text-gray-900">Revisi Diminta</h3>
                    <p class="text-sm text-gray-600">{{ $revisi->created_at->format('d M Y H:i') }}</p>
                    @if($revisi->status == 'Diminta')
                    <span class="inline-flex items-center gap-1 mt-2 text-xs px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full">
                        <span class="w-2 h-2 rounded-full bg-yellow-400"></span>
                        Status Saat Ini
                    </span>
                    @endif
                </div>
            </div>

            <!-- Step 2: Sedang Dikerjakan -->
            <div class="flex items-start gap-4">
                <div class="relative z-10 flex items-center justify-center w-8 h-8 rounded-full {{ in_array($revisi->status, ['Sedang Dikerjakan', 'Selesai']) ? ($revisi->status == 'Sedang Dikerjakan' ? 'bg-blue-500' : 'bg-green-500') : 'bg-gray-300' }}">
                    @if(in_array($revisi->status, ['Sedang Dikerjakan', 'Selesai']))
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    @else
                    <span class="w-3 h-3 rounded-full bg-white"></span>
                    @endif
                </div>
                <div class="flex-1 pb-6">
                    <h3 class="font-semibold {{ in_array($revisi->status, ['Sedang Dikerjakan', 'Selesai']) ? 'text-gray-900' : 'text-gray-400' }}">Sedang Dikerjakan</h3>
                    <p class="text-sm text-gray-600">
                        {{ in_array($revisi->status, ['Sedang Dikerjakan', 'Selesai']) ? 'Revisi sedang dikerjakan oleh tim kami' : 'Menunggu...' }}
                    </p>
                    @if($revisi->status == 'Sedang Dikerjakan')
                    <span class="inline-flex items-center gap-1 mt-2 text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-full">
                        <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                        Status Saat Ini
                    </span>
                    @endif
                </div>
            </div>

            <!-- Step 3: Selesai -->
            <div class="flex items-start gap-4">
                <div class="relative z-10 flex items-center justify-center w-8 h-8 rounded-full {{ $revisi->status == 'Selesai' ? 'bg-green-500' : 'bg-gray-300' }}">
                    @if($revisi->status == 'Selesai')
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    @else
                    <span class="w-3 h-3 rounded-full bg-white"></span>
                    @endif
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold {{ $revisi->status == 'Selesai' ? 'text-gray-900' : 'text-gray-400' }}">Revisi Selesai</h3>
                    <p class="text-sm text-gray-600">
                        {{ $revisi->status == 'Selesai' ? $revisi->tanggal_selesai->format('d M Y H:i') : 'Menunggu...' }}
                    </p>
                    @if($revisi->status == 'Selesai')
                    <span class="inline-flex items-center gap-1 mt-2 text-xs px-2 py-1 bg-green-100 text-green-800 rounded-full">
                        <span class="w-2 h-2 rounded-full bg-green-400"></span>
                        Status Saat Ini
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Informasi Pesanan -->
<div class="card mb-6 animate-slide delay-200">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Pesanan</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-xs text-gray-500 mb-1 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Kode Pesanan
            </p>
            <p class="font-semibold text-gray-900">{{ $revisi->pesanan->kode_pesanan }}</p>
        </div>

        <div class="bg-blue-50 p-4 rounded-lg">
            <p class="text-xs text-gray-500 mb-1 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                Layanan
            </p>
            <p class="font-semibold text-gray-900">{{ $revisi->pesanan->layanan->nama_layanan }}</p>
            <p class="text-sm text-gray-600">{{ $revisi->pesanan->paket->nama_paket ?? '-' }}</p>
        </div>

        <div class="bg-purple-50 p-4 rounded-lg">
            <p class="text-xs text-gray-500 mb-1 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Status Pesanan
            </p>
            <span class="badge-info text-sm">{{ $revisi->pesanan->status }}</span>
        </div>
    </div>

    <div class="mt-4 pt-4 border-t border-gray-200">
        <a href="{{ route('client.pesanan.show', $revisi->pesanan) }}" class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            Lihat Detail Pesanan
        </a>
    </div>
</div>

<!-- Catatan Revisi -->
<div class="card mb-6 animate-slide delay-300">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Catatan Revisi Anda</h2>
    <div class="bg-gray-50 p-6 rounded-lg">
        <p class="text-gray-700 whitespace-pre-line">{{ $revisi->catatan_revisi }}</p>
    </div>
</div>

<!-- Catatan Admin (jika ada) -->
@if($revisi->status == 'Selesai' && $revisi->catatan_admin)
<div class="card mb-6 animate-slide delay-300 bg-green-50 border-l-4 border-green-500">
    <div class="flex items-start gap-3">
        <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div class="flex-1">
            <h2 class="text-lg font-bold text-green-800 mb-2">Catatan dari Admin</h2>
            <div class="bg-white p-4 rounded-lg">
                <p class="text-gray-700 whitespace-pre-line">{{ $revisi->catatan_admin }}</p>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Link Hasil Revisi dari Admin -->
@if($revisi->status == 'Selesai' && !empty($revisi->link_hasil))
<div class="card mb-6 animate-slide delay-300 bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200">
    <div class="flex items-center gap-3 mb-4">
        <div class="bg-blue-500 rounded-full p-3">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
            </svg>
        </div>
        <div>
            <h2 class="text-xl font-bold text-gray-800">Link Hasil Revisi</h2>
            <p class="text-sm text-gray-600">Link untuk mengakses hasil revisi dari tim kami</p>
        </div>
    </div>
    
    <div class="space-y-4">
        @if(is_array($revisi->link_hasil))
            @foreach($revisi->link_hasil as $index => $link)
            <div class="link-item bg-white p-4 rounded-lg border border-gray-200 hover:border-blue-300">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <svg class="w-8 h-8 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 text-sm truncate">
                                {{ $link['title'] ?? 'Link Hasil Revisi ' . ($index + 1) }}
                            </p>
                            <p class="text-xs text-gray-500 truncate">{{ $link['url'] }}</p>
                            @if(isset($link['description']))
                            <p class="text-sm text-gray-600 mt-1">{{ $link['description'] }}</p>
                            @endif
                        </div>
                    </div>
                    <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer" 
                       class="text-white bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg transition flex items-center gap-2 whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        Buka Link
                    </a>
                </div>
            </div>
            @endforeach
        @else
            <!-- Fallback untuk link single (string) -->
            <div class="link-item bg-white p-4 rounded-lg border border-gray-200 hover:border-blue-300">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <svg class="w-8 h-8 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 text-sm">Link Hasil Revisi</p>
                            <p class="text-xs text-gray-500 truncate">{{ $revisi->link_hasil }}</p>
                        </div>
                    </div>
                    <a href="{{ $revisi->link_hasil }}" target="_blank" rel="noopener noreferrer" 
                       class="text-white bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg transition flex items-center gap-2 whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        Buka Link
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Safety Warning -->
    <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
        <div class="flex items-start gap-2">
            <svg class="w-5 h-5 text-yellow-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div>
                <p class="text-sm text-yellow-800 font-medium">Keamanan Link</p>
                <p class="text-xs text-yellow-700">
                    Pastikan Anda hanya mengklik link dari sumber yang terpercaya. Hubungi admin jika menemukan link yang mencurigakan.
                </p>
            </div>
        </div>
    </div>
</div>
@endif

<!-- File Referensi dari Client -->
@if($revisi->hasFiles())
<div class="card mb-6 animate-slide delay-300">
    <h2 class="text-xl font-bold text-gray-800 mb-4">File Referensi Anda ({{ $revisi->file_count }})</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($revisi->file_referensi as $index => $file)
        @php
            $filename = basename($file);
            $extension = pathinfo($file, PATHINFO_EXTENSION);
        @endphp
        <div class="file-item flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
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
            <a href="{{ route('client.revisi.download-file', [$revisi, $index]) }}" class="text-primary-600 hover:text-primary-700 p-2 hover:bg-primary-50 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- File Hasil dari Admin -->
@if($revisi->status == 'Selesai' && $revisi->hasFileHasil())
<div class="card mb-6 animate-slide delay-300 bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200">
    <div class="flex items-center gap-3 mb-4">
        <div class="bg-green-500 rounded-full p-3">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <h2 class="text-xl font-bold text-gray-800">File Hasil Revisi ({{ $revisi->file_hasil_count }})</h2>
            <p class="text-sm text-gray-600">File hasil revisi dari tim kami</p>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($revisi->file_hasil as $index => $file)
        @php
            $filename = basename($file);
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            $metadata = $revisi->file_hasil_metadata[$index] ?? null;
        @endphp
        <div class="file-item flex items-center justify-between p-4 bg-white rounded-lg border-2 border-green-200 hover:border-green-400 shadow-sm">
            <div class="flex items-center gap-3 flex-1 min-w-0">
                @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                <svg class="w-8 h-8 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                @elseif($extension === 'pdf')
                <svg class="w-8 h-8 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                @elseif(in_array($extension, ['zip', 'rar', '7z']))
                <svg class="w-8 h-8 text-purple-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                </svg>
                @elseif(in_array($extension, ['mp4', 'mov', 'avi', 'mkv']))
                <svg class="w-8 h-8 text-pink-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                @else
                <svg class="w-8 h-8 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-gray-900 text-sm truncate">
                        {{ $metadata['original_name'] ?? $filename }}
                    </p>
                    <p class="text-xs text-gray-500">
                        {{ strtoupper($extension) }}
                        @if($metadata && isset($metadata['size']))
                        • {{ number_format($metadata['size'] / 1024, 2) }} KB
                        @endif
                    </p>
                </div>
            </div>
            <a href="{{ route('client.revisi.download-hasil', [$revisi, $index]) }}" 
               class="text-white bg-green-500 hover:bg-green-600 p-2 rounded-lg transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
            </a>
        </div>
        @endforeach
    </div>

    <!-- Download All Button -->
    @if($revisi->file_hasil_count > 1)
    <div class="mt-4 pt-4 border-t border-green-200 text-center">
        <p class="text-sm text-gray-600 mb-3">Download semua file hasil revisi sekaligus</p>
        <button onclick="downloadAllFiles()" class="btn-success inline-flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
            </svg>
            Download Semua File ({{ $revisi->file_hasil_count }})
        </button>
    </div>
    @endif
</div>
@endif

<!-- Info Card -->
@if($revisi->status == 'Selesai')
<div class="card bg-green-50 border-l-4 border-green-500 animate-slide delay-300">
    <div class="flex items-start gap-3">
        <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <h4 class="font-semibold text-green-800 mb-1">Revisi Selesai!</h4>
            <p class="text-sm text-green-700">
                Revisi Anda telah selesai dikerjakan. 
                @if(!empty($revisi->link_hasil))
                Silakan akses link hasil revisi di atas atau download file yang tersedia.
                @elseif($revisi->hasFileHasil())
                Silakan download file hasil revisi di atas.
                @endif
            </p>
            <a href="{{ route('client.pesanan.show', $revisi->pesanan) }}" class="inline-flex items-center gap-2 text-green-600 hover:text-green-700 mt-3 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                Kembali ke Detail Pesanan
            </a>
        </div>
    </div>
</div>
@else
<div class="card bg-blue-50 border-l-4 border-blue-500 animate-slide delay-300">
    <div class="flex items-start gap-3">
        <svg class="w-6 h-6 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <h4 class="font-semibold text-blue-800 mb-1">Revisi Sedang Diproses</h4>
            <p class="text-sm text-blue-700">
                Tim kami sedang mengerjakan revisi Anda. Kami akan memberi tahu Anda segera setelah revisi selesai.
            </p>
        </div>
    </div>
</div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.body.classList.add('page-load');
        setTimeout(() => {
            document.body.classList.remove('page-load');
        }, 800);
    });

    // Function to download all files
    function downloadAllFiles() {
        @if($revisi->hasFileHasil())
            @foreach($revisi->file_hasil as $index => $file)
                setTimeout(() => {
                    window.open('{{ route('client.revisi.download-hasil', [$revisi, $index]) }}', '_blank');
                }, {{ $index * 500 }}); // Delay each download by 500ms
            @endforeach
        @endif
    }
</script>
@endsection