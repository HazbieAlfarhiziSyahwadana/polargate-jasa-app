@extends('layouts.app')

@section('title', 'Register - Polargate')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden" style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #60a5fa 100%);">
    <!-- Floating Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute w-96 h-96 bg-white opacity-5 rounded-full -top-20 -left-20 animate-pulse"></div>
        <div class="absolute w-80 h-80 bg-white opacity-5 rounded-full top-40 -right-20 animate-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute w-64 h-64 bg-white opacity-5 rounded-full -bottom-10 left-1/3 animate-pulse" style="animation-delay: 4s;"></div>
    </div>

    <div class="max-w-3xl w-full relative z-10">
        <div class="bg-white rounded-3xl shadow-2xl p-6 sm:p-8 lg:p-10 backdrop-blur-lg animate-fadeInUp hover:shadow-3xl transition-shadow duration-500">
            <!-- Logo & Title -->
            <div class="text-center mb-6 animate-fadeIn">
                <div class="inline-block p-3 bg-gradient-to-br from-primary-500 to-primary-700 rounded-2xl mb-3 shadow-lg transform hover:scale-110 hover:rotate-3 transition-all duration-300">
                    <i class="fas fa-user-plus text-white text-2xl sm:text-3xl"></i>
                </div>
                <h1 class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-primary-600 to-primary-800 bg-clip-text text-transparent mb-2 animate-gradient">
                    Daftar Akun Baru
                </h1>
                <p class="text-gray-600 text-sm sm:text-base">Lengkapi data diri Anda untuk membuat akun</p>
            </div>

            <!-- Error Messages -->
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

            <!-- Success Message -->
            @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-r-lg flex items-start animate-slideInLeft">
                <i class="fas fa-check-circle mr-3 mt-0.5 flex-shrink-0"></i>
                <span class="text-sm">{{ session('success') }}</span>
            </div>
            @endif

            <!-- Register Form -->
            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <!-- Personal Info Section -->
                <div class="border-b border-gray-200 pb-5 animate-slideInLeft" style="animation-delay: 0.1s;">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-user text-primary-600 mr-2 text-sm"></i>
                        Informasi Pribadi
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nama Lengkap -->
                        <div class="form-group">
                            <label for="name" class="block text-gray-700 text-sm font-semibold mb-2">
                                <i class="fas fa-id-card text-primary-600 mr-1 text-xs"></i>
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <div class="input-wrapper">
                                <input 
                                    type="text" 
                                    name="name" 
                                    id="name" 
                                    value="{{ old('name') }}"
                                    class="w-full px-4 py-2.5 text-sm border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-300 hover:border-primary-300 @error('name') border-red-500 @enderror" 
                                    placeholder="Contoh: John Doe"
                                    required
                                >
                                <div class="input-border"></div>
                            </div>
                            @error('name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Foto Profil -->
                        <div class="form-group">
                            <label for="foto" class="block text-gray-700 text-sm font-semibold mb-2">
                                <i class="fas fa-camera text-primary-600 mr-1 text-xs"></i>
                                Foto Profil <span class="text-gray-400 text-xs">(Opsional)</span>
                            </label>
                            <div class="input-wrapper">
                                <input 
                                    type="file" 
                                    name="foto" 
                                    id="foto" 
                                    accept="image/jpeg,image/jpg,image/png"
                                    class="w-full px-4 py-2 text-sm border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-300 hover:border-primary-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 @error('foto') border-red-500 @enderror"
                                >
                                <div class="input-border"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>
                                Max 2MB (jpg, jpeg, png)
                            </p>
                            @error('foto')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="mt-4 form-group">
                        <label for="alamat" class="block text-gray-700 text-sm font-semibold mb-2">
                            <i class="fas fa-map-marker-alt text-primary-600 mr-1 text-xs"></i>
                            Alamat Lengkap <span class="text-red-500">*</span>
                        </label>
                        <div class="input-wrapper">
                            <textarea 
                                name="alamat" 
                                id="alamat" 
                                rows="3"
                                class="w-full px-4 py-2.5 text-sm border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-300 hover:border-primary-300 resize-none @error('alamat') border-red-500 @enderror" 
                                placeholder="Contoh: Jl. Merdeka No. 123, RT 01/RW 02, Kelurahan ABC, Kecamatan XYZ, Jakarta Selatan"
                                required
                            >{{ old('alamat') }}</textarea>
                            <div class="input-border"></div>
                        </div>
                        @error('alamat')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <!-- Jenis Kelamin -->
                        <div class="form-group">
                            <label for="jenis_kelamin" class="block text-gray-700 text-sm font-semibold mb-2">
                                <i class="fas fa-venus-mars text-primary-600 mr-1 text-xs"></i>
                                Jenis Kelamin <span class="text-red-500">*</span>
                            </label>
                            <div class="input-wrapper">
                                <select 
                                    name="jenis_kelamin" 
                                    id="jenis_kelamin" 
                                    class="w-full px-4 py-2.5 text-sm border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-300 hover:border-primary-300 @error('jenis_kelamin') border-red-500 @enderror"
                                    required
                                >
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                <div class="input-border"></div>
                            </div>
                            @error('jenis_kelamin')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Lahir -->
                        <div class="form-group">
                            <label for="tanggal_lahir" class="block text-gray-700 text-sm font-semibold mb-2">
                                <i class="fas fa-calendar-alt text-primary-600 mr-1 text-xs"></i>
                                Tanggal Lahir <span class="text-red-500">*</span>
                            </label>
                            <div class="input-wrapper">
                                <input 
                                    type="date" 
                                    name="tanggal_lahir" 
                                    id="tanggal_lahir" 
                                    value="{{ old('tanggal_lahir') }}"
                                    class="w-full px-4 py-2.5 text-sm border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-300 hover:border-primary-300 @error('tanggal_lahir') border-red-500 @enderror"
                                    max="{{ date('Y-m-d') }}"
                                    required
                                >
                                <div class="input-border"></div>
                            </div>
                            @error('tanggal_lahir')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Contact Info Section -->
                <div class="border-b border-gray-200 pb-5 animate-slideInLeft" style="animation-delay: 0.2s;">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-address-book text-primary-600 mr-2 text-sm"></i>
                        Informasi Kontak
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nomor Telepon -->
                        <div class="form-group">
                            <label for="no_telepon" class="block text-gray-700 text-sm font-semibold mb-2">
                                <i class="fas fa-phone text-primary-600 mr-1 text-xs"></i>
                                Nomor Telepon <span class="text-red-500">*</span>
                            </label>
                            <div class="input-wrapper">
                                <input 
                                    type="tel" 
                                    name="no_telepon" 
                                    id="no_telepon" 
                                    value="{{ old('no_telepon') }}"
                                    class="w-full px-4 py-2.5 text-sm border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-300 hover:border-primary-300 @error('no_telepon') border-red-500 @enderror" 
                                    placeholder="Contoh: 081234567890"
                                    pattern="[0-9]{10,13}"
                                    required
                                >
                                <div class="input-border"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>
                                Format: 10-13 digit angka
                            </p>
                            @error('no_telepon')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">
                                <i class="fas fa-envelope text-primary-600 mr-1 text-xs"></i>
                                Email <span class="text-red-500">*</span>
                            </label>
                            <div class="input-wrapper">
                                <input 
                                    type="email" 
                                    name="email" 
                                    id="email" 
                                    value="{{ old('email') }}"
                                    class="w-full px-4 py-2.5 text-sm border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-300 hover:border-primary-300 @error('email') border-red-500 @enderror" 
                                    placeholder="Contoh: nama@email.com"
                                    required
                                >
                                <div class="input-border"></div>
                            </div>
                            @error('email')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Security Section -->
                <div class="pb-2 animate-slideInLeft" style="animation-delay: 0.3s;">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-shield-alt text-primary-600 mr-2 text-sm"></i>
                        Keamanan Akun
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Password -->
                        <div class="form-group">
                            <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">
                                <i class="fas fa-lock text-primary-600 mr-1 text-xs"></i>
                                Password <span class="text-red-500">*</span>
                            </label>
                            <div class="input-wrapper relative">
                                <input 
                                    type="password" 
                                    name="password" 
                                    id="password" 
                                    class="w-full px-4 py-2.5 pr-12 text-sm border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-300 hover:border-primary-300 @error('password') border-red-500 @enderror" 
                                    placeholder="Minimal 8 karakter"
                                    minlength="8"
                                    required
                                >
                                <button type="button" onclick="togglePassword('password', 'toggleIcon1')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary-600 transition-all duration-300 hover:scale-110 focus:outline-none">
                                    <i class="fas fa-eye" id="toggleIcon1"></i>
                                </button>
                                <div class="input-border"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>
                                Minimal 8 karakter
                            </p>
                            @error('password')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="form-group">
                            <label for="password_confirmation" class="block text-gray-700 text-sm font-semibold mb-2">
                                <i class="fas fa-lock text-primary-600 mr-1 text-xs"></i>
                                Konfirmasi Password <span class="text-red-500">*</span>
                            </label>
                            <div class="input-wrapper relative">
                                <input 
                                    type="password" 
                                    name="password_confirmation" 
                                    id="password_confirmation" 
                                    class="w-full px-4 py-2.5 pr-12 text-sm border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-300 hover:border-primary-300" 
                                    placeholder="Ulangi password"
                                    minlength="8"
                                    required
                                >
                                <button type="button" onclick="togglePassword('password_confirmation', 'toggleIcon2')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary-600 transition-all duration-300 hover:scale-110 focus:outline-none">
                                    <i class="fas fa-eye" id="toggleIcon2"></i>
                                </button>
                                <div class="input-border"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Password Strength Indicator -->
                    <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-xs text-blue-800 font-semibold mb-1">
                            <i class="fas fa-shield-alt mr-1"></i>
                            Tips Password Aman:
                        </p>
                        <ul class="text-xs text-blue-700 space-y-0.5 list-disc list-inside">
                            <li>Gunakan minimal 8 karakter</li>
                            <li>Kombinasikan huruf besar, kecil, angka, dan simbol</li>
                            <li>Hindari informasi pribadi yang mudah ditebak</li>
                        </ul>
                    </div>
                </div>
                <!-- Terms & Conditions - Version 2 (More Attractive) -->
<div class="animate-slideInLeft" style="animation-delay: 0.35s;">
    <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border-2 border-blue-200">
        <div class="flex items-start space-x-3">
            <div class="flex items-center h-5">
                <input 
                    type="checkbox" 
                    name="terms" 
                    id="terms" 
                    class="w-5 h-5 rounded border-2 border-blue-300 text-primary-600 focus:ring-2 focus:ring-primary-500 cursor-pointer transition-all duration-300 hover:border-primary-500 @error('terms') border-red-500 @enderror"
                    {{ old('terms') ? 'checked' : '' }}
                    required
                >
            </div>
            <div class="flex-1">
                <label for="terms" class="text-sm text-gray-800 cursor-pointer select-none leading-relaxed">
                    <span class="font-semibold text-gray-900">
                        <i class="fas fa-shield-check text-primary-600 mr-1"></i>
                        Persetujuan Pengguna
                    </span>
                    <br>
                    Dengan mendaftar, saya menyetujui 
                    <a href="#" onclick="event.preventDefault(); showTerms();" class="text-primary-600 hover:text-primary-700 font-semibold hover:underline">Syarat & Ketentuan</a> 
                    dan 
                    <a href="#" onclick="event.preventDefault(); showPrivacy();" class="text-primary-600 hover:text-primary-700 font-semibold hover:underline">Kebijakan Privasi</a> 
                    Polargate. <span class="text-red-500 font-bold">*</span>
                </label>
            </div>
        </div>
        @error('terms')
        <div class="mt-2 pl-8 text-xs text-red-600 flex items-center">
            <i class="fas fa-exclamation-triangle mr-1"></i>
            {{ $message }}
        </div>
        @enderror
    </div>
</div>

<!-- JavaScript untuk Modal (Optional) -->
<script>
function showTerms() {
    alert('Halaman Syarat & Ketentuan akan ditampilkan di sini.\n\nAnda bisa membuat modal atau redirect ke halaman terms.');
}

function showPrivacy() {
    alert('Halaman Kebijakan Privasi akan ditampilkan di sini.\n\nAnda bisa membuat modal atau redirect ke halaman privacy policy.');
}
</script>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-semibold py-3.5 px-6 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-[1.02] active:scale-[0.98] animate-slideInLeft" style="animation-delay: 0.4s;">
                    <i class="fas fa-user-plus mr-2"></i>Daftar Sekarang
                </button>
            </form>

            <!-- Login Link -->
            <div class="mt-6 text-center animate-fadeIn" style="animation-delay: 0.5s;">
                <p class="text-gray-600 mb-4 text-sm">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700 font-semibold hover:underline inline-flex items-center group transition-all duration-300">
                        Login Sekarang 
                        <i class="fas fa-arrow-right ml-1 text-xs group-hover:translate-x-1 transition-transform duration-300"></i>
                    </a>
                </p>
                
                <!-- Back to Landing Page Button -->
                <a href="{{ route('landing') }}" class="inline-flex items-center px-6 py-2.5 text-sm bg-gradient-to-r from-gray-100 to-gray-200 hover:from-gray-200 hover:to-gray-300 text-gray-700 rounded-full transition-all duration-300 shadow-sm hover:shadow-md border border-gray-300 hover:scale-105 transform">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <span class="font-medium">Kembali ke Beranda</span>
                </a>
            </div>
        </div>

        <!-- Footer Text -->
        <div class="text-center mt-6 animate-fadeIn" style="animation-delay: 0.6s;">
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
document.querySelectorAll('input, select, textarea').forEach(input => {
    input.addEventListener('focus', function() {
        this.closest('.input-wrapper')?.classList.add('input-focused');
    });
    
    input.addEventListener('blur', function() {
        this.closest('.input-wrapper')?.classList.remove('input-focused');
    });
});

// Preview foto sebelum upload
document.getElementById('foto')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        if (file.size > 2097152) { // 2MB
            alert('Ukuran file terlalu besar! Maksimal 2MB');
            this.value = '';
        }
    }
});

// Password match validation
document.getElementById('password_confirmation')?.addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    
    if (confirmPassword && password !== confirmPassword) {
        this.setCustomValidity('Password tidak cocok!');
    } else {
        this.setCustomValidity('');
    }
});
</script>
@endpush

@push('styles')
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

button, a, input, select, textarea, label {
    transition: all 0.3s ease;
}
</style>
@endpush
@endsection