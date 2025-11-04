@extends('layouts.admin')

@section('title', 'Kelola Client')

@section('content')
<style>
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .page-load .animate-fade {
        animation: fadeIn 0.4s ease-out;
    }

    .page-load .animate-slide {
        animation: slideUp 0.5s ease-out;
        opacity: 0;
        animation-fill-mode: forwards;
    }

    .page-load .delay-100 { animation-delay: 0.1s; }
    .page-load .delay-200 { animation-delay: 0.2s; }
    .page-load .delay-300 { animation-delay: 0.3s; }

    .card {
        transition: box-shadow 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .stats-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stats-card:hover {
        transform: scale(1.03);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
    }

    table tbody tr {
        transition: background-color 0.2s ease;
    }

    table tbody tr:hover {
        background-color: #f9fafb;
    }

    .search-input {
        transition: all 0.3s ease;
    }

    .search-input:focus {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }

    .client-avatar {
        transition: transform 0.3s ease;
    }

    .client-avatar:hover {
        transform: scale(1.1);
    }
</style>

<div class="mb-6 animate-fade">
    <h1 class="text-3xl font-bold text-gray-800">Kelola Client</h1>
    <p class="text-gray-600">Manajemen data client</p>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="card bg-gradient-to-br from-blue-500 to-blue-600 text-white stats-card animate-slide cursor-pointer">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium mb-1">Total Client</p>
                <p class="text-3xl font-bold">{{ $clients->total() }}</p>
            </div>
            <div class="bg-white/20 p-4 rounded-xl backdrop-blur-sm">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="card bg-gradient-to-br from-green-500 to-green-600 text-white stats-card animate-slide delay-100 cursor-pointer">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium mb-1">Client Aktif</p>
                <p class="text-3xl font-bold">{{ $clients->where('is_active', true)->count() }}</p>
            </div>
            <div class="bg-white/20 p-4 rounded-xl backdrop-blur-sm">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="card bg-gradient-to-br from-purple-500 to-purple-600 text-white stats-card animate-slide delay-200 cursor-pointer">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium mb-1">Total Pesanan</p>
                <p class="text-3xl font-bold">{{ $clients->sum('pesanan_count') }}</p>
            </div>
            <div class="bg-white/20 p-4 rounded-xl backdrop-blur-sm">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Filter & Search -->
<div class="card mb-6 animate-slide delay-100">
    <div class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Real-time Search -->
            <div class="lg:col-span-2">
                <label for="realTimeSearch" class="block text-sm font-medium text-gray-700 mb-2">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Cari Client Real-time
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" 
                           id="realTimeSearch" 
                           class="search-input w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition" 
                           placeholder="Ketik nama, email, atau nomor telepon...">
                </div>
            </div>

            <!-- Filter Status -->
            <div>
                <label for="statusFilter" class="block text-sm font-medium text-gray-700 mb-2">Filter Status</label>
                <select id="statusFilter" class="input-field w-full">
                    <option value="">Semua Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-2">
            <button id="resetFilter" class="btn-secondary flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Reset Filter
            </button>
        </div>

        <!-- Search Results Info -->
        <div id="searchInfo" class="text-sm text-gray-600 hidden">
            Menampilkan <span id="resultCount" class="font-semibold text-primary-600"></span> hasil dari <span id="totalCount" class="font-semibold">{{ $clients->count() }}</span> client
        </div>
    </div>
</div>

<!-- Table -->
<div class="card animate-slide delay-200">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pesanan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bergabung</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($clients as $client)
                <tr class="client-row"
                    data-nama="{{ strtolower($client->name) }}"
                    data-email="{{ strtolower($client->email) }}"
                    data-telepon="{{ $client->no_telepon }}"
                    data-status="{{ $client->is_active ? 'aktif' : 'nonaktif' }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <img src="{{ $client->foto_url }}" alt="{{ $client->name }}" class="w-10 h-10 rounded-full object-cover mr-3 client-avatar border-2 border-gray-200">
                            <div>
                                <div class="font-medium text-gray-900">{{ $client->name }}</div>
                                <div class="text-sm text-gray-500">{{ $client->jenis_kelamin }} • {{ $client->usia }} tahun</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $client->email }}</div>
                        <div class="text-sm text-gray-500">{{ $client->no_telepon }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <span class="inline-flex items-center">
                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            {{ $client->pesanan_count }} pesanan
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $client->created_at->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($client->is_active)
                        <span class="badge-success inline-flex items-center gap-1">
                            <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                            Aktif
                        </span>
                        @else
                        <span class="badge-danger inline-flex items-center gap-1">
                            <span class="w-2 h-2 bg-red-400 rounded-full"></span>
                            Nonaktif
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <a href="{{ route('admin.client.show', $client) }}" class="text-blue-600 hover:text-blue-900 transition-colors">Detail</a>
                        <a href="{{ route('admin.client.edit', $client) }}" class="text-green-600 hover:text-green-900 transition-colors">Edit</a>
                        <form action="{{ route('admin.client.toggle-status', $client) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-yellow-600 hover:text-yellow-900 transition-colors">
                                {{ $client->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr id="emptyRow">
                    <td colspan="6" class="px-6 py-8 text-center">
                        <div class="flex flex-col items-center justify-center gap-4">
                            <div class="bg-gray-100 rounded-full p-6">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <p class="text-gray-500">Tidak ada client</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- No Results Message -->
    <div id="noResults" class="hidden px-6 py-12 text-center">
        <div class="flex flex-col items-center justify-center gap-4">
            <div class="bg-gray-100 rounded-full p-6">
                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-gray-500 font-medium">Tidak ada hasil ditemukan</p>
                <p class="text-gray-400 text-sm mt-1">Coba ubah kata kunci pencarian Anda</p>
            </div>
        </div>
    </div>
    
    <!-- Pagination -->
    @if($clients->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $clients->links() }}
    </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // First load animation
        const isFirstLoad = !sessionStorage.getItem('client_loaded');
        
        if (isFirstLoad) {
            document.body.classList.add('page-load');
            sessionStorage.setItem('client_loaded', 'true');
            
            setTimeout(() => {
                document.body.classList.remove('page-load');
            }, 1000);
        }

        // Real-time Search and Filter
        const realTimeSearch = document.getElementById('realTimeSearch');
        const statusFilter = document.getElementById('statusFilter');
        const resetButton = document.getElementById('resetFilter');
        const clientRows = document.querySelectorAll('.client-row');
        const searchInfo = document.getElementById('searchInfo');
        const resultCount = document.getElementById('resultCount');
        const noResults = document.getElementById('noResults');
        const emptyRow = document.getElementById('emptyRow');

        function filterClient() {
            const searchTerm = realTimeSearch.value.toLowerCase();
            const statusValue = statusFilter.value;
            let visibleCount = 0;

            clientRows.forEach(row => {
                const nama = row.dataset.nama;
                const email = row.dataset.email;
                const telepon = row.dataset.telepon;
                const status = row.dataset.status;

                const matchSearch = nama.includes(searchTerm) || 
                                  email.includes(searchTerm) || 
                                  telepon.includes(searchTerm);
                const matchStatus = statusValue === '' || status === statusValue;

                if (matchSearch && matchStatus) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Update search info
            if (searchTerm || statusValue) {
                searchInfo.classList.remove('hidden');
                resultCount.textContent = visibleCount;
            } else {
                searchInfo.classList.add('hidden');
            }

            // Show/hide no results message
            if (visibleCount === 0 && clientRows.length > 0) {
                noResults.classList.remove('hidden');
                if (emptyRow) emptyRow.style.display = 'none';
            } else {
                noResults.classList.add('hidden');
            }
        }

        // Event listeners
        realTimeSearch.addEventListener('input', filterClient);
        statusFilter.addEventListener('change', filterClient);
        
        resetButton.addEventListener('click', () => {
            realTimeSearch.value = '';
            statusFilter.value = '';
            filterClient();
        });
    });

    window.addEventListener('beforeunload', function(e) {
        if (e.target.activeElement.tagName === 'A' && 
            e.target.activeElement.getAttribute('href') !== '#') {
            sessionStorage.removeItem('client_loaded');
        }
    });
</script>
@endsection