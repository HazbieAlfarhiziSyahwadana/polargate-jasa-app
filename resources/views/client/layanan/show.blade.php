@extends('layouts.client')

@section('title', $layanan->nama_layanan)

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

    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
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

    .page-load .animate-slide-left {
        animation: slideInLeft 0.6s ease-out;
        opacity: 0;
        animation-fill-mode: forwards;
    }

    .page-load .animate-slide-right {
        animation: slideInRight 0.6s ease-out;
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

    .card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .paket-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .paket-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
        border-color: #3b82f6;
    }

    .addon-card {
        transition: all 0.3s ease;
    }

    .addon-card:hover {
        background-color: #f3f4f6;
        transform: translateX(4px);
    }

    .related-card {
        transition: all 0.3s ease;
        display: block;
        text-decoration: none;
    }

    .related-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }

    .image-zoom {
        transition: transform 0.5s ease;
        cursor: pointer;
    }

    .image-zoom:hover {
        transform: scale(1.02);
    }

    .btn-primary, .btn-secondary {
        transition: all 0.2s ease;
    }

    .btn-primary:hover, .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
    }

    .info-item {
        transition: all 0.2s ease;
    }

    .info-item:hover {
        transform: translateX(4px);
    }

    .back-button {
        transition: all 0.2s ease;
    }

    .back-button:hover {
        transform: translateX(-4px);
        color: #1e40af;
    }

    .sidebar-sticky-container {
        position: sticky;
        top: 20px;
        align-self: flex-start;
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

    /* Media Display */
    .media-display {
        position: relative;
        cursor: pointer;
        overflow: hidden;
        border-radius: 12px;
        width: 100%;
        height: 100%;
    }

    .media-display:hover .play-overlay {
        opacity: 1;
    }

    .play-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 80px;
        height: 80px;
        background: rgba(220, 38, 38, 0.9);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.3s ease;
        border: 3px solid white;
        z-index: 5;
    }

    .media-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
        z-index: 5;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .video-badge {
        background: linear-gradient(135deg, rgba(220, 38, 38, 0.95), rgba(239, 68, 68, 0.95));
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .sidebar-sticky-container {
            position: relative;
            top: 0;
        }
    }

    @media (max-width: 768px) {
        .choice-buttons {
            grid-template-columns: 1fr;
        }

        .paket-card:hover {
            transform: none;
        }

        .related-card:hover {
            transform: none;
        }
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

<!-- Back Button -->
<div class="mb-6 animate-fade">
    <a href="{{ route('client.layanan.index') }}" class="back-button text-primary-600 hover:text-primary-700 text-sm font-medium inline-flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Daftar Layanan
    </a>
</div>

<!-- Main Grid Layout -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Detail Layanan (Kiri) -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Media & Info Utama -->
        <div class="card animate-slide-left">
            @php
                $hasImage = !empty($layanan->gambar);
                $hasVideo = !empty($layanan->video_url);
            @endphp
            
            <div class="overflow-hidden rounded-lg mb-4 bg-gray-50 relative" style="height: 500px;">
                @if($hasImage || $hasVideo)
                    <div class="media-display"
                         data-title="{{ $layanan->nama_layanan }}"
                         data-has-image="{{ $hasImage ? 'true' : 'false' }}"
                         data-has-video="{{ $hasVideo ? 'true' : 'false' }}"
                         data-image-url="{{ $hasImage ? $layanan->gambar_url : '' }}"
                         data-video-url="{{ $hasVideo ? $layanan->video_url : '' }}">
                        
                        @if($hasImage)
                            <img src="{{ $layanan->gambar_url }}" 
                                 alt="{{ $layanan->nama_layanan }}" 
                                 class="w-full h-full object-cover rounded-lg bg-gray-100 border border-gray-200 image-zoom">
                        @else
                            <div class="w-full h-full rounded-lg bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center">
                                <svg class="w-24 h-24 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                        @endif
                        
                        @if($hasVideo)
                            <div class="play-overlay">
                                <svg class="w-10 h-10 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                            <div class="media-badge video-badge">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                                <span>{{ $hasImage ? 'Gambar + Video' : 'Video' }}</span>
                            </div>
                        @elseif($hasImage)
                            <div class="media-badge">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>Gambar</span>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="w-full h-full bg-gray-200 rounded-lg flex items-center justify-center">
                        <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif
            </div>
            
            <span class="badge-info mb-3 inline-block">{{ $layanan->kategori }}</span>
            <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-800 mb-4">{{ $layanan->nama_layanan }}</h1>
            <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $layanan->deskripsi }}</p>
        </div>

        <!-- Paket -->
        @if($layanan->paket->count() > 0)
        <div class="card animate-slide-left delay-100">
            <div class="flex items-center gap-3 mb-6">
                <div class="bg-primary-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800">Pilih Paket</h2>
                    <p class="text-sm text-gray-600">Pilih paket yang sesuai dengan kebutuhan Anda</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($layanan->paket as $index => $paket)
                <div class="paket-card border-2 border-gray-200 rounded-lg p-4 md:p-5 {{ $index === 0 ? 'border-primary-500 bg-primary-50' : '' }}">
                    @if($index === 0)
                    <span class="inline-block bg-primary-600 text-white text-xs font-bold px-3 py-1 rounded-full mb-3">
                        POPULER
                    </span>
                    @endif
                    
                    <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-2">{{ $paket->nama_paket }}</h3>
                    <p class="text-2xl md:text-3xl font-bold text-primary-600 mb-3">
                        Rp {{ number_format($paket->harga, 0, ',', '.') }}
                    </p>
                    <p class="text-sm text-gray-600 mb-4">{{ $paket->deskripsi }}</p>
                    
                    <div class="mb-4">
                        <p class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Fitur yang Anda dapatkan:
                        </p>
                        <ul class="space-y-2">
                            @php
                                $fiturArray = is_string($paket->fitur) ? json_decode($paket->fitur, true) : $paket->fitur;
                            @endphp
                            @if($fiturArray && is_array($fiturArray))
                                @foreach($fiturArray as $fitur)
                                <li class="text-sm text-gray-700 flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>{{ $fitur }}</span>
                                </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>

                    <div class="flex items-center justify-between text-sm text-gray-600 mb-4 pb-4 border-b border-gray-200">
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="font-medium">{{ $paket->durasi_pengerjaan }} hari</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span class="font-medium">{{ $paket->jumlah_revisi }}x revisi</span>
                        </div>
                    </div>

                    <a href="{{ route('client.pesanan.create', $layanan) }}?paket={{ $paket->id }}" 
                       class="btn-primary w-full flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Pilih Paket Ini
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Add-ons -->
        @if($layanan->addons->count() > 0)
        <div class="card animate-slide-left delay-200">
            <div class="flex items-center gap-3 mb-6">
                <div class="bg-purple-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800">Add-ons Tersedia</h2>
                    <p class="text-sm text-gray-600">Tingkatkan layanan Anda dengan add-ons tambahan</p>
                </div>
            </div>
            
            <div class="space-y-3">
                @foreach($layanan->addons as $addon)
                <div class="addon-card bg-gray-50 p-4 rounded-lg flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 border border-gray-100">
                    <div class="flex items-start gap-3 flex-1">
                        <div class="bg-white rounded-lg p-2 border border-gray-200">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-1">{{ $addon->nama_addon }}</h4>
                            <p class="text-sm text-gray-600">{{ $addon->deskripsi }}</p>
                        </div>
                    </div>
                    <div class="text-right sm:ml-4">
                        <p class="font-bold text-purple-600 text-lg whitespace-nowrap">+Rp {{ number_format($addon->harga, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Layanan Terkait -->
        @if(isset($layananTerkait) && $layananTerkait->count() > 0)
        <div class="card animate-slide-left delay-300">
            <div class="flex items-center gap-3 mb-6">
                <div class="bg-blue-100 rounded-lg p-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800">Layanan Terkait</h2>
                    <p class="text-sm text-gray-600">Mungkin Anda juga tertarik dengan layanan ini</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($layananTerkait as $item)
                @php
                    $itemHasImage = !empty($item->gambar);
                    $itemHasVideo = !empty($item->video_url);
                @endphp
                <a href="{{ route('client.layanan.show', $item) }}" class="related-card bg-white border border-gray-200 rounded-lg overflow-hidden">
                    <div class="overflow-hidden bg-gray-50 relative" style="height: 200px;">
                        @if($itemHasImage)
                            <img src="{{ $item->gambar_url }}" 
                                 alt="{{ $item->nama_layanan }}" 
                                 class="w-full h-full object-cover">
                            @if($itemHasVideo)
                                <div class="media-badge video-badge text-xs">Video</div>
                            @endif
                        @elseif($itemHasVideo)
                            <div class="w-full h-full bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center">
                                <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                            <div class="media-badge video-badge text-xs">Video</div>
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <div class="p-4">
                        <span class="badge-info text-xs mb-2 inline-block">{{ $item->kategori }}</span>
                        <h4 class="font-semibold text-gray-800 mb-1 line-clamp-1">{{ $item->nama_layanan }}</h4>
                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ Str::limit($item->deskripsi, 80) }}</p>
                        <p class="text-primary-600 font-semibold">Mulai Rp {{ number_format($item->harga_mulai, 0, ',', '.') }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar (Kanan) - STICKY -->
    <div class="lg:col-span-1">
        <div class="sidebar-sticky-container space-y-6">
            <!-- Quick Info -->
            <div class="card bg-primary-50 border border-primary-200 animate-slide-right">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-primary-600 rounded-lg p-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-800">Informasi Layanan</h3>
                </div>
                
                <div class="space-y-3 text-sm">
                    <div class="info-item flex items-start gap-3">
                        <svg class="w-5 h-5 text-primary-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-gray-700">Garansi kepuasan pelanggan</span>
                    </div>
                    <div class="info-item flex items-start gap-3">
                        <svg class="w-5 h-5 text-primary-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-gray-700">Pengerjaan tepat waktu</span>
                    </div>
                    <div class="info-item flex items-start gap-3">
                        <svg class="w-5 h-5 text-primary-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span class="text-gray-700">Revisi sesuai paket</span>
                    </div>
                    <div class="info-item flex items-start gap-3">
                        <svg class="w-5 h-5 text-primary-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span class="text-gray-700">Tim profesional berpengalaman</span>
                    </div>
                </div>
            </div>

            <!-- Harga Mulai -->
            <div class="card bg-gradient-to-br from-primary-500 to-primary-600 text-white animate-slide-right delay-100">
                <div class="text-center">
                    <p class="text-primary-100 text-sm mb-2">Harga Mulai Dari</p>
                    <p class="text-3xl md:text-4xl font-bold mb-4">Rp {{ number_format($layanan->harga_mulai, 0, ',', '.') }}</p>
                    <a href="{{ route('client.pesanan.create', $layanan) }}" 
                       class="btn-secondary w-full bg-white text-primary-600 hover:bg-gray-100 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        Pesan Sekarang
                    </a>
                </div>
            </div>

            <!-- Butuh Bantuan -->
            <div class="card animate-slide-right delay-200">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-green-100 rounded-lg p-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-800">Butuh Bantuan?</h3>
                </div>
                
                <p class="text-sm text-gray-600 mb-4">Hubungi tim kami untuk konsultasi gratis</p>
                
                <div class="space-y-3">
                    <a href="https://wa.me/6281234567890" 
                       target="_blank" 
                       class="flex items-center text-sm text-gray-700 hover:text-green-600 p-3 bg-gray-50 rounded-lg hover:bg-green-50 transition-all">
                        <svg class="w-5 h-5 mr-3 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        <div>
                            <p class="font-medium">WhatsApp</p>
                            <p class="text-xs text-gray-500">0812-3456-7890</p>
                        </div>
                    </a>
                    
                    <a href="mailto:info@polargate.com" 
                       class="flex items-center text-sm text-gray-700 hover:text-red-600 p-3 bg-gray-50 rounded-lg hover:bg-red-50 transition-all">
                        <svg class="w-5 h-5 mr-3 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <div>
                            <p class="font-medium">Email</p>
                            <p class="text-xs text-gray-500">info@polargate.com</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const isFirstLoad = !sessionStorage.getItem('layanan_show_loaded');
    
    if (isFirstLoad) {
        document.body.classList.add('page-load');
        sessionStorage.setItem('layanan_show_loaded', 'true');
        
        setTimeout(() => {
            document.body.classList.remove('page-load');
        }, 1000);
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
                showChoiceModal(title, imageUrl, videoUrl);
            } else if (hasVideo) {
                openMediaModal(title, 'video', videoUrl);
            } else if (hasImage) {
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

// Show choice modal
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

// Open media modal
function openMediaModal(title, type, url) {
    console.log('Opening modal:', { title, type, url });
    
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
            <img src="${url}" alt="${title}" class="media-preview">
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

// Get YouTube ID
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
        sessionStorage.removeItem('layanan_show_loaded');
    }
});
</script>
@endsection