<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - Polargate')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe', 300: '#93c5fd',
                            400: '#60a5fa', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8',
                            800: '#1e40af', 900: '#1e3a8a',
                        },
                    }
                }
            }
        }
    </script>
    
    <style type="text/tailwindcss">
        @layer components {
            .btn-primary {
                @apply bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-semibold py-2.5 px-5 rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5;
            }
            .btn-secondary {
                @apply bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2.5 px-5 rounded-lg transition duration-200 shadow-sm;
            }
            .btn-success {
                @apply bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 px-5 rounded-lg transition duration-200 shadow-sm;
            }
            .btn-danger {
                @apply bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 px-5 rounded-lg transition duration-200 shadow-sm;
            }
            .input-field {
                @apply w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition;
            }
            .card {
                @apply bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300;
            }
            .badge-success {
                @apply inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800;
            }
            .badge-warning {
                @apply inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800;
            }
            .badge-danger {
                @apply inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800;
            }
            .badge-info {
                @apply inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800;
            }
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        }
        
        .sidebar-link {
            position: relative;
            transition: all 0.3s ease;
        }
        
        .sidebar-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 0;
            background: white;
            border-radius: 0 4px 4px 0;
            transition: height 0.3s ease;
        }
        
        .sidebar-link:hover::before,
        .sidebar-link.active::before {
            height: 70%;
        }
        
        .sidebar-link:hover {
            transform: translateX(4px);
        }
        
        /* Header Animation */
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .header-animate {
            animation: slideDown 0.5s ease-out;
        }
        
        /* Flash Message Animation */
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .flash-message {
            animation: slideInRight 0.5s ease-out;
        }
    </style>
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
</head>
<body class="bg-gray-50" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside 
            class="fixed inset-y-0 left-0 z-50 w-64 gradient-bg transform transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 shadow-2xl"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center justify-center h-20 bg-black bg-opacity-10 border-b border-white border-opacity-10">
                    <div class="text-center">
                        @if(file_exists(public_path('storage/logo/polargate_logo_white-01.png')))
                            <img src="{{ asset('storage/logo/polargate_logo_white-01.png') }}" 
                                 alt="PT Polargate Indonesia Kreasi" 
                                 class="h-10 mx-auto hover:scale-110 transition-transform duration-300">
                        @else
                            <img src="{{ asset('logo/polargate_logo_white-01.png') }}" 
                                 alt="PT Polargate Indonesia Kreasi" 
                                 class="h-10 mx-auto hover:scale-110 transition-transform duration-300">
                        @endif
                        <p class="text-xs text-blue-100 mt-1">Admin Panel</p>
                    </div>
                </div>
                
                <!-- Navigation -->
                <nav class="flex-1 overflow-y-auto py-6 px-3">
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center px-4 py-3 text-white hover:bg-white hover:bg-opacity-10 rounded-lg mb-1 {{ request()->routeIs('admin.dashboard') ? 'active bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-home w-5 text-center mr-3"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>
                    
                    <a href="{{ route('admin.layanan.index') }}" class="sidebar-link flex items-center px-4 py-3 text-white hover:bg-white hover:bg-opacity-10 rounded-lg mb-1 {{ request()->routeIs('admin.layanan.*') ? 'active bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-th-large w-5 text-center mr-3"></i>
                        <span class="font-medium">Layanan</span>
                    </a>
                    
                    <a href="{{ route('admin.paket.index') }}" class="sidebar-link flex items-center px-4 py-3 text-white hover:bg-white hover:bg-opacity-10 rounded-lg mb-1 {{ request()->routeIs('admin.paket.*') ? 'active bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-box w-5 text-center mr-3"></i>
                        <span class="font-medium">Paket</span>
                    </a>
                    
                    <a href="{{ route('admin.addon.index') }}" class="sidebar-link flex items-center px-4 py-3 text-white hover:bg-white hover:bg-opacity-10 rounded-lg mb-1 {{ request()->routeIs('admin.addon.*') ? 'active bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-plus-circle w-5 text-center mr-3"></i>
                        <span class="font-medium">Add-on</span>
                    </a>
                    
                    <a href="{{ route('admin.pesanan.index') }}" class="sidebar-link flex items-center px-4 py-3 text-white hover:bg-white hover:bg-opacity-10 rounded-lg mb-1 {{ request()->routeIs('admin.pesanan.*') ? 'active bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-clipboard-list w-5 text-center mr-3"></i>
                        <span class="font-medium">Pesanan</span>
                    </a>
                    
                    <a href="{{ route('admin.client.index') }}" class="sidebar-link flex items-center px-4 py-3 text-white hover:bg-white hover:bg-opacity-10 rounded-lg mb-1 {{ request()->routeIs('admin.client.*') ? 'active bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-users w-5 text-center mr-3"></i>
                        <span class="font-medium">Client</span>
                    </a>
                    
                    <a href="{{ route('admin.invoice.index') }}" class="sidebar-link flex items-center px-4 py-3 text-white hover:bg-white hover:bg-opacity-10 rounded-lg mb-1 {{ request()->routeIs('admin.invoice.*') ? 'active bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-file-invoice w-5 text-center mr-3"></i>
                        <span class="font-medium">Invoice</span>
                    </a>
                    
                    <a href="{{ route('admin.pembayaran.pending') }}" class="sidebar-link flex items-center px-4 py-3 text-white hover:bg-white hover:bg-opacity-10 rounded-lg mb-1 {{ request()->routeIs('admin.pembayaran.*') ? 'active bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-credit-card w-5 text-center mr-3"></i>
                        <span class="font-medium">Verifikasi Pembayaran</span>
                    </a>
                    
                    <!-- Dropdown Laporan -->
                    <div x-data="{ open: {{ request()->routeIs('admin.laporan.*') ? 'true' : 'false' }} }" class="mb-1">
                        <button @click="open = !open" class="sidebar-link flex items-center justify-between w-full px-4 py-3 text-white hover:bg-white hover:bg-opacity-10 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-chart-bar w-5 text-center mr-3"></i>
                                <span class="font-medium">Laporan</span>
                            </div>
                            <i class="fas fa-chevron-down text-xs transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             class="ml-4 mt-1 space-y-1">
                            <a href="{{ route('admin.laporan.keuangan') }}" class="block px-4 py-2 text-sm text-blue-100 hover:text-white hover:bg-white hover:bg-opacity-10 rounded-lg transition-all duration-300 hover:translate-x-2">Keuangan</a>
                            <a href="{{ route('admin.laporan.pemesanan') }}" class="block px-4 py-2 text-sm text-blue-100 hover:text-white hover:bg-white hover:bg-opacity-10 rounded-lg transition-all duration-300 hover:translate-x-2">Pemesanan</a>
                            <a href="{{ route('admin.laporan.client') }}" class="block px-4 py-2 text-sm text-blue-100 hover:text-white hover:bg-white hover:bg-opacity-10 rounded-lg transition-all duration-300 hover:translate-x-2">Client</a>
                        </div>
                    </div>
                </nav>
                
                <!-- Settings -->
                <div class="p-4 border-t border-white border-opacity-10">
                    <a href="{{ route('admin.profil') }}" class="sidebar-link flex items-center px-4 py-3 text-white hover:bg-white hover:bg-opacity-10 rounded-lg">
                        <i class="fas fa-cog w-5 text-center mr-3"></i>
                        <span class="font-medium">Pengaturan</span>
                    </a>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-md border-b border-gray-200 header-animate">
                <div class="flex items-center justify-between px-6 py-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-600 hover:text-primary-600 lg:hidden transition-colors duration-300 hover:scale-110 transform">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    
                    <div class="flex-1"></div>
                    
                    <!-- User Menu -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center space-x-3 hover:bg-gray-50 rounded-lg px-3 py-2 transition-all duration-300 hover:shadow-md">
                            <img src="{{ Auth::user()->foto_url }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover border-2 border-primary-500 hover:border-primary-600 transition-colors duration-300">
                            <div class="text-left hidden md:block">
                                <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500">Super Admin</p>
                            </div>
                            <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        
                        <div x-show="open" 
                             @click.away="open = false" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-2xl py-2 z-50 border border-gray-100">
                            <a href="{{ route('admin.profil') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-all duration-300 hover:translate-x-2">
                                <i class="fas fa-user-circle w-5 mr-3 text-primary-600"></i>
                                <span>Profil Saya</span>
                            </a>
                            <hr class="my-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-all duration-300 hover:translate-x-2">
                                    <i class="fas fa-sign-out-alt w-5 mr-3"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
                <!-- Flash Messages -->
                @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-r-lg shadow-lg flash-message">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-2xl mr-3 animate-bounce"></i>
                        <span class="font-medium">{{ session('success') }}</span>
                        <button @click="show = false" class="ml-auto hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-times text-green-500 hover:text-green-700"></i>
                        </button>
                    </div>
                </div>
                @endif
                
                @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-r-lg shadow-lg flash-message">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-2xl mr-3 animate-bounce"></i>
                        <span class="font-medium">{{ session('error') }}</span>
                        <button @click="show = false" class="ml-auto hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-times text-red-500 hover:text-red-700"></i>
                        </button>
                    </div>
                </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Overlay untuk mobile -->
    <div 
        x-show="sidebarOpen" 
        @click="sidebarOpen = false"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden"
    ></div>
    
    @stack('scripts')
</body>
</html>