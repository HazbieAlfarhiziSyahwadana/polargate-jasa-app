@extends('layouts.app')

@section('title', 'Login - Polargate')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden" style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #60a5fa 100%);">
    <!-- Floating Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute w-96 h-96 bg-white opacity-5 rounded-full -top-20 -left-20 animate-pulse"></div>
        <div class="absolute w-80 h-80 bg-white opacity-5 rounded-full top-40 -right-20 animate-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute w-64 h-64 bg-white opacity-5 rounded-full -bottom-10 left-1/3 animate-pulse" style="animation-delay: 4s;"></div>
    </div>

    <div class="max-w-md w-full relative z-10">
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
                    <i class="fas fa-user-shield text-white text-3xl"></i>
                </div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-primary-600 to-primary-800 bg-clip-text text-transparent mb-2">
                    Selamat Datang
                </h1>
                <p class="text-gray-600">Silakan login untuk melanjutkan</p>
            </div>

            <!-- Flash Messages -->
            @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-r-lg flex items-center">
                <i class="fas fa-check-circle mr-3"></i>
                <span class="text-sm">{{ session('success') }}</span>
            </div>
            @endif

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
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-envelope mr-2 text-primary-600"></i>Email
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

                <div>
                    <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-lock mr-2 text-primary-600"></i>Password
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition @error('password') border-red-500 @enderror" 
                            placeholder="Masukkan password"
                            required
                        >
                        <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500 cursor-pointer">
                        <span class="ml-2 text-sm text-gray-600">Ingat Saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-semibold py-3.5 px-6 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login Sekarang
                </button>
            </form>

            <!-- Register Link -->
            <div class="mt-8 text-center">
                <p class="text-gray-600">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="text-primary-600 hover:text-primary-700 font-semibold hover:underline">
                        Daftar Sekarang <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                </p>
            </div>

            <!-- Divider -->
            <div class="relative my-8">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-white text-gray-500">Informasi Demo</span>
                </div>
            </div>

            <!-- Demo Account Info -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-5 border border-blue-100">
                <div class="flex items-start mb-3">
                    <i class="fas fa-info-circle text-primary-600 text-lg mr-3 mt-0.5"></i>
                    <div>
                        <p class="text-sm font-semibold text-gray-800 mb-2">Akun Demo untuk Testing:</p>
                        <div class="space-y-2 text-xs text-gray-700">
                            <div class="flex items-center">
                                <span class="font-semibold min-w-20">Admin:</span>
                                <span class="ml-2">admin@polargate.com / admin123</span>
                            </div>
                            <div class="flex items-center">
                                <span class="font-semibold min-w-20">Client:</span>
                                <span class="ml-2">budi@email.com / client123</span>
                            </div>
                        </div>
                    </div>
                </div>
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

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}
</script>

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