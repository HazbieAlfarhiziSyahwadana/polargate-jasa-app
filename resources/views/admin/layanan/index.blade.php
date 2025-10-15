@extends('layouts.admin')

@section('title', 'Kelola Layanan')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Kelola Layanan</h1>
        <p class="text-gray-600">Manajemen layanan yang ditawarkan</p>
    </div>
    <a href="{{ route('admin.layanan.create') }}" class="btn-primary">
        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
        Tambah Layanan
    </a>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="card bg-gradient-to-br from-blue-500 to-blue-600 text-white">
        <p class="text-blue-100 text-sm">Total Layanan</p>
        <p class="text-3xl font-bold mt-2">{{ $layanan->count() }}</p>
    </div>
    <div class="card bg-gradient-to-br from-green-500 to-green-600 text-white">
        <p class="text-green-100 text-sm">Layanan Aktif</p>
        <p class="text-3xl font-bold mt-2">{{ $layanan->where('is_active', true)->count() }}</p>
    </div>
    <div class="card bg-gradient-to-br from-purple-500 to-purple-600 text-white">
        <p class="text-purple-100 text-sm">Total Pesanan</p>
        <p class="text-3xl font-bold mt-2">{{ $layanan->sum('pesanan_count') }}</p>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Layanan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Mulai</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pesanan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($layanan as $item)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <img src="{{ $item->gambar_url }}" alt="{{ $item->nama_layanan }}" class="w-16 h-16 object-cover rounded-lg">
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $item->nama_layanan }}</div>
                        <div class="text-sm text-gray-500">{{ Str::limit($item->deskripsi, 50) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="badge-info">{{ $item->kategori }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        Rp {{ number_format($item->harga_mulai, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $item->pesanan_count }} pesanan
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($item->is_active)
                        <span class="badge-success">Aktif</span>
                        @else
                        <span class="badge-danger">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <a href="{{ route('admin.layanan.edit', $item) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                        <form action="{{ route('admin.layanan.toggle-status', $item) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-yellow-600 hover:text-yellow-900">
                                {{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.layanan.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus layanan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">Belum ada layanan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection