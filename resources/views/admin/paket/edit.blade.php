@extends('layouts.admin')

@section('title', 'Edit Paket')

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

    .input-field, select, textarea {
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        width: 100%;
        padding: 0.625rem 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        outline: none;
    }

    .input-field:focus, select:focus, textarea:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .btn-primary, .btn-secondary {
        transition: all 0.2s ease;
    }

    .btn-primary:hover, .btn-secondary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .form-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #374151;
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .error-message {
        color: #ef4444;
        font-size: 0.75rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .error-message::before {
        content: "⚠";
    }

    .btn-danger-outline {
        padding: 0.5rem 0.75rem;
        border: 2px solid #ef4444;
        color: #dc2626;
        border-radius: 0.5rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.2s;
        background: white;
    }

    .btn-danger-outline:hover {
        background-color: #fef2f2;
        transform: scale(1.05);
    }

    .btn-secondary {
        padding: 0.625rem 1rem;
        background: white;
        border: 1px solid #d1d5db;
        color: #374151;
        border-radius: 0.5rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-secondary:hover {
        background-color: #f9fafb;
    }

    .btn-primary {
        padding: 0.625rem 1rem;
        background: #2563eb;
        color: white;
        border-radius: 0.5rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-primary:hover {
        background: #1d4ed8;
    }

    .card {
        background: white;
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
        padding: 1rem;
    }

    @media (min-width: 768px) {
        .card {
            padding: 1.5rem;
        }
    }
</style>

<div class="animate-fade">
    <div class="mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.paket.index') }}" 
               class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Edit Paket</h1>
                <p class="text-sm text-gray-600 mt-1">Update informasi paket <span class="font-semibold text-blue-600">{{ $paket->nama_paket }}</span></p>
            </div>
        </div>
    </div>

    <div class="max-w-4xl animate-slide">
        <div class="card">
            <!-- Info Banner -->
            <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-500 rounded-lg">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-blue-800">Informasi Edit</p>
                        <p class="text-sm text-blue-700 mt-1">Pastikan semua informasi yang diubah sudah benar sebelum menyimpan perubahan.</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.paket.update', $paket) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Layanan -->
                <div class="relative">
                    <label class="form-label">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Layanan <span class="text-red-500">*</span>
                    </label>
                    
                    <select name="layanan_id" id="layanan_id" class="input-field w-full @error('layanan_id') border-red-500 @enderror" required>
                        <option value="">Pilih Layanan</option>
                        @foreach($layanan as $item)
                        <option value="{{ $item->id }}" {{ old('layanan_id', $paket->layanan_id) == $item->id ? 'selected' : '' }}>
                            {{ $item->nama_layanan }} • {{ $item->kategori }}
                        </option>
                        @endforeach
                    </select>
                    
                    @error('layanan_id')
                    <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nama_paket" class="form-label">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            Nama Paket <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_paket" id="nama_paket" 
                               value="{{ old('nama_paket', $paket->nama_paket) }}" 
                               class="input-field w-full @error('nama_paket') border-red-500 @enderror" required>
                        @error('nama_paket')
                        <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="harga" class="form-label">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Harga <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="harga" id="harga" 
                               value="{{ old('harga', $paket->harga) }}" 
                               class="input-field w-full @error('harga') border-red-500 @enderror" required>
                        @error('harga')
                        <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="deskripsi" class="form-label">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                        </svg>
                        Deskripsi <span class="text-red-500">*</span>
                    </label>
                    <textarea name="deskripsi" id="deskripsi" rows="4" 
                              class="input-field w-full @error('deskripsi') border-red-500 @enderror" required>{{ old('deskripsi', $paket->deskripsi) }}</textarea>
                    @error('deskripsi')
                    <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fitur Paket - Versi yang sama seperti create -->
                <div>
                    <label class="form-label mb-3">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        Fitur Paket <span class="text-red-500">*</span>
                    </label>
                    
                    <div class="space-y-3" id="fitur-fields">
                        @php
                            $oldFitur = old('fitur', $paket->fitur);
                            if (is_string($oldFitur)) {
                                $oldFitur = json_decode($oldFitur, true) ?? [];
                            }
                            if (empty($oldFitur)) {
                                $oldFitur = [''];
                            }
                        @endphp
                        
                        @foreach($oldFitur as $index => $fitur)
                            <div class="flex gap-2 fitur-item" data-index="{{ $index }}">
                                <div class="fitur-number">
                                    {{ $loop->iteration }}
                                </div>
                                <input 
                                    type="text" 
                                    name="fitur[]" 
                                    value="{{ $fitur }}"
                                    class="input-field flex-1" 
                                    placeholder="Contoh: Durasi 30 detik"
                                    required
                                >
                                @if($loop->index > 0)
                                <button 
                                    type="button" 
                                    class="btn-danger-outline flex-shrink-0 remove-fitur-btn">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    
                    <button type="button" id="tambah-fitur-btn" 
                            class="btn-secondary mt-3 w-full sm:w-auto">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Tambah Fitur
                    </button>
                    
                    @error('fitur')
                    <p class="error-message">{{ $message }}</p>
                    @enderror
                    @if($errors->has('fitur.*'))
                        @foreach($errors->get('fitur.*') as $messages)
                            @foreach($messages as $message)
                                <p class="error-message">{{ $message }}</p>
                            @endforeach
                        @endforeach
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="durasi_pengerjaan" class="form-label">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Durasi Pengerjaan (Hari) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="durasi_pengerjaan" id="durasi_pengerjaan" 
                               value="{{ old('durasi_pengerjaan', $paket->durasi_pengerjaan) }}" 
                               class="input-field w-full @error('durasi_pengerjaan') border-red-500 @enderror" required>
                        @error('durasi_pengerjaan')
                        <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jumlah_revisi" class="form-label">
                            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Jumlah Revisi <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="jumlah_revisi" id="jumlah_revisi" 
                               value="{{ old('jumlah_revisi', $paket->jumlah_revisi) }}" 
                               class="input-field w-full @error('jumlah_revisi') border-red-500 @enderror" required>
                        @error('jumlah_revisi')
                        <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" 
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4" 
                               {{ old('is_active', $paket->is_active) ? 'checked' : '' }}>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Aktifkan paket</span>
                        </div>
                    </label>
                </div>

                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.paket.index') }}" class="btn-secondary text-center">
                        Batal
                    </a>
                    <button type="submit" class="btn-primary">
                        Update Paket
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing edit fitur form...');
    
    // Inisialisasi nomor fitur
    updateFiturNumbers();
    
    // Event listener untuk tombol Tambah Fitur
    const tambahFiturBtn = document.getElementById('tambah-fitur-btn');
    if (tambahFiturBtn) {
        console.log('Tambah Fitur button found');
        tambahFiturBtn.addEventListener('click', function() {
            console.log('Tambah Fitur button clicked');
            addFitur();
        });
    } else {
        console.error('Tambah Fitur button not found!');
    }
    
    // Event delegation untuk tombol hapus fitur
    const fiturFields = document.getElementById('fitur-fields');
    if (fiturFields) {
        fiturFields.addEventListener('click', function(e) {
            if (e.target.closest('.remove-fitur-btn')) {
                const fiturItem = e.target.closest('.fitur-item');
                if (fiturItem) {
                    removeFitur(fiturItem);
                }
            }
        });
    }
    
    // Animasi page load
    const isFirstLoad = !sessionStorage.getItem('paket_edit_loaded');
    if (isFirstLoad) {
        document.body.classList.add('page-load');
        sessionStorage.setItem('paket_edit_loaded', 'true');
        setTimeout(() => {
            document.body.classList.remove('page-load');
        }, 1000);
    }
});

// Fungsi untuk menambah fitur
function addFitur() {
    console.log('addFitur function called');
    const fiturFields = document.getElementById('fitur-fields');
    if (!fiturFields) {
        console.error('fitur-fields element not found!');
        return;
    }
    
    const fiturCount = fiturFields.querySelectorAll('.fitur-item').length;
    console.log('Current fitur count:', fiturCount);
    
    const newFitur = document.createElement('div');
    newFitur.className = 'flex gap-2 fitur-item';
    newFitur.setAttribute('data-index', fiturCount);
    
    newFitur.innerHTML = `
        <div class="fitur-number">
            ${fiturCount + 1}
        </div>
        <input 
            type="text" 
            name="fitur[]" 
            class="input-field flex-1" 
            placeholder="Contoh: Durasi 30 detik"
            required
        >
        <button 
            type="button" 
            class="btn-danger-outline flex-shrink-0 remove-fitur-btn">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </button>
    `;
    
    fiturFields.appendChild(newFitur);
    updateFiturNumbers();
    console.log('New fitur added successfully');
}

// Fungsi untuk menghapus fitur
function removeFitur(fiturItem) {
    const fiturFields = document.getElementById('fitur-fields');
    const fiturItems = fiturFields.querySelectorAll('.fitur-item');
    
    // Pastikan minimal ada 1 fitur
    if (fiturItems.length > 1) {
        fiturItem.remove();
        updateFiturNumbers();
    }
}

// Fungsi untuk update nomor fitur
function updateFiturNumbers() {
    const fiturItems = document.querySelectorAll('.fitur-item');
    fiturItems.forEach((item, index) => {
        const numberDiv = item.querySelector('.fitur-number');
        if (numberDiv) {
            numberDiv.textContent = index + 1;
        }
        item.setAttribute('data-index', index);
        
        // Tampilkan tombol hapus hanya jika bukan fitur pertama
        const removeBtn = item.querySelector('.remove-fitur-btn');
        if (removeBtn) {
            if (index === 0) {
                removeBtn.style.display = 'none';
            } else {
                removeBtn.style.display = 'inline-flex';
            }
        }
    });
}

// CSS untuk nomor fitur
const style = document.createElement('style');
style.textContent = `
    .fitur-number {
        flex-shrink: 0;
        width: 2rem;
        height: 2.5rem;
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.875rem;
        font-weight: 600;
    }
`;
document.head.appendChild(style);

window.addEventListener('beforeunload', function(e) {
    if (e.target.activeElement.tagName === 'A' && 
        e.target.activeElement.getAttribute('href') !== '#') {
        sessionStorage.removeItem('paket_edit_loaded');
    }
});
</script>
@endsection