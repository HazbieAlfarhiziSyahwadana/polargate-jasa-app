@extends('layouts.admin')

@section('title', 'Profil Admin')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Profil Admin</h1>
    <p class="text-gray-600">Kelola informasi profil dan keamanan akun admin</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Update Profil -->
    <div class="lg:col-span-2">
        <div class="card">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Informasi Profil</h2>
            
            <form action="{{ route('admin.profil.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Foto Profil -->
                <div class="mb-6 flex items-center">
                    <img src="{{ $user->foto_url }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-full object-cover mr-4" id="current-photo">
                    <div class="flex-1">
                        <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>
                        <input type="file" name="foto" id="foto" accept="image/*" class="input-field @error('foto') border-red-500 @enderror" onchange="previewImage(event)">
                        @error('foto')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Max 2MB (jpg, jpeg, png)</p>
                    </div>
                </div>

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

                <!-- Buttons -->
                <div class="flex justify-end mt-6">
                    <button type="submit" class="btn-primary">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Profil
                    </button>
                </div>
            </form>
        </div>

        <!-- Ganti Password -->
        <div class="card mt-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Ganti Password</h2>
            
            <form action="{{ route('admin.profil.change-password') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <!-- Password Lama -->
                    <div>
                        <label for="current_password" class="block text-gray-700 text-sm font-medium mb-2">Password Lama <span class="text-red-500">*</span></label>
                        <input type="password" name="current_password" id="current_password" class="input-field @error('current_password') border-red-500 @enderror" required>
                        @error('current_password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Baru -->
                    <div>
                        <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Password Baru <span class="text-red-500">*</span></label>
                        <input type="password" name="password" id="password" class="input-field @error('password') border-red-500 @enderror" required>
                        @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                    </div>

                    <!-- Konfirmasi Password -->
                    <div>
                        <label for="password_confirmation" class="block text-gray-700 text-sm font-medium mb-2">Konfirmasi Password Baru <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="input-field" required>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end mt-6">
                    <button type="submit" class="btn-primary">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Ganti Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Akun -->
    <div class="space-y-6">
        <div class="card">
            <h3 class="font-semibold text-gray-800 mb-4">Informasi Akun</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Role:</span>
                    <span class="badge-success">Super Admin</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Usia:</span>
                    <span class="font-medium text-gray-900">{{ $user->usia }} tahun</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Bergabung:</span>
                    <span class="font-medium text-gray-900">{{ $user->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>

        <div class="card bg-red-50 border border-red-200">
            <h3 class="font-semibold text-gray-800 mb-2">Peringatan Keamanan</h3>
            <ul class="text-sm text-gray-700 space-y-2">
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-red-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Jangan bagikan akses admin
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-red-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Gunakan password yang sangat kuat
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-red-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Logout setelah selesai bekerja
                </li>
            </ul>
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewImage(event) {
    const currentPhoto = document.getElementById('current-photo');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            currentPhoto.src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endpush
@endsection