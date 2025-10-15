@extends('layouts.admin')

@section('title', 'Kelola Client')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Kelola Client</h1>
    <p class="text-gray-600">Manajemen data client</p>
</div>

<!-- Filter & Search -->
<div class="card mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Filter Status</label>
            <select name="status" id="status" class="input-field" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
        <div>
            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Client</label>
            <input type="text" name="search" id="search" value="{{ request('search') }}" class="input-field" placeholder="Nama, email, atau telepon">
        </div>
        <div class="flex items-end">
            <button type="submit" class="btn-primary mr-2">Cari</button>
            <a href="{{ route('admin.client.index') }}" class="btn-secondary">Reset</a>
        </div>
    </form>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="card bg-gradient-to-br from-blue-500 to-blue-600 text-white">
        <p class="text-blue-100 text-sm">Total Client</p>
        <p class="text-3xl font-bold mt-2">{{ $clients->total() }}</p>
    </div>
    <div class="card bg-gradient-to-br from-green-500 to-green-600 text-white">
        <p class="text-green-100 text-sm">Client Aktif</p>
        <p class="text-3xl font-bold mt-2">{{ $clients->where('is_active', true)->count() }}</p>
    </div>
    <div class="card bg-gradient-to-br from-purple-500 to-purple-600 text-white">
        <p class="text-purple-100 text-sm">Total Pesanan</p>
        <p class="text-3xl font-bold mt-2">{{ $clients->sum('pesanan_count') }}</p>
    </div>
</div>

<!-- Table -->
<div class="card">
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
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <img src="{{ $client->foto_url }}" alt="{{ $client->name }}" class="w-10 h-10 rounded-full object-cover mr-3">
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
                        {{ $client->pesanan_count }} pesanan
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $client->created_at->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($client->is_active)
                        <span class="badge-success">Aktif</span>
                        @else
                        <span class="badge-danger">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <a href="{{ route('admin.client.show', $client) }}" class="text-blue-600 hover:text-blue-900">Detail</a>
                        <a href="{{ route('admin.client.edit', $client) }}" class="text-green-600 hover:text-green-900">Edit</a>
                        <form action="{{ route('admin.client.toggle-status', $client) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-yellow-600 hover:text-yellow-900">
                                {{ $client->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada client</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="px-6 py-4">
        {{ $clients->links() }}
    </div>
</div>
@endsection