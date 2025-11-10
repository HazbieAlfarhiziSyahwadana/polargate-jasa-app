@extends('layouts.client')

@section('title', 'Pembayaran Invoice')

@section('content')
<div class="mb-6">
    <a href="{{ route('client.invoice.index') }}" class="text-primary-600 hover:text-primary-700 text-sm mb-4 inline-block">
        ← Kembali ke Daftar Invoice
    </a>
    <h1 class="text-3xl font-bold text-gray-800">Pembayaran Invoice</h1>
    <p class="text-gray-600">Upload bukti pembayaran Anda</p>
</div>

{{-- Alert Messages --}}
@if(session('error'))
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
    <strong class="font-bold">Error!</strong>
    <span class="block sm:inline">{{ session('error') }}</span>
</div>
@endif

@if($errors->any())
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
    <strong class="font-bold">Terjadi kesalahan:</strong>
    <ul class="mt-2 list-disc list-inside">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Upload -->
    <div class="lg:col-span-2">
        <div class="card mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Detail Invoice</h2>
            
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Nomor Invoice:</span>
                    <span class="font-semibold text-gray-900">{{ $invoice->nomor_invoice }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Tipe:</span>
                    <span class="font-semibold text-gray-900">{{ $invoice->tipe }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Pesanan:</span>
                    <span class="font-semibold text-gray-900">{{ $invoice->pesanan->kode_pesanan }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Layanan:</span>
                    <span class="font-semibold text-gray-900">{{ $invoice->pesanan->layanan->nama }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Paket:</span>
                    <span class="font-semibold text-gray-900">{{ $invoice->pesanan->paket->nama }}</span>
                </div>
                <div class="flex justify-between pt-3 border-t text-lg">
                    <span class="font-bold text-gray-900">Total Tagihan:</span>
                    <span class="font-bold text-primary-600">Rp {{ number_format($invoice->jumlah, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Jatuh Tempo:</span>
                    <span class="font-semibold {{ \Carbon\Carbon::parse($invoice->jatuh_tempo)->isPast() ? 'text-red-600' : 'text-gray-900' }}">
                        {{ \Carbon\Carbon::parse($invoice->jatuh_tempo)->format('d M Y') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Form Pembayaran -->
        <div class="card">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Upload Bukti Pembayaran</h2>
            
            {{-- PENTING: enctype="multipart/form-data" wajib ada untuk upload file --}}
            <form action="{{ route('client.pembayaran.store') }}" 
                  method="POST" 
                  enctype="multipart/form-data"
                  id="paymentForm">
                @csrf
                
                {{-- Hidden fields --}}
                <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">

                <!-- Metode Pembayaran -->
                <div class="mb-4">
                    <label for="metode_pembayaran" class="block text-gray-700 text-sm font-medium mb-2">
                        Metode Pembayaran <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="metode_pembayaran" 
                        id="metode_pembayaran" 
                        class="input-field @error('metode_pembayaran') border-red-500 @enderror"
                        required
                    >
                        <option value="">-- Pilih Metode --</option>
                        <option value="Transfer Bank BCA" {{ old('metode_pembayaran') == 'Transfer Bank BCA' ? 'selected' : '' }}>Transfer Bank BCA</option>
                        <option value="Transfer Bank Mandiri" {{ old('metode_pembayaran') == 'Transfer Bank Mandiri' ? 'selected' : '' }}>Transfer Bank Mandiri</option>
                        <option value="Transfer Bank BNI" {{ old('metode_pembayaran') == 'Transfer Bank BNI' ? 'selected' : '' }}>Transfer Bank BNI</option>
                        <option value="Transfer Bank BRI" {{ old('metode_pembayaran') == 'Transfer Bank BRI' ? 'selected' : '' }}>Transfer Bank BRI</option>
                        <option value="E-Wallet (OVO)" {{ old('metode_pembayaran') == 'E-Wallet (OVO)' ? 'selected' : '' }}>E-Wallet (OVO)</option>
                        <option value="E-Wallet (GoPay)" {{ old('metode_pembayaran') == 'E-Wallet (GoPay)' ? 'selected' : '' }}>E-Wallet (GoPay)</option>
                        <option value="E-Wallet (Dana)" {{ old('metode_pembayaran') == 'E-Wallet (Dana)' ? 'selected' : '' }}>E-Wallet (Dana)</option>
                    </select>
                    @error('metode_pembayaran')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bukti Transfer -->
                <div class="mb-4">
                    <label for="bukti_pembayaran" class="block text-gray-700 text-sm font-medium mb-2">
                        Bukti Transfer <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="file" 
                        name="bukti_pembayaran" 
                        id="bukti_pembayaran" 
                        accept="image/jpeg,image/jpg,image/png"
                        class="input-field @error('bukti_pembayaran') border-red-500 @enderror"
                        required
                        onchange="previewImage(event)"
                    >
                    @error('bukti_pembayaran')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Upload screenshot/foto bukti transfer. Max 2MB (JPG, PNG)</p>
                    
                    <!-- Preview -->
                    <div id="imagePreview" class="mt-3 hidden">
                        <p class="text-sm text-gray-600 mb-2">Preview:</p>
                        <img id="preview" src="" alt="Preview" class="max-w-full h-auto rounded-lg border max-h-96">
                    </div>
                    
                    <!-- File Info -->
                    <div id="fileInfo" class="mt-2 text-xs text-gray-600 hidden"></div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('client.invoice.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" id="submitBtn" class="btn-primary">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span id="btnText">Upload Bukti Pembayaran</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="space-y-6">
        <!-- Informasi Rekening -->
        <div class="card bg-blue-50 border border-blue-200">
            <h3 class="font-semibold text-gray-800 mb-3">💳 Informasi Rekening</h3>
            
            <div class="space-y-3 text-sm">
                <div class="bg-white p-3 rounded">
                    <p class="text-xs text-gray-600 mb-1">Bank BCA</p>
                    <p class="font-bold text-gray-900">1234567890</p>
                    <p class="text-xs text-gray-600">a.n. PT Polargate Indonesia</p>
                </div>
                
                <div class="bg-white p-3 rounded">
                    <p class="text-xs text-gray-600 mb-1">Bank Mandiri</p>
                    <p class="font-bold text-gray-900">0987654321</p>
                    <p class="text-xs text-gray-600">a.n. PT Polargate Indonesia</p>
                </div>
                
                <div class="bg-white p-3 rounded">
                    <p class="text-xs text-gray-600 mb-1">OVO</p>
                    <p class="font-bold text-gray-900">0812-3456-7890</p>
                    <p class="text-xs text-gray-600">a.n. Polargate</p>
                </div>
            </div>
        </div>

        <!-- Petunjuk -->
        <div class="card">
            <h3 class="font-semibold text-gray-800 mb-3">📝 Petunjuk Pembayaran</h3>
            <ol class="text-xs text-gray-700 space-y-2 list-decimal list-inside">
                <li>Transfer sesuai jumlah tagihan</li>
                <li>Screenshot/foto bukti transfer</li>
                <li>Upload bukti pada form di samping</li>
                <li>Tunggu verifikasi (maks 1x24 jam)</li>
                <li>Status akan diupdate otomatis</li>
            </ol>
        </div>

        <!-- Warning -->
        <div class="card bg-yellow-50 border border-yellow-200">
            <h3 class="font-semibold text-gray-800 mb-2">⚠️ Perhatian</h3>
            <ul class="text-xs text-gray-700 space-y-1">
                <li>• Transfer tepat waktu sebelum jatuh tempo</li>
                <li>• Pastikan bukti transfer jelas terbaca</li>
                <li>• Hubungi CS jika ada kendala</li>
            </ul>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Preview image dan validasi
function previewImage(event) {
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('preview');
    const fileInfo = document.getElementById('fileInfo');
    const file = event.target.files[0];
    
    if (file) {
        // Validasi tipe file
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!allowedTypes.includes(file.type)) {
            alert('Format file tidak didukung! Gunakan JPG, JPEG, atau PNG');
            event.target.value = '';
            preview.classList.add('hidden');
            fileInfo.classList.add('hidden');
            return;
        }
        
        // Validasi ukuran file (2MB = 2048KB)
        const maxSize = 2048 * 1024; // 2MB in bytes
        if (file.size > maxSize) {
            alert('Ukuran file terlalu besar! Maksimal 2MB');
            event.target.value = '';
            preview.classList.add('hidden');
            fileInfo.classList.add('hidden');
            return;
        }
        
        // Show file info
        const fileSizeKB = (file.size / 1024).toFixed(2);
        fileInfo.textContent = `File: ${file.name} (${fileSizeKB} KB)`;
        fileInfo.classList.remove('hidden');
        
        // Preview image
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
        fileInfo.classList.add('hidden');
    }
}

// Validasi form sebelum submit
document.getElementById('paymentForm').addEventListener('submit', function(e) {
    const fileInput = document.getElementById('bukti_pembayaran');
    const metodeInput = document.getElementById('metode_pembayaran');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    
    // Validasi metode pembayaran
    if (!metodeInput.value) {
        e.preventDefault();
        alert('Silakan pilih metode pembayaran!');
        metodeInput.focus();
        return false;
    }
    
    // Validasi file
    if (!fileInput.files || fileInput.files.length === 0) {
        e.preventDefault();
        alert('Silakan pilih file bukti pembayaran!');
        fileInput.focus();
        return false;
    }
    
    // Validasi ukuran file lagi sebelum submit
    const file = fileInput.files[0];
    const maxSize = 2048 * 1024; // 2MB
    if (file.size > maxSize) {
        e.preventDefault();
        alert('Ukuran file terlalu besar! Maksimal 2MB');
        fileInput.value = '';
        return false;
    }
    
    // Confirm submit
    if (!confirm('Apakah Anda yakin data yang diinput sudah benar?')) {
        e.preventDefault();
        return false;
    }
    
    // Disable button dan show loading
    submitBtn.disabled = true;
    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
    btnText.textContent = 'Mengupload...';
    
    return true;
});

// Reset form jika ada error dan kembali
window.addEventListener('pageshow', function(event) {
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    
    if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        btnText.textContent = 'Upload Bukti Pembayaran';
    }
});
</script>
@endpush
@endsection