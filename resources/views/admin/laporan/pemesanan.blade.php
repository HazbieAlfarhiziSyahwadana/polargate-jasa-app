@extends('layouts.admin')

@section('title', 'Laporan Pemesanan')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Laporan Pemesanan</h1>
    <p class="text-gray-600">Laporan data pesanan dan status</p>
</div>

<div class="card mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select name="status" class="input-field">
                <option value="">Semua Status</option>
                <option value="Menunggu Pembayaran DP" {{ request('status') == 'Menunggu Pembayaran DP' ? 'selected' : '' }}>Menunggu Pembayaran DP</option>
                <option value="Sedang Diproses" {{ request('status') == 'Sedang Diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                <option value="Preview Siap" {{ request('status') == 'Preview Siap' ? 'selected' : '' }}>Preview Siap</option>
                <option value="Menunggu Pelunasan" {{ request('status') == 'Menunggu Pelunasan' ? 'selected' : '' }}>Menunggu Pelunasan</option>
                <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Layanan</label>
            <select name="layanan" class="input-field">
                <option value="">Semua Layanan</option>
                @foreach($pesananPerLayanan as $item)
                <option value="{{ $item->layanan_id }}" {{ request('layanan') == $item->layanan_id ? 'selected' : '' }}>
                    {{ $item->layanan->nama_layanan }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="btn-primary mr-2">Filter</button>
            <a href="{{ route('admin.laporan.pemesanan') }}" class="btn-secondary">Reset</a>
        </div>
    </form>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="card bg-blue-50">
        <p class="text-sm text-gray-600">Total Pesanan</p>
        <p class="text-2xl font-bold text-blue-600">{{ $pesanan->count() }}</p>
    </div>
    <div class="card bg-yellow-50">
        <p class="text-sm text-gray-600">Sedang Proses</p>
        <p class="text-2xl font-bold text-yellow-600">{{ $pesananPerStatus->where('status', 'Sedang Diproses')->first()->total ?? 0 }}</p>
    </div>
    <div class="card bg-green-50">
        <p class="text-sm text-gray-600">Selesai</p>
        <p class="text-2xl font-bold text-green-600">{{ $pesananPerStatus->where('status', 'Selesai')->first()->total ?? 0 }}</p>
    </div>
    <div class="card bg-purple-50">
        <p class="text-sm text-gray-600">Total Revenue</p>
        <p class="text-2xl font-bold text-purple-600">Rp {{ number_format($pesanan->sum('total_harga'), 0, ',', '.') }}</p>
    </div>
</div>

<div class="card">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-gray-800">Daftar Pesanan</h2>
        <a href="{{ route('admin.laporan.export-pemesanan', request()->all()) }}" class="btn-success text-sm">
            Export Excel
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Layanan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pesanan as $item)
                <tr>
                    <td class="px-4 py-3">{{ $item->kode_pesanan }}</td>
                    <td class="px-4 py-3">{{ $item->client->name }}</td>
                    <td class="px-4 py-3">{{ $item->layanan->nama_layanan }}</td>
                    <td class="px-4 py-3">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                    <td class="px-4 py-3">
                        @if($item->status == 'Selesai')
                        <span class="badge-success">{{ $item->status }}</span>
                        @else
                        <span class="badge-info">{{ $item->status }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">{{ $item->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection