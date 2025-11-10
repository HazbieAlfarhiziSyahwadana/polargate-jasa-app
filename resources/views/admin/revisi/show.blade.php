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
                        {{-- ✅ PERBAIKAN: Ganti user menjadi client dengan null safety --}}
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

<!-- Catatan Revisi -->
<div class="card mb-6 animate-slide delay-300">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Catatan Revisi</h2>
    <div class="bg-gray-50 p-6 rounded-lg">
        <p class="text-gray-700 whitespace-pre-line">{{ $revisi->catatan_revisi ?? 'Tidak ada catatan' }}</p>
    </div>
</div>

<!-- File Referensi -->
@if($revisi->hasFiles())
<div class="card mb-6 animate-slide delay-300">
    <h2 class="text-xl font-bold text-gray-800 mb-4">File Referensi ({{ $revisi->file_count }})</h2>
    
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

<!-- Actions -->
<div class="card animate-slide delay-300">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Tindakan</h2>
    
    @if($revisi->status != 'Selesai')
    <form action="{{ route('admin.revisi.update-status', $revisi) }}" method="POST" class="space-y-4">
        @csrf
        @method('PATCH')
        
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                Update Status Revisi
            </label>
            <select name="status" id="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition" required>
                <option value="Diminta" {{ $revisi->status == 'Diminta' ? 'selected' : '' }}>Diminta</option>
                <option value="Sedang Dikerjakan" {{ $revisi->status == 'Sedang Dikerjakan' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                <option value="Selesai" {{ $revisi->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="btn-primary flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Update Status
            </button>
        </div>
    </form>
    @else
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="font-semibold text-green-800">Revisi Selesai</p>
                <p class="text-sm text-green-700">Revisi ini telah diselesaikan pada {{ $revisi->formatted_tanggal_selesai }}</p>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.body.classList.add('page-load');
        setTimeout(() => {
            document.body.classList.remove('page-load');
        }, 800);
    });
</script>
@endsection