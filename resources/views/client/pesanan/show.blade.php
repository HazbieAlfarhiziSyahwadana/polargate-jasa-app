@extends('layouts.client')

@section('title', 'Detail Pesanan')

@section('content')
<style>
     .animate-fade {
        animation: fadeIn 0.5s ease-in;
    }
    
    .animate-slide-left {
        animation: slideLeft 0.6s ease-out;
    }
    
    .animate-slide-right {
        animation: slideRight 0.6s ease-out;
    }
    
    .animate-slide {
        animation: slideUp 0.3s ease-out;
    }
    
    .delay-100 {
        animation-delay: 0.1s;
        opacity: 0;
        animation-fill-mode: forwards;
    }
    
    .delay-200 {
        animation-delay: 0.2s;
        opacity: 0;
        animation-fill-mode: forwards;
    }
    
    .delay-300 {
        animation-delay: 0.3s;
        opacity: 0;
        animation-fill-mode: forwards;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes slideLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slideRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .card {
        background: white;
        border-radius: 16px;
        padding: 28px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
    }
    
    .card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    }
    
    .sidebar-card {
        position: sticky;
        top: 24px;
    }
    
    .back-button {
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 8px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }
    
    .back-button:hover {
        transform: translateX(-4px);
        background: #f1f5f9;
        border-color: #cbd5e1;
    }
    
    .badge-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 8px 20px;
        border-radius: 24px;
        font-size: 14px;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    }
    
    .badge-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        padding: 8px 20px;
        border-radius: 24px;
        font-size: 14px;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
    }
    
    .badge-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        padding: 8px 20px;
        border-radius: 24px;
        font-size: 14px;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
    }
    
    .badge-info {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        padding: 8px 20px;
        border-radius: 24px;
        font-size: 14px;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
    }
    
    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    }
    
    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
    }
    
    .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
    }
    
    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
    }
    
    .btn-secondary {
        background: #6b7280;
        color: white;
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .timeline-item {
        position: relative;
        padding-left: 0;
    }
    
    .timeline-item:not(:last-child)::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 32px;
        width: 2px;
        height: calc(100% + 8px);
        background: #e5e7eb;
    }
    
    .invoice-item {
        transition: all 0.2s ease;
    }
    
    .invoice-item:hover {
        transform: translateX(4px);
        border-color: #3b82f6;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.15);
    }

    /* Styling untuk Detail Pesanan yang Lebih Baik */
    .detail-pesanan-box {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 24px;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        min-height: 120px;
        position: relative;
    }

    .detail-pesanan-text {
        font-size: 15px;
        line-height: 1.8;
        color: #1f2937;
        white-space: pre-wrap;
        word-wrap: break-word;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    .detail-pesanan-text p {
        margin-bottom: 12px;
    }

    .detail-pesanan-text:last-child {
        margin-bottom: 0;
    }

    /* Empty state */
    .detail-empty {
        color: #9ca3af;
        font-style: italic;
        text-align: center;
        padding: 40px 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
    }

    .detail-empty svg {
        width: 48px;
        height: 48px;
        opacity: 0.5;
    }

    /* Info items styling */
    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 18px;
        background: #f9fafb;
        border-radius: 10px;
        transition: all 0.2s ease;
        border: 1px solid #f3f4f6;
    }

    .info-item:hover {
        background: #f3f4f6;
        border-color: #e5e7eb;
    }

    .info-item-highlight {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border: 1px solid #bfdbfe;
    }

    .info-item-total {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        padding: 16px 20px;
        border: none;
    }

    .card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f1f5f9;
    }

    .card-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .card-title {
        font-size: 20px;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }
</style>

<div class="mb-6 animate-fade">
    <a href="{{ route('client.pesanan.index') }}" class="back-button text-primary-600 hover:text-primary-700 text-sm mb-4 inline-flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Daftar Pesanan
    </a>
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ $pesanan->kode_pesanan }}</h1>
            <p class="text-gray-600">Detail pesanan Anda</p>
        </div>
        @php
            $statusClass = 'badge-info';
            if($pesanan->status == 'Selesai') $statusClass = 'badge-success';
            if(in_array($pesanan->status, ['Menunggu Pembayaran DP', 'Menunggu Pelunasan'])) $statusClass = 'badge-warning';
            if($pesanan->status == 'Dibatalkan') $statusClass = 'badge-danger';

            $dpInvoice = $pesanan->invoices->firstWhere('tipe', 'DP');
            $dpLunas = $dpInvoice && $dpInvoice->status === 'Lunas';

            $pelunasanInvoice = $pesanan->invoices->firstWhere('tipe', 'Pelunasan');
            $pelunasanLunas = $pelunasanInvoice && $pelunasanInvoice->status === 'Lunas';

            $previewExpiry = $pesanan->preview_expired_at ? \Carbon\Carbon::parse($pesanan->preview_expired_at) : null;
            $previewAktif = $previewExpiry && $previewExpiry->isFuture() && $pesanan->is_preview_active;
        @endphp
        <span class="{{ $statusClass }}">{{ $pesanan->status }}</span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Detail Pesanan -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Info Layanan -->
        <div class="card animate-slide-left">
            <div class="flex items-center gap-3 mb-4">
                <div class="bg-primary-100 rounded-lg p-2">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-800">Informasi Layanan</h2>
            </div>
            
            <div class="space-y-3">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-gray-600 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Layanan:
                    </span>
                    <span class="font-semibold text-gray-900">{{ $pesanan->layanan->nama_layanan }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-gray-600 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Paket:
                    </span>
                    <span class="font-semibold text-gray-900">{{ $pesanan->paket->nama_paket ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-primary-50 rounded-lg">
                    <span class="text-gray-600 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Harga Paket:
                    </span>
                    <span class="font-semibold text-gray-900">Rp {{ number_format($pesanan->harga_paket, 0, ',', '.') }}</span>
                </div>
                
                @if($pesanan->addons->count() > 0)
                <div class="pt-3 border-t">
                    <p class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add-ons:
                    </p>
                    @foreach($pesanan->addons as $addon)
                    <div class="flex justify-between text-sm mb-2 p-2 bg-purple-50 rounded">
                        <span class="text-gray-600">• {{ $addon->nama_addon }}</span>
                        <span class="text-gray-900 font-semibold">Rp {{ number_format($addon->pivot->harga, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
                
                <div class="flex justify-between pt-3 border-t text-lg p-3 bg-gradient-to-br from-primary-500 to-primary-600 text-white rounded-lg">
                    <span class="font-bold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        Total:
                    </span>
                    <span class="font-bold">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Notifikasi Pesanan Dibatalkan --}}
        @if($pesanan->status == 'Dibatalkan')
        <div class="card bg-red-50 border-2 border-red-200 animate-slide-left delay-100">
            <div class="flex items-start gap-3">
                <div class="bg-red-600 rounded-lg p-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-gray-800 mb-2">Pesanan Dibatalkan</h3>
                    <p class="text-sm text-gray-700 mb-3">
                        Pesanan ini telah dibatalkan pada {{ \Carbon\Carbon::parse($pesanan->dibatalkan_at)->format('d M Y H:i') }}
                    </p>
                    @if($pesanan->alasan_pembatalan)
                    <div class="bg-white p-3 rounded-lg border border-red-200">
                        <p class="text-xs font-semibold text-gray-700 mb-1">Alasan Pembatalan:</p>
                        <p class="text-sm text-gray-700">{{ $pesanan->alasan_pembatalan }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- Button Batalkan Pesanan - Hanya muncul jika belum dibatalkan dan belum bayar DP --}}
        @if($pesanan->status == 'Menunggu Pembayaran DP' && (!$dpLunas || !$dpInvoice))
        <div class="card bg-red-50 border-2 border-red-200 animate-slide-left delay-{{ $pesanan->status == 'Dibatalkan' ? '200' : '100' }}">
            <div class="flex items-start gap-3">
                <div class="bg-red-600 rounded-lg p-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-gray-800 mb-2">Ingin Membatalkan Pesanan?</h3>
                    <p class="text-sm text-gray-700 mb-4">
                        Anda dapat membatalkan pesanan ini karena pembayaran DP belum dilakukan. Pesanan yang sudah dibayar tidak dapat dibatalkan.
                    </p>
                    <button onclick="confirmCancelOrder('{{ $pesanan->id }}', '{{ $pesanan->kode_pesanan }}')" 
                            class="btn-danger">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Batalkan Pesanan
                    </button>
                </div>
            </div>
        </div>
        @endif

      <!-- Detail Pesanan -->
<div class="card animate-slide-left delay-{{ $pesanan->status == 'Dibatalkan' ? '300' : ($pesanan->status == 'Menunggu Pembayaran DP' && (!$dpLunas || !$dpInvoice) ? '200' : '100') }}">
    <div class="flex items-center gap-3 mb-4">
        <div class="bg-green-100 rounded-lg p-2">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h2 class="text-xl font-bold text-gray-800">Detail Pesanan</h2>
    </div>
    
    @if(!empty(trim($pesanan->detail_pesanan ?? '')))
        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
            <p class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">{{ $pesanan->detail_pesanan }}</p>
        </div>
    @else
        <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200 flex items-start gap-3">
            <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm text-gray-600">Detail pesanan belum diisi</p>
        </div>
    @endif
</div>

{{-- Informasi Revisi --}}
@php
    $kuotaRevisi = $pesanan->paket->jumlah_revisi ?? 0;
    $totalRevisi = $pesanan->revisi()->count();
    $sisaRevisi = max(0, $kuotaRevisi - $totalRevisi);
    $revisiAktif = $pesanan->active_revisi;
    $bisaRevisi = $pesanan->canRequestRevisi();
@endphp

@if($kuotaRevisi > 0)
<div class="card animate-slide-left delay-{{ $pesanan->status == 'Dibatalkan' ? '400' : ($pesanan->status == 'Menunggu Pembayaran DP' && (!$dpLunas || !$dpInvoice) ? '300' : '200') }}">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
            <div class="bg-purple-100 rounded-lg p-2">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Revisi</h2>
                <p class="text-sm text-gray-600">Kelola permintaan revisi Anda</p>
            </div>
        </div>
        <div class="text-right">
            <p class="text-2xl font-bold {{ $sisaRevisi > 0 ? 'text-primary-600' : 'text-gray-400' }}">
                {{ $sisaRevisi }}/{{ $kuotaRevisi }}
            </p>
            <p class="text-xs text-gray-500">Sisa Kuota</p>
        </div>
    </div>

    {{-- Revisi Aktif --}}
    @if($revisiAktif)
    <div class="bg-orange-50 border-l-4 border-orange-500 p-4 rounded-r-lg mb-4">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-orange-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="flex-1">
                <h4 class="text-sm font-semibold text-orange-800 mb-1">
                    Revisi ke-{{ $revisiAktif->revisi_ke }} - {{ $revisiAktif->status }}
                </h4>
                <p class="text-xs text-orange-700 mb-2">
                    Diajukan: {{ $revisiAktif->created_at->format('d M Y H:i') }}
                </p>
                <p class="text-sm text-orange-700 bg-white p-2 rounded border border-orange-200 mb-3">
                    {{ Str::limit($revisiAktif->catatan_revisi, 150) }}
                </p>
                <a href="{{ route('client.revisi.show', $revisiAktif) }}" class="text-sm text-orange-600 hover:text-orange-800 font-medium inline-flex items-center gap-1">
                    Lihat Detail Revisi
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    @endif

            {{-- Riwayat Revisi --}}
            @if($pesanan->revisi->count() > 0)
            <div class="space-y-3">
                <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Riwayat Revisi ({{ $pesanan->revisi->count() }})
                </h3>
                
                <div class="space-y-2">
                    @foreach($pesanan->revisi->take(3) as $revisi)
                    @php
                        $statusBadgeClass = 'bg-gray-100 text-gray-800';
                        if($revisi->status == 'Diminta') $statusBadgeClass = 'bg-yellow-100 text-yellow-800';
                        if($revisi->status == 'Sedang Dikerjakan') $statusBadgeClass = 'bg-blue-100 text-blue-800';
                        if($revisi->status == 'Selesai') $statusBadgeClass = 'bg-green-100 text-green-800';
                    @endphp
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200 hover:border-primary-300 transition">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <p class="text-sm font-semibold text-gray-900">Revisi ke-{{ $revisi->revisi_ke }}</p>
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $statusBadgeClass }}">
                                    {{ $revisi->status }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-600 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $revisi->created_at->format('d M Y H:i') }}
                            </p>
                            <p class="text-xs text-gray-700 mt-1 line-clamp-1">
                                {{ Str::limit($revisi->catatan_revisi, 80) }}
                            </p>
                        </div>
                        <a href="{{ route('client.revisi.show', $revisi) }}" class="ml-3 text-primary-600 hover:text-primary-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                    @endforeach
                </div>
                
                @if($pesanan->revisi->count() > 3)
                <a href="{{ route('client.revisi.index', ['search' => $pesanan->kode_pesanan]) }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium inline-flex items-center gap-1">
                    Lihat Semua Revisi ({{ $pesanan->revisi->count() }})
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                @endif
            </div>
            @else
            <div class="bg-gray-50 p-4 rounded-lg text-center">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-sm text-gray-600">Belum ada revisi yang diajukan</p>
            </div>
            @endif

            {{-- Button Ajukan Revisi --}}
            @if($bisaRevisi && $dpLunas)
            <div class="mt-4 pt-4 border-t border-gray-200">
                <a href="{{ route('client.revisi.create', $pesanan) }}" class="btn-warning w-full justify-center inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Ajukan Revisi Baru
                </a>
            </div>
            @elseif(!$dpLunas)
            <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 text-center">
                    <p class="text-sm text-yellow-800">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        Revisi dapat diajukan setelah DP dibayar
                    </p>
                </div>
            </div>
            @elseif($sisaRevisi <= 0)
            <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-center">
                    <p class="text-sm text-red-800">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Kuota revisi Anda telah habis
                    </p>
                </div>
            </div>
            @elseif($revisiAktif)
            <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-center">
                    <p class="text-sm text-blue-800">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Tunggu revisi aktif selesai sebelum mengajukan revisi baru
                    </p>
                </div>
            </div>
            @endif
        </div>
        @endif

      {{-- Preview - Hanya muncul jika status bukan Dibatalkan --}}
@if($pesanan->status != 'Dibatalkan' && !empty($pesanan->preview_link))
    @if(!$dpLunas)
    <div class="card bg-yellow-50 border-2 border-yellow-200 animate-slide-left delay-300">
        <div class="flex items-center gap-3 mb-3">
            <div class="bg-yellow-600 rounded-lg p-2">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-800">Menunggu Pembayaran DP</h2>
        </div>
        <p class="text-sm text-gray-700 mb-4">
            Preview akan aktif setelah pembayaran DP Anda terverifikasi.
        </p>
        @if($dpInvoice && $dpInvoice->status !== 'Lunas')
        <a href="{{ route('client.pembayaran.invoice', $dpInvoice) }}" class="btn-primary inline-flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Bayar DP Sekarang
        </a>
        @endif
    </div>
    @else
        @if($previewAktif)
        <div class="card bg-blue-50 border-2 border-blue-200 animate-slide-left delay-300">
            <div class="flex items-center gap-3 mb-3">
                <div class="bg-blue-600 rounded-lg p-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-800">Preview Tersedia</h2>
            </div>
            <p class="text-sm text-gray-700 mb-2">Preview pekerjaan Anda siap dilihat.</p>
            @if($previewExpiry)
            <p class="text-xs text-green-600 font-medium mb-4 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Aktif hingga {{ $previewExpiry->format('d M Y H:i') }}
            </p>
            @endif
            <a href="{{ $pesanan->preview_link }}" target="_blank" class="btn-primary inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Lihat Preview
            </a>
        </div>
        @else
        <div class="card bg-red-50 border-2 border-red-200 animate-slide-left delay-300">
            <div class="flex items-center gap-3 mb-3">
                <div class="bg-red-600 rounded-lg p-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-800">Preview Kadaluarsa</h2>
            </div>
            <p class="text-sm text-gray-700 mb-2">
                Link preview sudah kadaluarsa pada {{ $previewExpiry ? $previewExpiry->format('d M Y H:i') : '-' }}
            </p>
            <p class="text-sm text-gray-600 mb-4">
                Silakan hubungi admin untuk meminta perpanjangan waktu akses preview.
            </p>
            <button class="btn-secondary inline-flex items-center gap-2 cursor-not-allowed opacity-60" disabled>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Link Kadaluarsa
            </button>
        </div>
        @endif
    @endif
@endif

        {{-- File Final - Hanya muncul jika status bukan Dibatalkan --}}
        @if($pesanan->status != 'Dibatalkan')
            @if($pesanan->file_final && $pelunasanLunas)
            <div class="card bg-green-50 border-2 border-green-200 animate-slide-left delay-300">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-green-600 rounded-lg p-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800">Pekerjaan Selesai</h2>
                </div>
                <p class="text-sm text-gray-700 mb-4">File final sudah tersedia untuk didownload.</p>
                <a href="{{ route('client.pesanan.download-final', $pesanan) }}" class="btn-success inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download File Final
                </a>
            </div>
            @elseif($pesanan->file_final)
            <div class="card bg-yellow-50 border-2 border-yellow-200 animate-slide-left delay-300">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-yellow-600 rounded-lg p-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800">File Final Siap</h2>
                </div>
                <p class="text-sm text-gray-700 mb-4">File final sudah disiapkan oleh tim kami. Silakan selesaikan pembayaran pelunasan untuk mengunduh hasil akhir.</p>
                @if($pelunasanInvoice && $pelunasanInvoice->status !== 'Lunas')
                <a href="{{ route('client.pembayaran.invoice', $pelunasanInvoice) }}" class="btn-primary inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Lanjutkan Pembayaran Pelunasan
                </a>
                @endif
            </div>
            @endif
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Status Timeline -->
        <div class="card sidebar-card animate-slide-right">
            <div class="flex items-center gap-3 mb-4">
                <div class="bg-primary-600 rounded-lg p-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-800">Status Pesanan</h3>
            </div>
            <div class="space-y-4">
                <div class="timeline-item flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Pesanan Dibuat</p>
                        <p class="text-xs text-gray-500">{{ $pesanan->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
                
                @if($pesanan->status == 'Dibatalkan')
                <div class="timeline-item flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-red-500 flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-900">Pesanan Dibatalkan</p>
                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($pesanan->dibatalkan_at)->format('d M Y H:i') }}</p>
                    </div>
                </div>
                @else
                <div class="timeline-item flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full {{ in_array($pesanan->status, ['Sedang Diproses', 'Preview Siap', 'Menunggu Pelunasan', 'Selesai']) ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Sedang Diproses</p>
                        <p class="text-xs text-gray-500">Tim sedang mengerjakan</p>
                    </div>
                </div>
                
                <div class="timeline-item flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full {{ $pesanan->status == 'Selesai' ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Selesai</p>
                        <p class="text-xs text-gray-500">Pekerjaan selesai</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Invoice - Hanya tampilkan jika status bukan Dibatalkan -->
        @if($pesanan->status != 'Dibatalkan')
        <div class="card sidebar-card animate-slide-right delay-100">
            <div class="flex items-center gap-3 mb-4">
                <div class="bg-blue-600 rounded-lg p-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-800">Invoice</h3>
            </div>
            <div class="space-y-2">
                @foreach($pesanan->invoices as $invoice)
                <div class="invoice-item bg-gray-50 p-3 rounded-lg flex justify-between items-center border border-gray-200">
                    <div>
                        <p class="text-sm font-medium text-gray-900 flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            {{ $invoice->tipe }}
                        </p>
                        <p class="text-xs text-gray-600">Rp {{ number_format($invoice->jumlah, 0, ',', '.') }}</p>
                    </div>
                    @if($invoice->status == 'Lunas')
                    <span class="badge-success text-xs">Lunas</span>
                    @elseif($invoice->status == 'Menunggu Verifikasi')
                    <span class="badge-warning text-xs">Pending</span>
                    @else
                    <a href="{{ route('client.pembayaran.invoice', $invoice) }}" class="text-sm text-blue-600 hover:underline font-medium">Bayar</a>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Contact -->
        <div class="card bg-blue-50 border-2 border-blue-200 sidebar-card animate-slide-right delay-200">
            <div class="flex items-center gap-3 mb-3">
                <div class="bg-blue-600 rounded-lg p-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-800">Butuh Bantuan?</h3>
            </div>
            <p class="text-sm text-gray-700 mb-3">Hubungi kami jika ada pertanyaan</p>
            <a href="https://wa.me/6281234567890" target="_blank" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-2">
                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                </svg>
                WhatsApp: 0812-3456-7890
            </a>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Batalkan Pesanan - Hanya muncul jika belum dibatalkan -->
@if($pesanan->status != 'Dibatalkan')
<div id="cancelOrderModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white animate-slide">
        <div class="mt-3">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 text-center mt-4 mb-2">Batalkan Pesanan?</h3>
            <p class="text-sm text-gray-600 text-center mb-1">Anda yakin ingin membatalkan pesanan</p>
            <p class="text-sm font-semibold text-gray-900 text-center mb-4" id="cancelOrderCode"></p>
            <p class="text-xs text-red-600 text-center mb-6">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Tindakan ini tidak dapat dibatalkan!
            </p>
            
            <form id="cancelOrderForm" method="POST" class="space-y-3">
                @csrf
                @method('PATCH')
                
                <div>
                    <label for="cancelReason" class="block text-sm font-medium text-gray-700 mb-2">
                        Alasan Pembatalan <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        id="cancelReason" 
                        name="alasan_pembatalan" 
                        rows="3" 
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition text-sm"
                        placeholder="Masukkan alasan pembatalan..."></textarea>
                </div>
                
                <div class="flex gap-3">
                    <button type="button" 
                            onclick="closeCancelModal()" 
                            class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition font-medium">
                        Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                        Ya, Batalkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isFirstLoad = !sessionStorage.getItem('pesanan_show_loaded');
        
        if (isFirstLoad) {
            document.body.classList.add('page-load');
            sessionStorage.setItem('pesanan_show_loaded', 'true');
            
            setTimeout(() => {
                document.body.classList.remove('page-load');
            }, 1000);
        }
    });

    window.addEventListener('beforeunload', function(e) {
        if (e.target.activeElement.tagName === 'A' && 
            e.target.activeElement.getAttribute('href') !== '#') {
            sessionStorage.removeItem('pesanan_show_loaded');
        }
    });

    // Fungsi untuk menampilkan modal konfirmasi
    function confirmCancelOrder(pesananId, kodePesanan) {
        const modal = document.getElementById('cancelOrderModal');
        const form = document.getElementById('cancelOrderForm');
        const codeElement = document.getElementById('cancelOrderCode');
        
        codeElement.textContent = kodePesanan;
        form.action = `/client/pesanan/${pesananId}/cancel`;
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    // Fungsi untuk menutup modal
    function closeCancelModal() {
        const modal = document.getElementById('cancelOrderModal');
        const form = document.getElementById('cancelOrderForm');
        const textarea = document.getElementById('cancelReason');
        
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        form.reset();
        textarea.value = '';
    }

    // Tutup modal jika klik di luar modal
    document.getElementById('cancelOrderModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeCancelModal();
        }
    });

    // Tutup modal dengan tombol ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCancelModal();
        }
    });
</script>
@endsection