@extends('layouts.admin')

@section('title', 'Tambah Paket')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Tambah Paket Baru</h1>
    <p class="text-gray-600">Isi form di bawah untuk menambah paket</p>
</div>

<div class="card max-w-4xl">
    <form action="{{ route('admin.paket.store') }}" method="POST" x-data="paketForm()">
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
            <!-- Nama Paket -->
            <div>
                <label for="nama_paket" class="block text-gray-700 text-sm font-medium mb-2">Nama Paket <span class="text-red-500">*</span></label>
                <input type="text" name="nama_paket" id="nama_paket" value="{{ old('nama_paket') }}" class="input-field @error('nama_paket') border-red-500 @enderror" placeholder="Paket Basic" required>
                @error('nama_paket')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Harga -->
            <div>
                <label for="harga" class="block text-gray-700 text-sm font-medium mb-2">Harga <span class="text-red-500">*</span></label>
                <input type="number" name="harga" id="harga" value="{{ old('harga') }}" class="input-field @error('harga') border-red-500 @enderror" placeholder="5000000" required>
                @error('harga')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Deskripsi -->
        <div class="mt-4">
            <label for="deskripsi" class="block text-gray-700 text-sm font-medium mb-2">Deskripsi <span class="text-red-500">*</span></label>
            <textarea name="deskripsi" id="deskripsi" rows="3" class="input-field @error('deskripsi') border-red-500 @enderror" placeholder="Deskripsi paket" required>{{ old('deskripsi') }}</textarea>
            @error('deskripsi')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Fitur -->
        <div class="mt-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Fitur Paket <span class="text-red-500">*</span></label>
            
            <template x-for="(fitur, index) in fiturList" :key="index">
                <div class="flex gap-2 mb-2">
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
                        class="btn-danger"
                        x-show="fiturList.length > 1"
                    >Hapus</button>
                </div>
            </template>
            
            <button type="button" @click="addFitur" class="btn-secondary mt-2">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Tambah Fitur
            </button>
            @error('fitur')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
            <!-- Durasi Pengerjaan -->
            <div>
                <label for="durasi_pengerjaan" class="block text-gray-700 text-sm font-medium mb-2">Durasi Pengerjaan (Hari) <span class="text-red-500">*</span></label>
                <input type="number" name="durasi_pengerjaan" id="durasi_pengerjaan" value="{{ old('durasi_pengerjaan') }}" class="input-field @error('durasi_pengerjaan') border-red-500 @enderror" placeholder="14" required>
                @error('durasi_pengerjaan')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Jumlah Revisi -->
            <div>
                <label for="jumlah_revisi" class="block text-gray-700 text-sm font-medium mb-2">Jumlah Revisi <span class="text-red-500">*</span></label>
                <input type="number" name="jumlah_revisi" id="jumlah_revisi" value="{{ old('jumlah_revisi', 0) }}" class="input-field @error('jumlah_revisi') border-red-500 @enderror" placeholder="2" required>
                @error('jumlah_revisi')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Status Aktif -->
        <div class="mt-4">
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-primary-600" {{ old('is_active', true) ? 'checked' : '' }}>
                <span class="ml-2 text-sm text-gray-700">Aktifkan paket</span>
            </label>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end space-x-3 mt-6">
            <a href="{{ route('admin.paket.index') }}" class="btn-secondary">Batal</a>
            <button type="submit" class="btn-primary">Simpan Paket</button>
        </div>
    </form>
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
</script>
@endpush
@endsection