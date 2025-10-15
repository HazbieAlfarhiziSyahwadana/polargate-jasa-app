<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Client - Polargate')</title>
    
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
            .btn-outline {
                @apply border-2 border-primary-600 text-primary-600 hover:bg-primary-600 hover:text-white font-semibold py-2 px-5 rounded-lg transition duration-200;
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
                                 class="h-10 mx-auto">
                        @else
                            <img src="{{ asset('logo/polargate_logo_white-01.png') }}" 
                                 alt="PT Polargate Indonesia Kreasi" 
                                 class="h-10 mx-auto">
                        @endif
                        <p class="text-xs text-blue-100 mt-1">Client Portal</p>
                    </div>
                </div>
                
                <!-- Navigation -->
                <nav class="flex-1 overflow-y-auto py-6 px-3">
                    <a href="{{ route('client.dashboard') }}" class="sidebar-link flex items-center px-4 py-3 text-white hover:bg-white hover:bg-opacity-10 rounded-lg mb-1 {{ request()->routeIs('client.dashboard') ? 'active bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-home w-5 text-center mr-3"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>
                    
                    <a href="{{ route('client.layanan.index') }}" class="sidebar-link flex items-center px-4 py-3 text-white hover:bg-white hover:bg-opacity-10 rounded-lg mb-1 {{ request()->routeIs('client.layanan.*') ? 'active bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-th-large w-5 text-center mr-3"></i>
                        <span class="font-medium">Layanan</span>
                    </a>
                    
                    <a href="{{ route('client.pesanan.index') }}" class="sidebar-link flex items-center px-4 py-3 text-white hover:bg-white hover:bg-opacity-10 rounded-lg mb-1 {{ request()->routeIs('client.pesanan.*') ? 'active bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-shopping-cart w-5 text-center mr-3"></i>
                        <span class="font-medium">Pesanan Saya</span>
                    </a>
                    
                    <a href="{{ route('client.invoice.index') }}" class="sidebar-link flex items-center px-4 py-3 text-white hover:bg-white hover:bg-opacity-10 rounded-lg mb-1 {{ request()->routeIs('client.invoice.*') ? 'active bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-file-invoice w-5 text-center mr-3"></i>
                        <span class="font-medium">Invoice</span>
                    </a>
                    
                    <a href="{{ route('client.profil') }}" class="sidebar-link flex items-center px-4 py-3 text-white hover:bg-white hover:bg-opacity-10 rounded-lg mb-1 {{ request()->routeIs('client.profil') ? 'active bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-user-circle w-5 text-center mr-3"></i>
                        <span class="font-medium">Profil</span>
                    </a>
                </nav>
                
                <!-- Logout Button -->
                <div class="p-4 border-t border-white border-opacity-10">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="sidebar-link flex items-center w-full px-4 py-3 text-white hover:bg-red-500 hover:bg-opacity-20 rounded-lg transition">
                            <i class="fas fa-sign-out-alt w-5 text-center mr-3"></i>
                            <span class="font-medium">Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-md border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-600 hover:text-primary-600 lg:hidden transition">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    
                    <div class="flex-1 max-w-xl mx-4 hidden md:block">
                        <div class="relative">
                            <input type="search" placeholder="Cari layanan..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    
                    <!-- User Menu -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center space-x-3 hover:bg-gray-50 rounded-lg px-3 py-2 transition">
                            <img src="{{ Auth::user()->foto_url }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover border-2 border-primary-500">
                            <div class="text-left hidden md:block">
                                <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500">Client</p>
                            </div>
                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-2xl py-2 z-50 border border-gray-100">
                            <a href="{{ route('client.profil') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition">
                                <i class="fas fa-user-circle w-5 mr-3 text-primary-600"></i>
                                <span>Profil Saya</span>
                            </a>
                            <hr class="my-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition">
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
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-r-lg shadow-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-2xl mr-3"></i>
                        <span class="font-medium">{{ session('success') }}</span>
                        <button @click="show = false" class="ml-auto">
                            <i class="fas fa-times text-green-500 hover:text-green-700"></i>
                        </button>
                    </div>
                </div>
                @endif
                
                @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-r-lg shadow-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-2xl mr-3"></i>
                        <span class="font-medium">{{ session('error') }}</span>
                        <button @click="show = false" class="ml-auto">
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
