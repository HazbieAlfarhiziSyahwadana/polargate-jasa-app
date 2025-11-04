@extends('layouts.admin')

@section('title', 'Tambah Add-on')

@section('content')
<div class="animate-fade-in">
    <!-- Header -->
    <div class="mb-8 animate-slide-down">
        <div class="flex items-center gap-3 mb-3">
            <a href="{{ route('admin.addon.index') }}" 
               class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors duration-200">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800">Tambah Add-on Baru</h1>
                <p class="text-gray-600 mt-1">Isi form di bawah untuk menambah add-on</p>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="max-w-4xl animate-slide-up">
        <div class="card">
            <form action="{{ route('admin.addon.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Layanan -->
                <div class="form-group-animated" x-data="{ open: false, selected: {{ old('layanan_id') ?? 'null' }} }">
                    <label class="form-label">
                        <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Layanan <span class="text-red-500">*</span>
                    </label>
                    
                    <!-- Hidden Input -->
                    <input type="hidden" name="layanan_id" :value="selected" required>
                    
                    <!-- Custom Select Button -->
                    <button type="button" @click="open = !open" 
                            class="w-full input-field text-left flex items-center justify-between @error('layanan_id') border-red-500 @enderror"
                            :class="{ 'border-primary-500': open }">
                        <span x-show="!selected" class="text-gray-400">Pilih Layanan</span>
                        @foreach($layanan as $item)
                        <span x-show="selected == {{ $item->id }}" class="flex items-center gap-2">
                            <span class="font-medium">{{ $item->nama_layanan }}</span>
                            <span class="hidden sm:inline text-gray-500 text-sm">• {{ $item->kategori }}</span>
                        </span>
                        @endforeach
                        <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <!-- Dropdown Options -->
                    <div x-show="open" @click.away="open = false" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         class="absolute z-50 w-full mt-2 bg-white rounded-xl shadow-xl border border-gray-200 max-h-64 overflow-y-auto">
                        @foreach($layanan as $item)
                        <button type="button" @click="selected = {{ $item->id }}; open = false"
                                :class="{ 'bg-primary-50 border-l-4 border-primary-500': selected == {{ $item->id }} }"
                                class="w-full px-4 py-3 text-left hover:bg-gray-50 transition-colors duration-150 border-b border-gray-100 last:border-b-0">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                                <span class="font-medium text-gray-900">{{ $item->nama_layanan }}</span>
                                <span class="text-xs sm:text-sm px-2 py-1 bg-gray-100 rounded-md text-gray-600 w-fit">{{ $item->kategori }}</span>
                            </div>
                        </button>
                        @endforeach
                    </div>
                    
                    @error('layanan_id')
                    <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Add-on -->
                    <div class="form-group-animated">
                        <label for="nama_addon" class="form-label">
                            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            Nama Add-on <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_addon" id="nama_addon" 
                               value="{{ old('nama_addon') }}" 
                               class="input-field @error('nama_addon') border-red-500 @enderror" 
                               placeholder="Render 4K" required>
                        @error('nama_addon')
                        <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Harga -->
                    <div class="form-group-animated">
                        <label for="harga" class="form-label">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Harga <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                            <input type="number" name="harga" id="harga" 
                                   value="{{ old('harga') }}" 
                                   class="input-field @error('harga') border-red-500 @enderror pl-12" 
                                   placeholder="2000000" required>
                        </div>
                        @error('harga')
                        <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="form-group-animated">
                    <label for="deskripsi" class="form-label">
                        <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                        </svg>
                        Deskripsi <span class="text-red-500">*</span>
                    </label>
                    <textarea name="deskripsi" id="deskripsi" rows="4" 
                              class="input-field @error('deskripsi') border-red-500 @enderror resize-none" 
                              placeholder="Deskripsi add-on" required>{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                    <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Aktif -->
                <div class="form-group-animated">
                    <label class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200 cursor-pointer group">
                        <input type="checkbox" name="is_active" value="1" 
                               class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 w-5 h-5 transition-all duration-200" 
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-500 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Aktifkan add-on</span>
                        </div>
                    </label>
                </div>

                <!-- Buttons -->
                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.addon.index') }}" 
                       class="btn-secondary text-center transform hover:scale-105 transition-all duration-200">
                        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Batal
                    </a>
                    <button type="submit" 
                            class="btn-primary transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Add-on
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
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

.animate-fade-in {
    animation: fadeIn 0.6s ease-out;
}

.animate-slide-down {
    animation: slideDown 0.6s ease-out;
}

.animate-slide-up {
    animation: slideUp 0.6s ease-out;
}

.form-group-animated {
    animation: slideUp 0.6s ease-out;
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

/* Scrollbar Styling */
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
@endsection