@extends('layouts.app')

@section('title', 'Register - Polargate')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden" style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #60a5fa 100%);">
    <!-- Floating Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute w-96 h-96 bg-white opacity-5 rounded-full -top-20 -left-20 animate-pulse"></div>
        <div class="absolute w-80 h-80 bg-white opacity-5 rounded-full top-40 -right-20 animate-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute w-64 h-64 bg-white opacity-5 rounded-full -bottom-10 left-1/3 animate-pulse" style="animation-delay: 4s;"></div>
    </div>

    <div class="max-w-4xl w-full relative z-10">
        <!-- Back to Landing Page Button -->
        <div class="text-center mb-6">
            <a href="{{ route('landing') }}" class="inline-flex items-center px-6 py-2.5 bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-full transition-all duration-300 backdrop-blur-sm border border-white border-opacity-30">
                <i class="fas fa-arrow-left mr-2"></i>
                <span class="font-medium">Kembali ke Beranda</span>
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-2xl p-8 sm:p-10 backdrop-blur-lg">
            <!-- Logo & Title -->
            <div class="text-center mb-8">
                <div class="inline-block p-3 bg-gradient-to-br from-primary-500 to-primary-700 rounded-2xl mb-4 shadow-lg">
                    <i class="fas fa-user-plus text-white text-3xl"></i>
                </div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-primary-600 to-primary-800 bg-clip-text text-transparent mb-2">
                    Daftar Akun Baru
                </h1>
                <p class="text-gray-600">Lengkapi data diri Anda untuk membuat akun</p>
            </div>

            <!-- Error Messages -->
            @if($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-r-lg">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle mt-0.5 mr-3"></i>
                    <ul class="text-sm space-y-1">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif>

            <!-- Register Form -->
            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Personal Info Section -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-user text-primary-600 mr-2"></i>
                        Informasi Pribadi
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nama Lengkap -->
                        <div>
                            <label for="name" class="block text-gray-700 text-sm font-semibold mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="name" 
                                id="name" 
                                value="{{ old('name') }}"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition @error('name') border-red-500 @enderror" 
                                placeholder="John Doe"
                                required
                            >
                        </div>

                        <!-- Foto Profil -->
                        <div>
                            <label for="foto" class="block text-gray-700 text-sm font-semibold mb-2">
                                Foto Profil
                            </label>
                            <input 
                                type="file" 
                                name="foto" 
                                id="foto" 
                                accept="image/*"
                                class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition @error('foto') border-red-500 @enderror"
                            >
                            <p class="text-xs text-gray-500 mt-1">Max 2MB (jpg, jpeg, png)</p>
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="mt-4">
                        <label for="alamat" class="block text-gray-700 text-sm font-semibold mb-2">
                            Alamat Lengkap <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            name="alamat" 
                            id="alamat" 
                            rows="3"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition @error('alamat') border-red-500 @enderror" 
                            placeholder="Jl. Contoh No. 123, Jakarta"
                            required
                        >{{ old('alamat') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <!-- Jenis Kelamin -->
                        <div>
                            <label for="jenis_kelamin" class="block text-gray-700 text-sm font-semibold mb-2">
                                Jenis Kelamin <span class="text-red-500">*</span>
                            </label>
                            <select 
                                name="jenis_kelamin" 
                                id="jenis_kelamin" 
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition @error('jenis_kelamin') border-red-500 @enderror"
                                required
                            >
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <!-- Tanggal Lahir -->
                        <div>
                            <label for="tanggal_lahir" class="block text-gray-700 text-sm font-semibold mb-2">
                                Tanggal Lahir <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="date" 
                                name="tanggal_lahir" 
                                id="tanggal_lahir" 
                                value="{{ old('tanggal_lahir') }}"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition @error('tanggal_lahir') border-red-500 @enderror"
                                max="{{ date('Y-m-d') }}"
                                required
                            >
                        </div>
                    </div>
                </div>

                <!-- Contact Info Section -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-address-book text-primary-600 mr-2"></i>
                        Informasi Kontak
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nomor Telepon -->
                        <div>
                            <label for="no_telepon" class="block text-gray-700 text-sm font-semibold mb-2">
                                Nomor Telepon <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="tel" 
                                name="no_telepon" 
                                id="no_telepon" 
                                value="{{ old('no_telepon') }}"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition @error('no_telepon') border-red-500 @enderror" 
                                placeholder="081234567890"
                                required
                            >
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="email" 
                                name="email" 
                                id="email" 
                                value="{{ old('email') }}"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition @error('email') border-red-500 @enderror" 
                                placeholder="nama@email.com"
                                required
                            >
                        </div>
                    </div>
                </div>

                <!-- Security Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-lock text-primary-600 mr-2"></i>
                        Keamanan Akun
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="password" 
                                name="password" 
                                id="password" 
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition @error('password') border-red-500 @enderror" 
                                placeholder="Min. 8 karakter"
                                required
                            >
                        </div>

                        <!-- Konfirmasi Password -->
                        <div>
                            <label for="password_confirmation" class="block text-gray-700 text-sm font-semibold mb-2">
                                Konfirmasi Password <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="password" 
                                name="password_confirmation" 
                                id="password_confirmation" 
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition" 
                                placeholder="Ulangi password"
                                required
                            >
                        </div>
                    </div>
                </div>

                <!-- Terms & Conditions -->
                <div class="bg-gray-50 rounded-2xl p-4 border-2 border-gray-100">
                    <label class="flex items-start cursor-pointer">
                        <input 
                            type="checkbox" 
                            name="terms" 
                            class="mt-1 w-5 h-5 rounded border-gray-300 text-primary-600 focus:ring-primary-500 @error('terms') border-red-500 @enderror cursor-pointer"
                            required
                        >
                        <span class="ml-3 text-sm text-gray-700">
                            Saya menyetujui <a href="#" class="text-primary-600 hover:underline font-semibold">Syarat dan Ketentuan</a> yang berlaku serta <a href="#" class="text-primary-600 hover:underline font-semibold">Kebijakan Privasi</a>
                        </span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-user-plus mr-2"></i>Daftar Sekarang
                </button>
            </form>

            <!-- Login Link -->
            <div class="mt-8 text-center">
                <p class="text-gray-600">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700 font-semibold hover:underline">
                        Login Sekarang <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                </p>
            </div>
        </div>

        <!-- Footer Text -->
        <div class="text-center mt-6">
            <p class="text-white text-opacity-80 text-sm">
                © 2025 PT Polargate Indonesia Kreasi
            </p>
        </div>
    </div>
</div>

<style>
@keyframes pulse {
    0%, 100% {
        opacity: 0.05;
        transform: scale(1);
    }
    50% {
        opacity: 0.1;
        transform: scale(1.05);
    }
}

.animate-pulse {
    animation: pulse 6s ease-in-out infinite;
}
</style>
@endsection