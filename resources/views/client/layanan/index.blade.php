@extends('layouts.client')

@section('title', 'Lihat Layanan')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Layanan Kami</h1>
    <p class="text-gray-600">Pilih layanan yang sesuai dengan kebutuhan Anda</p>
</div>

<!-- Filter & Search -->
<div class="card mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
            <select name="kategori" id="kategori" class="input-field" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                <option value="Multimedia" {{ request('kategori') == 'Multimedia' ? 'selected' : '' }}>Multimedia</option>
                <option value="IT" {{ request('kategori') == 'IT' ? 'selected' : '' }}>IT</option>
            </select>
        </div>
        <div>
            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Layanan</label>
            <input type="text" name="search" id="search" value="{{ request('search') }}" class="input-field" placeholder="Nama layanan">
        </div>
        <div class="flex items-end">
            <button type="submit" class="btn-primary mr-2">Cari</button>
            <a href="{{ route('client.layanan.index') }}" class="btn-secondary">Reset</a>
        </div>
    </form>
</div>

<!-- Layanan Grid -->
@if($layanan->count() > 0)
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($layanan as $item)
    <div class="card hover:shadow-xl transition-shadow duration-300">
        <!-- Gambar -->
        <div class="mb-4">
            <img src="{{ $item->gambar_url }}" alt="{{ $item->nama_layanan }}" class="w-full h-48 object-cover rounded-lg">
        </div>

        <!-- Badge Kategori -->
        <span class="badge-info text-xs mb-2">{{ $item->kategori }}</span>

        <!-- Nama & Deskripsi -->
        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $item->nama_layanan }}</h3>
        <p class="text-sm text-gray-600 mb-4 line-clamp-3">{{ $item->deskripsi }}</p>

        <!-- Harga -->
        <div class="flex justify-between items-center mb-4 pb-4 border-b">
            <div>
                <p class="text-xs text-gray-500">Mulai dari</p>
                <p class="text-xl font-bold text-primary-600">Rp {{ number_format($item->harga_mulai, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Paket -->
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

        <!-- Action Button -->
        <a href="{{ route('client.layanan.show', $item) }}" class="btn-primary w-full">
            Lihat Detail & Pesan
        </a>
    </div>
    @endforeach
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $layanan->links() }}
</div>

@else
<div class="card text-center py-12">
    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak Ada Layanan Ditemukan</h3>
    <p class="mt-2 text-sm text-gray-500">Coba ubah filter atau kata kunci pencarian</p>
</div>
@endif
@endsection