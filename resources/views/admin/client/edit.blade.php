@extends('layouts.admin')

@section('title', 'Edit Client')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Edit Data Client</h1>
    <p class="text-gray-600">Update informasi client</p>
</div>

<div class="card max-w-4xl">
    <form action="{{ route('admin.client.update', $user) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nama Lengkap -->
            <div>
                <label for="name" class="block text-gray-700 text-sm font-medium mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="input-field @error('name') border-red-500 @enderror" required>
                @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="input-field @error('email') border-red-500 @enderror" required>
                @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Foto Profil -->
        <div class="mt-4">
            <label for="foto" class="block text-gray-700 text-sm font-medium mb-2">Foto Profil</label>
            
            @if($user->foto)
            <div class="mb-2">
                <img src="{{ $user->foto_url }}" alt="{{ $user->name }}" class="w-32 h-32 object-cover rounded-lg">
                <p class="text-xs text-gray-500 mt-1">Foto saat ini</p>
            </div>
            @endif
            
            <input type="file" name="foto" id="foto" accept="image/*" class="input-field @error('foto') border-red-500 @enderror" onchange="previewImage(event)">
            @error('foto')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs text-gray-500 mt-1">Max 2MB (jpg, jpeg, png). Kosongkan jika tidak ingin mengubah</p>
            <img id="preview" class="mt-2 w-32 h-32 object-cover rounded-lg hidden">
        </div>

        <!-- Alamat -->
        <div class="mt-4">
            <label for="alamat" class="block text-gray-700 text-sm font-medium mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
            <textarea name="alamat" id="alamat" rows="3" class="input-field @error('alamat') border-red-500 @enderror" required>{{ old('alamat', $user->alamat) }}</textarea>
            @error('alamat')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
            <!-- Jenis Kelamin -->
            <div>
                <label for="jenis_kelamin" class="block text-gray-700 text-sm font-medium mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                <select name="jenis_kelamin" id="jenis_kelamin" class="input-field @error('jenis_kelamin') border-red-500 @enderror" required>
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="Laki-laki" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('jenis_kelamin')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tanggal Lahir -->
            <div>
                <label for="tanggal_lahir" class="block text-gray-700 text-sm font-medium mb-2">Tanggal Lahir <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', $user->tanggal_lahir->format('Y-m-d')) }}" class="input-field @error('tanggal_lahir') border-red-500 @enderror" max="{{ date('Y-m-d') }}" required>
                @error('tanggal_lahir')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
            <!-- Nomor Telepon -->
            <div>
                <label for="no_telepon" class="block text-gray-700 text-sm font-medium mb-2">Nomor Telepon <span class="text-red-500">*</span></label>
                <input type="tel" name="no_telepon" id="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}" class="input-field @error('no_telepon') border-red-500 @enderror" required>
                @error('no_telepon')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Status Aktif -->
        <div class="mt-4">
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-primary-600" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                <span class="ml-2 text-sm text-gray-700">Akun Aktif</span>
            </label>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end space-x-3 mt-6">
            <a href="{{ route('admin.client.show', $user) }}" class="btn-secondary">Batal</a>
            <button type="submit" class="btn-primary">Update Client</button>
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