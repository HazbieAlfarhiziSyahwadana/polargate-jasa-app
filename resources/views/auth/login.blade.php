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
        <div class="bg-white rounded-3xl shadow-2xl p-8 sm:p-10 backdrop-blur-lg animate-fadeInUp hover:shadow-3xl transition-shadow duration-500">
            <!-- Logo & Title -->
            <div class="text-center mb-8 animate-fadeIn">
                <div class="inline-block p-3 bg-gradient-to-br from-primary-500 to-primary-700 rounded-2xl mb-4 shadow-lg transform hover:scale-110 hover:rotate-3 transition-all duration-300">
                    <i class="fas fa-user-shield text-white text-3xl"></i>
                </div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-primary-600 to-primary-800 bg-clip-text text-transparent mb-2 animate-gradient">
                    Selamat Datang
                </h1>
                <p class="text-gray-600">Silakan login untuk melanjutkan</p>
            </div>

            <!-- Flash Messages -->
            @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-r-lg flex items-center animate-slideInLeft">
                <i class="fas fa-check-circle mr-3"></i>
                <span class="text-sm">{{ session('success') }}</span>
            </div>
            @endif

            @if(session('status'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-r-lg flex items-center animate-slideInLeft">
                <i class="fas fa-check-circle mr-3"></i>
                <span class="text-sm">{{ session('status') }}</span>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-r-lg animate-shake">
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

                <div class="form-group animate-slideInLeft" style="animation-delay: 0.1s;">
                    <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-envelope mr-2 text-primary-600"></i>Email
                    </label>
                    <div class="input-wrapper">
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            value="{{ old('email') }}"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-300 hover:border-primary-300 @error('email') border-red-500 @enderror" 
                            placeholder="nama@email.com"
                            required
                        >
                        <div class="input-border"></div>
                    </div>
                </div>

                <div class="form-group animate-slideInLeft" style="animation-delay: 0.2s;">
                    <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-lock mr-2 text-primary-600"></i>Password
                    </label>
                    <div class="input-wrapper relative">
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            class="w-full px-4 py-3 pr-12 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-300 hover:border-primary-300 @error('password') border-red-500 @enderror" 
                            placeholder="Masukkan password"
                            required
                        >
                        <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary-600 transition-all duration-300 hover:scale-110">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                        <div class="input-border"></div>
                    </div>
                </div>

                <!-- Forgot Password Link (moved here) -->
                <div class="text-right animate-slideInLeft" style="animation-delay: 0.3s;">
                    <a href="{{ route('password.request') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium hover:underline transition-colors duration-300">
                        Lupa Password?
                    </a>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-semibold py-3.5 px-6 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-[1.02] active:scale-[0.98] animate-slideInLeft" style="animation-delay: 0.4s;">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login Sekarang
                </button>
            </form>

            <!-- Register Link -->
            <div class="mt-8 text-center animate-fadeIn" style="animation-delay: 0.5s;">
                <p class="text-gray-600 mb-4">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="text-primary-600 hover:text-primary-700 font-semibold hover:underline inline-flex items-center group transition-all duration-300">
                        Daftar Sekarang 
                        <i class="fas fa-arrow-right ml-1 text-xs group-hover:translate-x-1 transition-transform duration-300"></i>
                    </a>
                </p>
                
                <!-- Back to Landing Page Button -->
                <a href="{{ route('landing') }}" class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-gray-100 to-gray-200 hover:from-gray-200 hover:to-gray-300 text-gray-700 rounded-full transition-all duration-300 shadow-sm hover:shadow-md border border-gray-300 hover:scale-105 transform">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <span class="font-medium">Kembali ke Beranda</span>
                </a>
            </div>
        </div>

        <!-- Footer Text -->
        <div class="text-center mt-6 animate-fadeIn" style="animation-delay: 0.8s;">
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

// Add focus effect to inputs
document.querySelectorAll('input[type="email"], input[type="password"]').forEach(input => {
    input.addEventListener('focus', function() {
        this.parentElement.classList.add('input-focused');
    });
    
    input.addEventListener('blur', function() {
        this.parentElement.classList.remove('input-focused');
    });
});
</script>

<style>
/* Background Pulse Animation */
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

/* Fade In Up Animation */
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

/* Fade In Animation */
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

/* Slide Down Animation */
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

.animate-slideDown {
    animation: slideDown 0.6s ease-out;
}

/* Slide In Left Animation */
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

/* Shake Animation for Errors */
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}

.animate-shake {
    animation: shake 0.5s ease-in-out;
}

/* Gradient Animation */
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

/* Slow Bounce Animation */
@keyframes bounce-slow {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-5px);
    }
}

.animate-bounce-slow {
    animation: bounce-slow 2s ease-in-out infinite;
}

/* Input Focus Effect */
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

/* Form Group Animation */
.form-group {
    transition: transform 0.3s ease;
}

.form-group:hover {
    transform: translateX(3px);
}

/* Enhanced Shadow */
.shadow-3xl {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* Responsive Adjustments */
@media (max-width: 640px) {
    .animate-fadeInUp {
        animation: fadeInUp 0.4s ease-out;
    }
    
    .animate-slideInLeft {
        animation: slideInLeft 0.4s ease-out forwards;
    }
}

/* Smooth Transitions for All Interactive Elements */
* {
    -webkit-tap-highlight-color: transparent;
}

button, a, input, label {
    transition: all 0.3s ease;
}
</style>
@endsection