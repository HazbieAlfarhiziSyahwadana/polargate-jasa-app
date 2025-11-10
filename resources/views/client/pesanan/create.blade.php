@extends('layouts.client')

@section('title', 'Buat Pesanan Baru')

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

    /* Animasi hanya untuk first load */
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
        transition: box-shadow 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .paket-option {
        transition: all 0.3s ease;
    }

    .paket-option:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .addon-option {
        transition: all 0.3s ease;
    }

    .addon-option:hover {
        border-color: #3b82f6;
        background-color: #eff6ff;
    }

    .btn-primary {
        transition: all 0.2s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
    }

    .back-button {
        transition: all 0.2s ease;
    }

    .back-button:hover {
        transform: translateX(-4px);
        color: #1e40af;
    }

    .input-field, textarea {
        transition: all 0.3s ease;
    }

    .input-field:focus, textarea:focus {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }

    .sidebar-summary {
        position: sticky;
        top: 20px;
    }

    @media (max-width: 1024px) {
        .sidebar-summary {
            position: relative;
            top: 0;
        }
    }
</style>

<div class="mb-6 animate-fade">
    <a href="{{ route('client.layanan.show', $layanan) }}" class="back-button text-primary-600 hover:text-primary-700 text-sm mb-4 inline-flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Detail Layanan
    </a>
    <h1 class="text-3xl font-bold text-gray-800">Buat Pesanan Baru</h1>
    <p class="text-gray-600">Lengkapi form di bawah untuk membuat pesanan</p>
</div>

<form action="{{ route('client.pesanan.store') }}" method="POST" enctype="multipart/form-data" x-data="pesananForm()">
    @csrf
    <input type="hidden" name="layanan_id" value="{{ $layanan->id }}">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Input -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Info Layanan -->
            <div class="card animate-slide-left">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-primary-100 rounded-lg p-2">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800">Layanan yang Dipilih</h2>
                </div>
                
                <div class="flex items-start space-x-4">
                    <div class="overflow-hidden rounded-lg bg-gray-50">
                        <img src="{{ $layanan->gambar_url }}" alt="{{ $layanan->nama_layanan }}" class="w-24 h-24 object-contain rounded-lg bg-gray-100 border border-gray-200">
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">{{ $layanan->nama_layanan }}</h3>
                        <span class="badge-info text-xs mt-1 inline-block">{{ $layanan->kategori }}</span>
                        <p class="text-sm text-gray-600 mt-2">{{ Str::limit($layanan->deskripsi, 100) }}</p>
                    </div>
                </div>
            </div>

            <!-- Pilih Paket -->
            <div class="card animate-slide-left delay-100">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-blue-100 rounded-lg p-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Pilih Paket <span class="text-red-500">*</span></h2>
                        <p class="text-sm text-gray-600">Pilih paket yang sesuai dengan kebutuhan Anda</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($layanan->paket as $index => $paket)
                    <label class="cursor-pointer">
                        <input 
                            type="radio" 
                            name="paket_id" 
                            value="{{ $paket->id }}" 
                            class="hidden peer"
                            @change="updateHarga({{ $paket->harga }})"
                            {{ old('paket_id') == $paket->id || request('paket') == $paket->id ? 'checked' : '' }}
                            required
                        >
                        <div class="paket-option border-2 border-gray-200 rounded-lg p-4 peer-checked:border-primary-500 peer-checked:bg-primary-50 hover:border-primary-300 transition {{ $index === 0 ? 'relative' : '' }}">
                            @if($index === 0)
                            <span class="absolute -top-2 -right-2 bg-primary-600 text-white text-xs font-bold px-3 py-1 rounded-full">
                                POPULER
                            </span>
                            @endif
                            
                            <h3 class="font-bold text-gray-900 mb-2">{{ $paket->nama_paket }}</h3>
                            <p class="text-2xl font-bold text-primary-600 mb-2">Rp {{ number_format($paket->harga, 0, ',', '.') }}</p>
                            <p class="text-sm text-gray-600 mb-3">{{ $paket->deskripsi }}</p>
                            
                            <div class="space-y-1">
                                @php
                                    $fiturArray = is_string($paket->fitur) ? json_decode($paket->fitur, true) : $paket->fitur;
                                @endphp
                                @if($fiturArray && is_array($fiturArray))
                                    @foreach($fiturArray as $fitur)
                                    <p class="text-xs text-gray-700 flex items-start">
                                        <svg class="w-4 h-4 text-green-500 mr-1 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ $fitur }}
                                    </p>
                                    @endforeach
                                @endif
                            </div>
                            
                            <div class="flex items-center justify-between mt-3 pt-3 border-t text-xs text-gray-600">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $paket->durasi_pengerjaan }} hari
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    {{ $paket->jumlah_revisi }}x revisi
                                </span>
                            </div>
                        </div>
                    </label>
                    @endforeach
                </div>
                
                @error('paket_id')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Pilih Add-ons (Opsional) -->
            @if($layanan->addons->count() > 0)
            <div class="card animate-slide-left delay-200">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-purple-100 rounded-lg p-2">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Add-ons (Opsional)</h2>
                        <p class="text-sm text-gray-600">Pilih add-ons tambahan untuk meningkatkan layanan Anda</p>
                    </div>
                </div>
                
                <div class="space-y-3">
                    @foreach($layanan->addons as $addon)
                    <label class="addon-option flex items-start p-4 border-2 border-gray-200 rounded-lg hover:border-primary-300 cursor-pointer transition">
                        <input 
                            type="checkbox" 
                            name="addons[]" 
                            value="{{ $addon->id }}"
                            class="mt-1 rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                            @change="updateAddonHarga({{ $addon->harga }}, $event.target.checked)"
                            {{ in_array($addon->id, old('addons', [])) ? 'checked' : '' }}
                        >
                        <div class="ml-3 flex-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-semibold text-gray-900 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ $addon->nama_addon }}
                                    </h4>
                                    <p class="text-sm text-gray-600">{{ $addon->deskripsi }}</p>
                                </div>
                                <p class="font-bold text-purple-600 ml-4 text-lg">+Rp {{ number_format($addon->harga, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Detail Pesanan -->
            <div class="card animate-slide-left delay-300">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-green-100 rounded-lg p-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Detail Pesanan <span class="text-red-500">*</span></h2>
                        <p class="text-sm text-gray-600">Jelaskan kebutuhan Anda sedetail mungkin</p>
                    </div>
                </div>
                
                <textarea 
                    name="detail_pesanan" 
                    rows="6" 
                    class="input-field @error('detail_pesanan') border-red-500 @enderror" 
                    placeholder="Jelaskan detail pesanan Anda secara lengkap...&#10;&#10;Contoh:&#10;- Nama project/produk&#10;- Ukuran/dimensi&#10;- Warna yang diinginkan&#10;- Referensi/contoh (jika ada)&#10;- Deadline khusus&#10;- Informasi penting lainnya"
                    required
>{{ old('detail_pesanan') }}</textarea>
                @error('detail_pesanan')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-2 flex items-start gap-2">
                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>*Jelaskan sedetail mungkin agar kami dapat memahami kebutuhan Anda</span>
                </p>
            </div>

            <!-- File Pendukung (Opsional) -->
            <div class="card animate-slide-left delay-400">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-yellow-100 rounded-lg p-2">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">File Pendukung (Opsional)</h2>
                        <p class="text-sm text-gray-600">Upload file referensi, brief, atau dokumen pendukung lainnya</p>
                    </div>
                </div>
                
                <input 
                    type="file" 
                    name="file_pendukung[]" 
                    multiple
                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip,.rar"
                    class="input-field @error('file_pendukung.*') border-red-500 @enderror"
                >
                @error('file_pendukung.*')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-2 flex items-start gap-2">
                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Max 5MB per file. Format: PDF, DOC, DOCX, JPG, PNG, ZIP, RAR</span>
                </p>
            </div>
        </div>

        <!-- Sidebar Summary -->
        <div class="space-y-6">
            <!-- Ringkasan Harga -->
            <div class="card sidebar-summary animate-slide-right">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-primary-600 rounded-lg p-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-800">Ringkasan Pesanan</h3>
                </div>
                
                <div class="space-y-3 mb-4 pb-4 border-b border-gray-200">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Harga Paket:</span>
                        <span class="font-semibold text-gray-900" x-text="formatRupiah(hargaPaket)">Rp 0</span>
                    </div>
                    
                    <div x-show="totalAddon > 0" class="flex justify-between text-sm">
                        <span class="text-gray-600">Add-ons:</span>
                        <span class="font-semibold text-gray-900" x-text="formatRupiah(totalAddon)">Rp 0</span>
                    </div>
                </div>
                
                <div class="flex justify-between items-center mb-4">
                    <span class="font-bold text-gray-900">Total:</span>
                    <span class="text-2xl font-bold text-primary-600" x-text="formatRupiah(totalHarga)">Rp 0</span>
                </div>
                
                <div class="bg-blue-50 p-4 rounded-lg mb-4 text-sm border border-blue-200">
                    <p class="font-semibold text-gray-900 mb-1 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Pembayaran DP (50%):
                    </p>
                    <p class="text-xl font-bold text-primary-600" x-text="formatRupiah(totalHarga * 0.5)">Rp 0</p>
                </div>
                
                <button type="submit" class="btn-primary w-full flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Buat Pesanan
                </button>
                
                <p class="text-xs text-gray-500 text-center mt-3 flex items-center justify-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Dengan melakukan pemesanan, Anda menyetujui syarat dan ketentuan kami
                </p>
            </div>

            <!-- Info Pembayaran -->
            <div class="card bg-yellow-50 border border-yellow-200 animate-slide-right delay-100">
                <div class="flex items-center gap-3 mb-3">
                    <div class="bg-yellow-600 rounded-lg p-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800">Informasi Pembayaran</h3>
                </div>
                <ul class="text-xs text-gray-700 space-y-2">
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>DP 50% dibayar setelah pesanan dibuat</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Pelunasan 50% setelah preview disetujui</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Pembayaran dapat melalui transfer bank</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Verifikasi maksimal 1x24 jam</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isFirstLoad = !sessionStorage.getItem('pesanan_create_loaded');
        
        if (isFirstLoad) {
            document.body.classList.add('page-load');
            sessionStorage.setItem('pesanan_create_loaded', 'true');
            
            setTimeout(() => {
                document.body.classList.remove('page-load');
            }, 1000);
        }
    });

    window.addEventListener('beforeunload', function(e) {
        if (e.target.activeElement.tagName === 'A' && 
            e.target.activeElement.getAttribute('href') !== '#') {
            sessionStorage.removeItem('pesanan_create_loaded');
        }
    });
</script>

@endsection

@push('scripts')
<script>
function pesananForm() {
    return {
        hargaPaket: {{ request('paket') ? ($layanan->paket->find(request('paket'))->harga ?? 0) : 0 }},
        totalAddon: 0,
        totalHarga: {{ request('paket') ? ($layanan->paket->find(request('paket'))->harga ?? 0) : 0 }},
        
        updateHarga(harga) {
            this.hargaPaket = harga;
            this.calculateTotal();
        },
        
        updateAddonHarga(harga, checked) {
            if (checked) {
                this.totalAddon += harga;
            } else {
                this.totalAddon -= harga;
            }
            this.calculateTotal();
        },
        
        calculateTotal() {
            this.totalHarga = this.hargaPaket + this.totalAddon;
        },
        
        formatRupiah(angka) {
            if (!angka || angka === 0) return 'Rp 0';
            return 'Rp ' + Math.round(angka).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
    }
}
</script>
@endpush