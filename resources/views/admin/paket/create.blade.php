@extends('layouts.admin')

@section('title', 'Tambah Paket')

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
    }

    .input-field:focus, select:focus, textarea:focus {
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
        @apply flex items-center gap-2 text-gray-700 text-sm font-semibold mb-2;
    }

    .error-message {
        @apply text-red-500 text-xs mt-2 flex items-center gap-1;
    }

    .error-message::before {
        content: "⚠";
    }

    .btn-danger-outline {
        @apply px-3 py-2 border-2 border-red-500 text-red-600 rounded-lg hover:bg-red-50 font-medium inline-flex items-center justify-center gap-2 transition-all duration-200;
    }

    .btn-danger-outline:hover {
        transform: scale(1.05);
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
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Tambah Paket Baru</h1>
                <p class="text-sm text-gray-600 mt-1">Isi form di bawah untuk menambah paket</p>
            </div>
        </div>
    </div>

    <div class="max-w-4xl animate-slide">
        <div class="card">
            <form action="{{ route('admin.paket.store') }}" method="POST" x-data="paketForm()" class="space-y-6">
                @csrf

                <!-- Layanan -->
                <div class="relative" x-data="{ open: false, selected: {{ old('layanan_id') ?? 'null' }} }">
                    <label class="form-label">
                        <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Layanan <span class="text-red-500">*</span>
                    </label>
                    
                    <input type="hidden" name="layanan_id" :value="selected" required>
                    
                    <button type="button" @click="open = !open" 
                            class="w-full input-field text-left flex items-center justify-between @error('layanan_id') border-red-500 @enderror"
                            :class="{ 'border-primary-500': open }">
                        <span x-show="!selected" class="text-gray-400">Pilih Layanan</span>
                        @foreach($layanan as $item)
                        <span x-show="selected == {{ $item->id }}" class="flex items-center gap-2 truncate">
                            <span class="font-medium truncate">{{ $item->nama_layanan }}</span>
                            <span class="hidden sm:inline text-gray-500 text-xs flex-shrink-0">• {{ $item->kategori }}</span>
                        </span>
                        @endforeach
                        <svg class="w-5 h-5 text-gray-400 transition-transform flex-shrink-0" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <div x-show="open" 
                         @click.away="open = false" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute z-50 w-full mt-1 bg-white rounded-lg shadow-lg border border-gray-200 max-h-60 overflow-y-auto"
                         style="display: none;">
                        @foreach($layanan as $item)
                        <button type="button" 
                                @click="selected = {{ $item->id }}; open = false"
                                :class="{ 'bg-primary-50 border-l-4 border-primary-500': selected == {{ $item->id }} }"
                                class="w-full px-4 py-3 text-left hover:bg-gray-50 transition-colors border-b border-gray-100 last:border-b-0">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                                <span class="font-medium text-gray-900 text-sm">{{ $item->nama_layanan }}</span>
                                <span class="text-xs px-2 py-1 bg-gray-100 rounded text-gray-600 w-fit">{{ $item->kategori }}</span>
                            </div>
                        </button>
                        @endforeach
                    </div>
                    
                    @error('layanan_id')
                    <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nama_paket" class="form-label">
                            <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            Nama Paket <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_paket" id="nama_paket" 
                               value="{{ old('nama_paket') }}" 
                               class="input-field w-full @error('nama_paket') border-red-500 @enderror" 
                               placeholder="Paket Basic" required>
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
                               value="{{ old('harga') }}" 
                               class="input-field w-full @error('harga') border-red-500 @enderror" 
                               placeholder="5000000" required>
                        @error('harga')
                        <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="deskripsi" class="form-label">
                        <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                        </svg>
                        Deskripsi <span class="text-red-500">*</span>
                    </label>
                    <textarea name="deskripsi" id="deskripsi" rows="4" 
                              class="input-field w-full @error('deskripsi') border-red-500 @enderror" 
                              placeholder="Deskripsi paket" required>{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                    <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label mb-3">
                        <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        Fitur Paket <span class="text-red-500">*</span>
                    </label>
                    
                    <div class="space-y-3">
                        <template x-for="(fitur, index) in fiturList" :key="index">
                            <div class="flex gap-2">
                                <div class="flex-shrink-0 w-8 h-10 bg-gradient-to-br from-primary-500 to-primary-600 rounded-lg flex items-center justify-center text-white text-sm font-semibold">
                                    <span x-text="index + 1"></span>
                                </div>
                                <input 
                                    type="text" 
                                    :name="'fitur[' + index + ']'" 
                                    x-model="fiturList[index]"
                                    class="input-field flex-1" 
                                    placeholder="Contoh: Durasi 30 detik"
                                    required
                                >
                                <button 
                                    type="button" 
                                    @click="removeFitur(index)" 
                                    class="btn-danger-outline flex-shrink-0"
                                    x-show="fiturList.length > 1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>
                    
                    <button type="button" @click="addFitur" 
                            class="btn-secondary mt-3 w-full sm:w-auto">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Tambah Fitur
                    </button>
                    @error('fitur')
                    <p class="error-message">{{ $message }}</p>
                    @enderror
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
                               value="{{ old('durasi_pengerjaan') }}" 
                               class="input-field w-full @error('durasi_pengerjaan') border-red-500 @enderror" 
                               placeholder="14" required>
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
                               value="{{ old('jumlah_revisi', 0) }}" 
                               class="input-field w-full @error('jumlah_revisi') border-red-500 @enderror" 
                               placeholder="2" required>
                        @error('jumlah_revisi')
                        <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" 
                               class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 w-4 h-4" 
                               {{ old('is_active', true) ? 'checked' : '' }}>
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
                        Simpan Paket
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function paketForm() {
    return {
        fiturList: [''],
        addFitur() {
            this.fiturList.push('');
        },
        removeFitur(index) {
            this.fiturList.splice(index, 1);
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const isFirstLoad = !sessionStorage.getItem('paket_create_loaded');
    
    if (isFirstLoad) {
        document.body.classList.add('page-load');
        sessionStorage.setItem('paket_create_loaded', 'true');
        
        setTimeout(() => {
            document.body.classList.remove('page-load');
        }, 1000);
    }
});

window.addEventListener('beforeunload', function(e) {
    if (e.target.activeElement.tagName === 'A' && 
        e.target.activeElement.getAttribute('href') !== '#') {
        sessionStorage.removeItem('paket_create_loaded');
    }
});
</script>
@endpush
@endsection