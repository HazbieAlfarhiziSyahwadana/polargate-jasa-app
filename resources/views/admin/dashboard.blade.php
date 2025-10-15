@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
    <p class="text-gray-600">Selamat datang, {{ Auth::user()->name }}!</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Total Pesanan -->
    <div class="card bg-gradient-to-br from-blue-500 to-blue-600 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium">Total Pesanan</p>
                <p class="text-3xl font-bold mt-2">{{ $total_pesanan }}</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Total Client -->
    <div class="card bg-gradient-to-br from-green-500 to-green-600 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium">Total Client</p>
                <p class="text-3xl font-bold mt-2">{{ $total_client }}</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Pesanan Aktif -->
    <div class="card bg-gradient-to-br from-yellow-500 to-yellow-600 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-yellow-100 text-sm font-medium">Pesanan Aktif</p>
                <p class="text-3xl font-bold mt-2">{{ $pesanan_aktif }}</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Pendapatan Total -->
    <div class="card bg-gradient-to-br from-purple-500 to-purple-600 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium">Pendapatan Total</p>
                <p class="text-3xl font-bold mt-2">Rp {{ number_format($pendapatan_total, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Pesanan Terbaru -->
    <div class="card">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Pesanan Terbaru</h2>
        <div class="space-y-4">
            @forelse($pesanan_terbaru as $pesanan)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex-1">
                    <p class="font-semibold text-gray-800">{{ $pesanan->kode_pesanan }}</p>
                    <p class="text-sm text-gray-600">{{ $pesanan->client->name }}</p>
                    <p class="text-sm text-gray-500">{{ $pesanan->layanan->nama_layanan }}</p>
                </div>
                <div class="text-right">
                    <span class="badge-info text-xs">{{ $pesanan->status }}</span>
                    <p class="text-xs text-gray-500 mt-1">{{ $pesanan->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">Belum ada pesanan</p>
            @endforelse
        </div>
        <a href="{{ route('admin.pesanan.index') }}" class="block text-center text-primary-600 hover:text-primary-700 font-medium mt-4">
            Lihat Semua Pesanan →
        </a>
    </div>

    <!-- Pembayaran Pending -->
    <div class="card">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Pembayaran Menunggu Verifikasi</h2>
        <div class="space-y-4">
            @forelse($pembayaran_pending as $pembayaran)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex-1">
                    <p class="font-semibold text-gray-800">{{ $pembayaran->invoice->nomor_invoice }}</p>
                    <p class="text-sm text-gray-600">{{ $pembayaran->invoice->pesanan->client->name }}</p>
                    <p class="text-sm font-medium text-gray-800">Rp {{ number_format($pembayaran->jumlah_dibayar, 0, ',', '.') }}</p>
                </div>
                <div class="text-right">
                    <span class="badge-warning text-xs">Pending</span>
                    <p class="text-xs text-gray-500 mt-1">{{ $pembayaran->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">Tidak ada pembayaran pending</p>
            @endforelse
        </div>
        <a href="{{ route('admin.pembayaran.pending') }}" class="block text-center text-primary-600 hover:text-primary-700 font-medium mt-4">
            Lihat Semua Pembayaran →
        </a>
    </div>
</div>

<!-- Layanan Populer -->
<div class="card mt-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Layanan Paling Populer</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Layanan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Pesanan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Mulai</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($layanan_populer as $layanan)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-gray-900">{{ $layanan->nama_layanan }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="badge-info">{{ $layanan->kategori }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $layanan->pesanan_count }} pesanan
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        Rp {{ number_format($layanan->harga_mulai, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection