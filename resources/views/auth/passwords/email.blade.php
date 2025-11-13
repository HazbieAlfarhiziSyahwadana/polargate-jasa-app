@extends('layouts.app')

@section('title', 'Lupa Password - Polargate')

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
                    <i class="fas fa-key text-white text-3xl"></i>
                </div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-primary-600 to-primary-800 bg-clip-text text-transparent mb-2 animate-gradient">
                    Lupa Password?
                </h1>
                <p class="text-gray-600 text-sm sm:text-base">Masukkan email Anda untuk menerima link reset password</p>
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

            <!-- Request Reset Form -->
            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
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
                            autofocus
                        >
                        <div class="input-border"></div>
                    </div>
                    @error('email')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-semibold py-3.5 px-6 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-[1.02] active:scale-[0.98] animate-slideInLeft" style="animation-delay: 0.2s;">
                    <i class="fas fa-paper-plane mr-2"></i>Kirim Link Reset
                </button>
            </form>

            <!-- Back to Login -->
            <div class="mt-6 text-center animate-fadeIn" style="animation-delay: 0.3s;">
                <a href="{{ route('login') }}" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-semibold group transition-all duration-300">
                    <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform duration-300"></i>
                    Kembali ke Login
                </a>
            </div>

            <!-- Info Box -->
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-xl animate-fadeIn" style="animation-delay: 0.4s;">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-3 flex-shrink-0"></i>
                    <div class="text-xs text-blue-800">
                        <p class="font-semibold mb-1">Informasi:</p>
                        <p>Link reset password akan dikirim ke email Anda. Periksa folder spam jika tidak menemukannya di inbox.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Text -->
        <div class="text-center mt-6 animate-fadeIn" style="animation-delay: 0.5s;">
            <p class="text-white text-opacity-80 text-sm">
                © {{ date('Y') }} PT Polargate Indonesia Kreasi
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Add focus effect to inputs
document.querySelectorAll('input[type="email"]').forEach(input => {
    input.addEventListener('focus', function() {
        this.parentElement.classList.add('input-focused');
    });
    
    input.addEventListener('blur', function() {
        this.parentElement.classList.remove('input-focused');
    });
});
</script>
@endpush

@push('styles')
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