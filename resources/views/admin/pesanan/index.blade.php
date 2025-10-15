@extends('layouts.admin')

@section('title', 'Kelola Pesanan')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Kelola Pesanan</h1>
    <p class="text-gray-600">Manajemen pesanan client</p>
</div>

<!-- Filter -->
<div class="card mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Filter Status</label>
            <select name="status" id="status" class="input-field" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="Menunggu Pembayaran DP" {{ request('status') == 'Menunggu Pembayaran DP' ? 'selected' : '' }}>Menunggu Pembayaran DP</option>
                <option value="DP Dibayar - Menunggu Verifikasi" {{ request('status') == 'DP Dibayar - Menunggu Verifikasi' ? 'selected' : '' }}>DP Dibayar - Menunggu Verifikasi</option>
                <option value="Sedang Diproses" {{ request('status') == 'Sedang Diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                <option value="Preview Siap" {{ request('status') == 'Preview Siap' ? 'selected' : '' }}>Preview Siap</option>
                <option value="Revisi Diminta" {{ request('status') == 'Revisi Diminta' ? 'selected' : '' }}>Revisi Diminta</option>
                <option value="Menunggu Pelunasan" {{ request('status') == 'Menunggu Pelunasan' ? 'selected' : '' }}>Menunggu Pelunasan</option>
                <option value="Pelunasan Dibayar - Menunggu Verifikasi" {{ request('status') == 'Pelunasan Dibayar - Menunggu Verifikasi' ? 'selected' : '' }}>Pelunasan Dibayar - Menunggu Verifikasi</option>
                <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="Dibatalkan" {{ request('status') == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
        </div>
        <div>
            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Pesanan</label>
            <input type="text" name="search" id="search" value="{{ request('search') }}" class="input-field" placeholder="Kode pesanan atau nama client">
        </div>
        <div class="flex items-end">
            <button type="submit" class="btn-primary mr-2">Cari</button>
            <a href="{{ route('admin.pesanan.index') }}" class="btn-secondary">Reset</a>
        </div>
    </form>
</div>

<!-- Table -->
<div class="card">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Pesanan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Layanan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Harga</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pesanan as $item)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-gray-900">{{ $item->kode_pesanan }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $item->client->name }}</div>
                        <div class="text-sm text-gray-500">{{ $item->client->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $item->layanan->nama_layanan }}</div>
                        <div class="text-sm text-gray-500">{{ $item->paket->nama_paket ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $statusClass = 'badge-info';
                            if(in_array($item->status, ['Selesai'])) $statusClass = 'badge-success';
                            if(in_array($item->status, ['Menunggu Pembayaran DP', 'Menunggu Pelunasan'])) $statusClass = 'badge-warning';
                            if(in_array($item->status, ['Dibatalkan'])) $statusClass = 'badge-danger';
                        @endphp
                        <span class="{{ $statusClass }} text-xs">{{ $item->status }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $item->created_at->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('admin.pesanan.show', $item) }}" class="text-blue-600 hover:text-blue-900">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada pesanan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="px-6 py-4">
        {{ $pesanan->links() }}
    </div>
</div>
@endsection