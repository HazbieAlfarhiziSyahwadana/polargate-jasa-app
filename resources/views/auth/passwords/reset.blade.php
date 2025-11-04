@extends('layouts.app')

@section('title', 'Reset Password - Polargate')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden" style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #60a5fa 100%);">
    <!-- Floating Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute w-96 h-96 bg-white opacity-5 rounded-full -top-20 -left-20 animate-pulse"></div>
        <div class="absolute w-80 h-80 bg-white opacity-5 rounded-full top-40 -right-20 animate-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute w-64 h-64 bg-white opacity-5 rounded-full -bottom-10 left-1/3 animate-pulse" style="animation-delay: 4s;"></div>
    </div>

    <div class="max-w-md w-full relative z-10">
        <div class="bg-white rounded-3xl shadow-2xl p-8 sm:p-10 backdrop-blur-lg animate-fadeInUp hover:shadow-3xl transition-shadow duration-500">
            <!-- Logo & Title -->
            <div class="text-center mb-8 animate-fadeIn">
                <div class="inline-block p-3 bg-gradient-to-br from-primary-500 to-primary-700 rounded-2xl mb-4 shadow-lg transform hover:scale-110 hover:rotate-3 transition-all duration-300">
                    <i class="fas fa-lock-open text-white text-3xl"></i>
                </div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-primary-600 to-primary-800 bg-clip-text text-transparent mb-2 animate-gradient">
                    Reset Password
                </h1>
                <p class="text-gray-600 text-sm sm:text-base">Masukkan password baru Anda</p>
            </div>

            <!-- Flash Messages -->
            @if(session('status'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-r-lg flex items-start animate-slideInLeft">
                <i class="fas fa-check-circle mr-3 mt-0.5 flex-shrink-0"></i>
                <span class="text-sm">{{ session('status') }}</span>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-r-lg animate-shake">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle mt-0.5 mr-3 flex-shrink-0"></i>
                    <ul class="text-sm space-y-1">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <!-- Reset Password Form -->
            <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Email Field -->
                <div class="form-group animate-slideInLeft" style="animation-delay: 0.1s;">
                    <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-envelope mr-2 text-primary-600"></i>Email
                    </label>
                    <div class="input-wrapper">
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            value="{{ $email ?? old('email') }}"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-300 hover:border-primary-300 bg-gray-50 @error('email') border-red-500 @enderror" 
                            placeholder="nama@email.com"
                            required
                            readonly
                        >
                        <div class="input-border"></div>
                    </div>
                    @error('email')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="form-group animate-slideInLeft" style="animation-delay: 0.2s;">
                    <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-lock mr-2 text-primary-600"></i>Password Baru
                    </label>
                    <div class="input-wrapper relative">
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            class="w-full px-4 py-3 pr-12 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-300 hover:border-primary-300 @error('password') border-red-500 @enderror" 
                            placeholder="Masukkan password baru"
                            required
                            autofocus
                        >
                        <button type="button" onclick="togglePassword('password', 'toggleIcon1')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary-600 transition-all duration-300 hover:scale-110 focus:outline-none">
                            <i class="fas fa-eye" id="toggleIcon1"></i>
                        </button>
                        <div class="input-border"></div>
                    </div>
                    <p class="mt-1 text-xs text-gray-500 flex items-center">
                        <i class="fas fa-info-circle mr-1"></i>
                        Minimal 8 karakter
                    </p>
                    @error('password')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation Field -->
                <div class="form-group animate-slideInLeft" style="animation-delay: 0.3s;">
                    <label for="password_confirmation" class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-lock mr-2 text-primary-600"></i>Konfirmasi Password
                    </label>
                    <div class="input-wrapper relative">
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation" 
                            class="w-full px-4 py-3 pr-12 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-300 hover:border-primary-300" 
                            placeholder="Konfirmasi password baru"
                            required
                        >
                        <button type="button" onclick="togglePassword('password_confirmation', 'toggleIcon2')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary-600 transition-all duration-300 hover:scale-110 focus:outline-none">
                            <i class="fas fa-eye" id="toggleIcon2"></i>
                        </button>
                        <div class="input-border"></div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-semibold py-3.5 px-6 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-[1.02] active:scale-[0.98] animate-slideInLeft" style="animation-delay: 0.4s;">
                    <i class="fas fa-check-circle mr-2"></i>Reset Password
                </button>
            </form>

            <!-- Back to Login -->
            <div class="mt-6 text-center animate-fadeIn" style="animation-delay: 0.5s;">
                <a href="{{ route('login') }}" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-semibold group transition-all duration-300">
                    <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform duration-300"></i>
                    Kembali ke Login
                </a>
            </div>

            <!-- Security Info -->
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-xl animate-fadeIn" style="animation-delay: 0.6s;">
                <div class="flex items-start">
                    <i class="fas fa-shield-alt text-blue-600 mt-0.5 mr-3 flex-shrink-0"></i>
                    <div class="text-xs text-blue-800">
                        <p class="font-semibold mb-1">Tips Keamanan:</p>
                        <ul class="space-y-1 list-disc list-inside">
                            <li>Gunakan kombinasi huruf besar, kecil, dan angka</li>
                            <li>Jangan gunakan password yang mudah ditebak</li>
                            <li>Simpan password Anda dengan aman</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Text -->
        <div class="text-center mt-6 animate-fadeIn" style="animation-delay: 0.7s;">
            <p class="text-white text-opacity-80 text-sm">
                © {{ date('Y') }} PT Polargate Indonesia Kreasi
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script>
function togglePassword(inputId, iconId) {
    const passwordInput = document.getElementById(inputId);
    const toggleIcon = document.getElementById(iconId);
    
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

// Add focus effect to inputs
document.querySelectorAll('input[type="email"], input[type="password"]').forEach(input => {
    input.addEventListener('focus', function() {
        this.parentElement.classList.add('input-focused');
    });
    
    input.addEventListener('blur', function() {
        this.parentElement.classList.remove('input-focused');
    });
});

// Password strength indicator
const passwordInput = document.getElementById('password');
if (passwordInput) {
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        const strength = checkPasswordStrength(password);
        // You can add visual feedback here
    });
}

function checkPasswordStrength(password) {
    let strength = 0;
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]+/)) strength++;
    if (password.match(/[A-Z]+/)) strength++;
    if (password.match(/[0-9]+/)) strength++;
    if (password.match(/[$@#&!]+/)) strength++;
    return strength;
}
</script>
@endpush

@push('styles')
<style>
/* Reuse same animations as email.blade.php */
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

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fadeInUp {
    animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.animate-fadeIn {
    animation: fadeIn 0.8s ease-out forwards;
    opacity: 0;
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.animate-slideInLeft {
    animation: slideInLeft 0.6s ease-out forwards;
    opacity: 0;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}

.animate-shake {
    animation: shake 0.5s ease-in-out;
}

@keyframes gradient {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}

.animate-gradient {
    background-size: 200% auto;
    animation: gradient 3s ease infinite;
}

.input-wrapper {
    position: relative;
}

.input-border {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background: linear-gradient(to right, #2563eb, #3b82f6);
    transition: width 0.4s ease;
    border-radius: 0 0 12px 12px;
}

.input-focused .input-border {
    width: 100%;
}

.form-group {
    transition: transform 0.3s ease;
}

.form-group:hover {
    transform: translateX(3px);
}

.shadow-3xl {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

@media (max-width: 640px) {
    .animate-fadeInUp {
        animation: fadeInUp 0.4s ease-out;
    }
    
    .animate-slideInLeft {
        animation: slideInLeft 0.4s ease-out forwards;
    }
}

* {
    -webkit-tap-highlight-color: transparent;
}

button, a, input, label {
    transition: all 0.3s ease;
}
</style>
@endpush
@endsection