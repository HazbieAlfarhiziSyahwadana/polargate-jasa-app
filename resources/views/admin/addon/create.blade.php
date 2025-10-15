@extends('layouts.admin')

@section('title', 'Tambah Add-on')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Tambah Add-on Baru</h1>
    <p class="text-gray-600">Isi form di bawah untuk menambah add-on</p>
</div>

<div class="card max-w-4xl">
    <form action="{{ route('admin.addon.store') }}" method="POST">
        @csrf

        <!-- Layanan -->
        <div>
            <label for="layanan_id" class="block text-gray-700 text-sm font-medium mb-2">Layanan <span class="text-red-500">*</span></label>
            <select name="layanan_id" id="layanan_id" class="input-field @error('layanan_id') border-red-500 @enderror" required>
                <option value="">Pilih Layanan</option>
                @foreach($layanan as $item)
                <option value="{{ $item->id }}" {{ old('layanan_id') == $item->id ? 'selected' : '' }}>
                    {{ $item->nama_layanan }} ({{ $item->kategori }})
                </option>
                @endforeach
            </select>
            @error('layanan_id')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
            <!-- Nama Add-on -->
            <div>
                <label for="nama_addon" class="block text-gray-700 text-sm font-medium mb-2">Nama Add-on <span class="text-red-500">*</span></label>
                <input type="text" name="nama_addon" id="nama_addon" value="{{ old('nama_addon') }}" class="input-field @error('nama_addon') border-red-500 @enderror" placeholder="Render 4K" required>
                @error('nama_addon')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Harga -->
            <div>
                <label for="harga" class="block text-gray-700 text-sm font-medium mb-2">Harga <span class="text-red-500">*</span></label>
                <input type="number" name="harga" id="harga" value="{{ old('harga') }}" class="input-field @error('harga') border-red-500 @enderror" placeholder="2000000" required>
                @error('harga')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Deskripsi -->
        <div class="mt-4">
            <label for="deskripsi" class="block text-gray-700 text-sm font-medium mb-2">Deskripsi <span class="text-red-500">*</span></label>
            <textarea name="deskripsi" id="deskripsi" rows="3" class="input-field @error('deskripsi') border-red-500 @enderror" placeholder="Deskripsi add-on" required>{{ old('deskripsi') }}</textarea>
            @error('deskripsi')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Status Aktif -->
        <div class="mt-4">
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-primary-600" {{ old('is_active', true) ? 'checked' : '' }}>
                <span class="ml-2 text-sm text-gray-700">Aktifkan add-on</span>
            </label>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end space-x-3 mt-6">
            <a href="{{ route('admin.addon.index') }}" class="btn-secondary">Batal</a>
            <button type="submit" class="btn-primary">Simpan Add-on</button>
        </div>
    </form>
</div>
@endsection