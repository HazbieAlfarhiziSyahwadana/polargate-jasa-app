@extends('layouts.client')

@section('title', 'Buat Pesanan Baru')

@section('content')
<div class="mb-6">
    <a href="{{ route('client.layanan.show', $layanan) }}" class="text-primary-600 hover:text-primary-700 text-sm mb-4 inline-block">
        ← Kembali ke Detail Layanan
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
            <div class="card">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Layanan yang Dipilih</h2>
                <div class="flex items-start space-x-4">
                    <img src="{{ $layanan->gambar_url }}" alt="{{ $layanan->nama_layanan }}" class="w-24 h-24 object-cover rounded-lg">
                    <div>
                        <h3 class="font-bold text-gray-900">{{ $layanan->nama_layanan }}</h3>
                        <span class="badge-info text-xs mt-1">{{ $layanan->kategori }}</span>
                        <p class="text-sm text-gray-600 mt-2">{{ Str::limit($layanan->deskripsi, 100) }}</p>
                    </div>
                </div>
            </div>

            <!-- Pilih Paket -->
            <div class="card">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Pilih Paket <span class="text-red-500">*</span></h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($layanan->paket as $paket)
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
                        <div class="border-2 border-gray-200 rounded-lg p-4 peer-checked:border-primary-500 peer-checked:bg-primary-50 hover:border-primary-300 transition">
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
                                        <svg class="w-4 h-4 text-green-500 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ $fitur }}
                                    </p>
                                    @endforeach
                                @endif
                            </div>
                            
                            <div class="flex items-center justify-between mt-3 pt-3 border-t text-xs text-gray-600">
                                <span>⏱️ {{ $paket->durasi_pengerjaan }} hari</span>
                                <span>🔄 {{ $paket->jumlah_revisi }}x revisi</span>
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
            <div class="card">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Add-ons (Opsional)</h2>
                <p class="text-sm text-gray-600 mb-4">Pilih add-ons tambahan untuk meningkatkan layanan Anda</p>
                
                <div class="space-y-3">
                    @foreach($layanan->addons as $addon)
                    <label class="flex items-start p-4 border-2 border-gray-200 rounded-lg hover:border-primary-300 cursor-pointer transition">
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
                                    <h4 class="font-semibold text-gray-900">{{ $addon->nama_addon }}</h4>
                                    <p class="text-sm text-gray-600">{{ $addon->deskripsi }}</p>
                                </div>
                                <p class="font-bold text-primary-600 ml-4">+Rp {{ number_format($addon->harga, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Detail Pesanan -->
            <div class="card">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Detail Pesanan <span class="text-red-500">*</span></h2>
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
                <p class="text-xs text-gray-500 mt-2">*Jelaskan sedetail mungkin agar kami dapat memahami kebutuhan Anda</p>
            </div>

            <!-- File Pendukung (Opsional) -->
            <div class="card">
                <h2 class="text-xl font-bold text-gray-800 mb-4">File Pendukung (Opsional)</h2>
                <p class="text-sm text-gray-600 mb-3">Upload file referensi, brief, atau dokumen pendukung lainnya</p>
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
                <p class="text-xs text-gray-500 mt-2">Max 5MB per file. Format: PDF, DOC, DOCX, JPG, PNG, ZIP, RAR</p>
            </div>
        </div>

        <!-- Sidebar Summary -->
        <div class="space-y-6">
            <!-- Ringkasan Harga -->
            <div class="card sticky top-6">
                <h3 class="font-bold text-gray-800 mb-4">Ringkasan Pesanan</h3>
                
                <div class="space-y-3 mb-4 pb-4 border-b">
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
                
                <div class="bg-blue-50 p-3 rounded-lg mb-4 text-sm">
                    <p class="font-semibold text-gray-900 mb-1">Pembayaran DP (50%):</p>
                    <p class="text-xl font-bold text-primary-600" x-text="formatRupiah(totalHarga * 0.5)">Rp 0</p>
                </div>
                
                <button type="submit" class="btn-primary w-full">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Buat Pesanan
                </button>
                
                <p class="text-xs text-gray-500 text-center mt-3">Dengan melakukan pemesanan, Anda menyetujui syarat dan ketentuan kami</p>
            </div>

            <!-- Info Pembayaran -->
            <div class="card bg-yellow-50 border border-yellow-200">
                <h3 class="font-semibold text-gray-800 mb-2">📋 Informasi Pembayaran</h3>
                <ul class="text-xs text-gray-700 space-y-1">
                    <li>• DP 50% dibayar setelah pesanan dibuat</li>
                    <li>• Pelunasan 50% setelah preview disetujui</li>
                    <li>• Pembayaran dapat melalui transfer bank</li>
                    <li>• Verifikasi maksimal 1x24 jam</li>
                </ul>
            </div>
        </div>
    </div>
</form>

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