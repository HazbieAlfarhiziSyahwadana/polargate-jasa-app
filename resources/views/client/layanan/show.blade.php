@extends('layouts.client')

@section('title', $layanan->nama_layanan)

@section('content')
<div class="mb-6">
    <a href="{{ route('client.layanan.index') }}" class="text-primary-600 hover:text-primary-700 text-sm">
        ← Kembali ke Daftar Layanan
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Detail Layanan -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Gambar & Info Utama -->
        <div class="card">
            <img src="{{ $layanan->gambar_url }}" alt="{{ $layanan->nama_layanan }}" class="w-full h-64 object-contain rounded-lg mb-4 bg-gray-100 border border-gray-200">
            
            <span class="badge-info mb-2">{{ $layanan->kategori }}</span>
            <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $layanan->nama_layanan }}</h1>
            <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $layanan->deskripsi }}</p>
        </div>

        <!-- Paket -->
        @if($layanan->paket->count() > 0)
        <div class="card">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Pilih Paket</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($layanan->paket as $paket)
                <div class="border-2 border-gray-200 rounded-lg p-5 hover:border-primary-500 transition-colors">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $paket->nama_paket }}</h3>
                    <p class="text-3xl font-bold text-primary-600 mb-3">
                        Rp {{ number_format($paket->harga, 0, ',', '.') }}
                    </p>
                    <p class="text-sm text-gray-600 mb-4">{{ $paket->deskripsi }}</p>
                    
                    <div class="mb-4">
                        <p class="text-sm font-semibold text-gray-700 mb-2">Fitur:</p>
                        <ul class="space-y-1">
                            @php
                                $fiturArray = is_string($paket->fitur) ? json_decode($paket->fitur, true) : $paket->fitur;
                            @endphp
                            @if($fiturArray && is_array($fiturArray))
                                @foreach($fiturArray as $fitur)
                                <li class="text-sm text-gray-700 flex items-start">
                                    <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    {{ $fitur }}
                                </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>

                    <div class="flex items-center justify-between text-sm text-gray-600 mb-4 pb-4 border-b">
                        <div>
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $paket->durasi_pengerjaan }} hari
                        </div>
                        <div>
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            {{ $paket->jumlah_revisi }}x revisi
                        </div>
                    </div>

                    <a href="{{ route('client.pesanan.create', $layanan) }}?paket={{ $paket->id }}" class="btn-primary w-full">
                        Pilih Paket Ini
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Add-ons -->
        @if($layanan->addons->count() > 0)
        <div class="card">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Add-ons Tersedia</h2>
            <p class="text-sm text-gray-600 mb-4">Tingkatkan layanan Anda dengan add-ons tambahan</p>
            
            <div class="space-y-3">
                @foreach($layanan->addons as $addon)
                <div class="bg-gray-50 p-4 rounded-lg flex justify-between items-center">
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800">{{ $addon->nama_addon }}</h4>
                        <p class="text-sm text-gray-600">{{ $addon->deskripsi }}</p>
                    </div>
                    <div class="text-right ml-4">
                        <p class="font-bold text-primary-600">+Rp {{ number_format($addon->harga, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Layanan Terkait -->
        @if(isset($layananTerkait) && $layananTerkait->count() > 0)
        <div class="card">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Layanan Terkait</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($layananTerkait as $item)
                <a href="{{ route('client.layanan.show', $item) }}" class="bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition-colors">
                    <img src="{{ $item->gambar_url }}" alt="{{ $item->nama_layanan }}" class="w-full h-32 object-contain rounded mb-3 bg-gray-100 border border-gray-200">
                    <h4 class="font-semibold text-gray-800 mb-1">{{ $item->nama_layanan }}</h4>
                    <p class="text-sm text-gray-600 mb-2 line-clamp-2">{{ Str::limit($item->deskripsi, 80) }}</p>
                    <p class="text-primary-600 font-semibold">Mulai Rp {{ number_format($item->harga_mulai, 0, ',', '.') }}</p>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Quick Info -->
        <div class="card bg-primary-50 border border-primary-200">
            <h3 class="font-bold text-gray-800 mb-4">Informasi Layanan</h3>
            <div class="space-y-3 text-sm">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-primary-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-gray-700">Garansi kepuasan pelanggan</span>
                </div>
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-primary-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-gray-700">Pengerjaan tepat waktu</span>
                </div>
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-primary-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span class="text-gray-700">Revisi sesuai paket</span>
                </div>
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-primary-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span class="text-gray-700">Tim profesional berpengalaman</span>
                </div>
            </div>
        </div>

        <!-- Harga Mulai -->
        <div class="card bg-gradient-to-br from-primary-500 to-primary-600 text-white">
            <p class="text-primary-100 text-sm mb-2">Harga Mulai Dari</p>
            <p class="text-4xl font-bold mb-4">Rp {{ number_format($layanan->harga_mulai, 0, ',', '.') }}</p>
            <a href="{{ route('client.pesanan.create', $layanan) }}" class="btn-secondary w-full bg-white text-primary-600 hover:bg-gray-100">
                Pesan Sekarang
            </a>
        </div>

        <!-- Butuh Bantuan -->
        <div class="card">
            <h3 class="font-bold text-gray-800 mb-3">Butuh Bantuan?</h3>
            <p class="text-sm text-gray-600 mb-4">Hubungi tim kami untuk konsultasi gratis</p>
            <div class="space-y-2">
                <a href="https://wa.me/6281234567890" target="_blank" class="flex items-center text-sm text-gray-700 hover:text-primary-600">
                    <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                    WhatsApp: 0812-3456-7890
                </a>
                <a href="mailto:info@polargate.com" class="flex items-center text-sm text-gray-700 hover:text-primary-600">
                    <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    info@polargate.com
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
