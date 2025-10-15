@extends('layouts.admin')

@section('title', 'Detail Client')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Detail Client</h1>
            <p class="text-gray-600">Informasi lengkap client</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.client.edit', $user) }}" class="btn-primary">Edit Client</a>
            <a href="{{ route('admin.client.index') }}" class="btn-secondary">Kembali</a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Info Client -->
    <div class="lg:col-span-1 space-y-6">
        <div class="card">
            <div class="text-center mb-6">
                <img src="{{ $user->foto_url }}" alt="{{ $user->name }}" class="w-32 h-32 rounded-full object-cover mx-auto mb-4">
                <h2 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h2>
                <p class="text-gray-600">{{ $user->email }}</p>
                @if($user->is_active)
                <span class="badge-success mt-2">Aktif</span>
                @else
                <span class="badge-danger mt-2">Nonaktif</span>
                @endif
            </div>

            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Jenis Kelamin:</span>
                    <span class="font-medium text-gray-900">{{ $user->jenis_kelamin }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Usia:</span>
                    <span class="font-medium text-gray-900">{{ $user->usia }} tahun</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Tanggal Lahir:</span>
                    <span class="font-medium text-gray-900">{{ $user->tanggal_lahir->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">No. Telepon:</span>
                    <span class="font-medium text-gray-900">{{ $user->no_telepon }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Bergabung:</span>
                    <span class="font-medium text-gray-900">{{ $user->created_at->format('d M Y') }}</span>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t">
                <p class="text-sm text-gray-600 mb-2">Alamat:</p>
                <p class="text-sm text-gray-900">{{ $user->alamat }}</p>
            </div>

            <!-- Toggle Status -->
            <form action="{{ route('admin.client.toggle-status', $user) }}" method="POST" class="mt-6">
                @csrf
                @method('PATCH')
                <button type="submit" class="w-full {{ $user->is_active ? 'btn-danger' : 'btn-success' }}">
                    {{ $user->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}
                </button>
            </form>
        </div>

        <!-- Stats -->
        <div class="card">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Statistik</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total Pesanan</span>
                    <span class="text-2xl font-bold text-blue-600">{{ $user->pesanan->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Pesanan Selesai</span>
                    <span class="text-2xl font-bold text-green-600">{{ $user->pesanan->where('status', 'Selesai')->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total Belanja</span>
                    <span class="text-lg font-bold text-purple-600">
                        Rp {{ number_format($user->pesanan->sum('total_harga'), 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Pesanan -->
    <div class="lg:col-span-2">
        <div class="card">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Riwayat Pesanan</h2>
            
            @if($user->pesanan->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Pesanan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Layanan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($user->pesanan->sortByDesc('created_at') as $pesanan)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $pesanan->kode_pesanan }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $pesanan->layanan->nama_layanan }}</div>
                                <div class="text-xs text-gray-500">{{ $pesanan->paket->nama_paket ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClass = 'badge-info';
                                    if($pesanan->status == 'Selesai') $statusClass = 'badge-success';
                                    if(in_array($pesanan->status, ['Menunggu Pembayaran DP', 'Menunggu Pelunasan'])) $statusClass = 'badge-warning';
                                    if($pesanan->status == 'Dibatalkan') $statusClass = 'badge-danger';
                                @endphp
                                <span class="{{ $statusClass }} text-xs">{{ $pesanan->status }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $pesanan->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('admin.pesanan.show', $pesanan) }}" class="text-blue-600 hover:text-blue-900">Detail</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p class="mt-2 text-gray-500">Belum ada pesanan</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection