@extends('layouts.admin')

@section('title', 'Edit Layanan')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Edit Layanan</h1>
    <p class="text-gray-600">Update informasi layanan</p>
</div>

<div class="card max-w-4xl">
    <form action="{{ route('admin.layanan.update', $layanan) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Kategori -->
            <div>
                <label for="kategori" class="block text-gray-700 text-sm font-medium mb-2">Kategori <span class="text-red-500">*</span></label>
                <select name="kategori" id="kategori" class="input-field @error('kategori') border-red-500 @enderror" required>
                    <option value="">Pilih Kategori</option>
                    <option value="Multimedia" {{ old('kategori', $layanan->kategori) == 'Multimedia' ? 'selected' : '' }}>Multimedia</option>
                    <option value="IT" {{ old('kategori', $layanan->kategori) == 'IT' ? 'selected' : '' }}>IT</option>
                </select>
                @error('kategori')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nama Layanan -->
            <div>
                <label for="nama_layanan" class="block text-gray-700 text-sm font-medium mb-2">Nama Layanan <span class="text-red-500">*</span></label>
                <input type="text" name="nama_layanan" id="nama_layanan" value="{{ old('nama_layanan', $layanan->nama_layanan) }}" class="input-field @error('nama_layanan') border-red-500 @enderror" required>
                @error('nama_layanan')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Deskripsi -->
        <div class="mt-4">
            <label for="deskripsi" class="block text-gray-700 text-sm font-medium mb-2">Deskripsi <span class="text-red-500">*</span></label>
            <textarea name="deskripsi" id="deskripsi" rows="4" class="input-field @error('deskripsi') border-red-500 @enderror" required>{{ old('deskripsi', $layanan->deskripsi) }}</textarea>
            @error('deskripsi')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
            <!-- Gambar -->
            <div>
                <label for="gambar" class="block text-gray-700 text-sm font-medium mb-2">Gambar Layanan</label>
                
                @if($layanan->gambar)
                <div class="mb-2">
                    <img src="{{ $layanan->gambar_url }}" alt="{{ $layanan->nama_layanan }}" class="w-32 h-32 object-cover rounded-lg">
                    <p class="text-xs text-gray-500 mt-1">Gambar saat ini</p>
                </div>
                @endif
                
                <input type="file" name="gambar" id="gambar" accept="image/*" class="input-field @error('gambar') border-red-500 @enderror" onchange="previewImage(event)">
                @error('gambar')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Max 2MB (jpg, jpeg, png). Kosongkan jika tidak ingin mengubah</p>
                <img id="preview" class="mt-2 w-32 h-32 object-cover rounded-lg hidden">
            </div>

            <!-- Harga Mulai -->
            <div>
                <label for="harga_mulai" class="block text-gray-700 text-sm font-medium mb-2">Harga Mulai <span class="text-red-500">*</span></label>
                <input type="number" name="harga_mulai" id="harga_mulai" value="{{ old('harga_mulai', $layanan->harga_mulai) }}" class="input-field @error('harga_mulai') border-red-500 @enderror" required>
                @error('harga_mulai')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Status Aktif -->
        <div class="mt-4">
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-primary-600" {{ old('is_active', $layanan->is_active) ? 'checked' : '' }}>
                <span class="ml-2 text-sm text-gray-700">Aktifkan layanan</span>
            </label>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end space-x-3 mt-6">
            <a href="{{ route('admin.layanan.index') }}" class="btn-secondary">Batal</a>
            <button type="submit" class="btn-primary">Update Layanan</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endpush
@endsection