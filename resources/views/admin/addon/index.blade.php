@extends('layouts.admin')

@section('title', 'Kelola Add-on')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Kelola Add-on</h1>
        <p class="text-gray-600">Manajemen add-on layanan</p>
    </div>
    <a href="{{ route('admin.addon.create') }}" class="btn-primary">
        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
        Tambah Add-on
    </a>
</div>

<div class="card">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Add-on</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Layanan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($addons as $addon)
                <tr>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $addon->nama_addon }}</div>
                        <div class="text-sm text-gray-500">{{ Str::limit($addon->deskripsi, 60) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="badge-info">{{ $addon->layanan->nama_layanan }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        Rp {{ number_format($addon->harga, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($addon->is_active)
                        <span class="badge-success">Aktif</span>
                        @else
                        <span class="badge-danger">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <a href="{{ route('admin.addon.edit', $addon) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                        <form action="{{ route('admin.addon.toggle-status', $addon) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-yellow-600 hover:text-yellow-900">
                                {{ $addon->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.addon.destroy', $addon) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus add-on ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada add-on</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection