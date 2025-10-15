@extends('layouts.admin')

@section('title', 'Laporan Client')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Laporan Client</h1>
    <p class="text-gray-600">Data statistik client dan aktivitas</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="card bg-blue-50">
        <p class="text-sm text-gray-600">Total Client</p>
        <p class="text-3xl font-bold text-blue-600">{{ $totalClient }}</p>
    </div>
    <div class="card bg-green-50">
        <p class="text-sm text-gray-600">Client Aktif</p>
        <p class="text-3xl font-bold text-green-600">{{ $clientAktif }}</p>
    </div>
    <div class="card bg-purple-50">
        <p class="text-sm text-gray-600">Client Baru (Bulan Ini)</p>
        <p class="text-3xl font-bold text-purple-600">{{ $clientBaru }}</p>
    </div>
    <div class="card bg-yellow-50">
        <p class="text-sm text-gray-600">Total Transaksi</p>
        <p class="text-2xl font-bold text-yellow-600">Rp {{ number_format($clients->sum(fn($c) => $c->pesanan->sum('total_harga')), 0, ',', '.') }}</p>
    </div>
</div>

<div class="card">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Daftar Client</h2>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Pesanan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Belanja</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bergabung</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($clients as $client)
                <tr>
                    <td class="px-4 py-3">{{ $client->name }}</td>
                    <td class="px-4 py-3">{{ $client->email }}</td>
                    <td class="px-4 py-3">{{ $client->pesanan->count() }}</td>
                    <td class="px-4 py-3">Rp {{ number_format($client->pesanan->sum('total_harga'), 0, ',', '.') }}</td>
                    <td class="px-4 py-3">{{ $client->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection