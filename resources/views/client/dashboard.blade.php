@extends('layouts.client')

@section('title', 'Dashboard Client')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
    <p class="text-gray-600">Selamat datang, {{ Auth::user()->name }}!</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
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

    <div class="card bg-gradient-to-br from-green-500 to-green-600 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium">Pesanan Selesai</p>
                <p class="text-3xl font-bold mt-2">{{ $pesanan_selesai }}</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="card bg-gradient-to-br from-purple-500 to-purple-600 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium">Total Belanja</p>
                <p class="text-2xl font-bold mt-2">Rp {{ number_format($total_pembayaran, 0, ',', '.') }}</p>
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
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">Pesanan Terbaru</h2>
            <a href="{{ route('client.pesanan.index') }}" class="text-sm text-primary-600 hover:text-primary-700">Lihat Semua →</a>
        </div>

        <div class="space-y-4">
            @forelse($pesanan_terbaru as $pesanan)
            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <p class="font-semibold text-gray-800">{{ $pesanan->kode_pesanan }}</p>
                        <p class="text-sm text-gray-600">{{ $pesanan->layanan->nama_layanan }}</p>
                    </div>
                    @php
                        $statusClass = 'badge-info';
                        if($pesanan->status == 'Selesai') $statusClass = 'badge-success';
                        if(in_array($pesanan->status, ['Menunggu Pembayaran DP', 'Menunggu Pelunasan'])) $statusClass = 'badge-warning';
                    @endphp
                    <span class="{{ $statusClass }} text-xs">{{ $pesanan->status }}</span>
                </div>
                <div class="flex justify-between items-center mt-3">
                    <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                    <a href="{{ route('client.pesanan.show', $pesanan) }}" class="text-sm text-blue-600 hover:underline">Detail</a>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">Belum ada pesanan</p>
            @endforelse
        </div>

        <a href="{{ route('client.layanan.index') }}" class="btn-primary w-full mt-4">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Buat Pesanan Baru
        </a>
    </div>

    <!-- Invoice Pending -->
    <div class="card">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">Invoice Pending</h2>
        </div>

        <div class="space-y-4">
            @forelse($invoice_pending as $invoice)
            <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <p class="font-semibold text-gray-800">{{ $invoice->nomor_invoice }}</p>
                        <p class="text-sm text-gray-600">{{ $invoice->pesanan->layanan->nama_layanan }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $invoice->tipe }} - Jatuh tempo: {{ $invoice->tanggal_jatuh_tempo->format('d M Y') }}</p>
                    </div>
                    <span class="badge-warning text-xs">{{ $invoice->status }}</span>
                </div>
                <div class="flex justify-between items-center mt-3">
                    <span class="text-lg font-bold text-gray-900">Rp {{ number_format($invoice->jumlah, 0, ',', '.') }}</span>
                    <a href="{{ route('client.pembayaran.invoice', $invoice) }}" class="btn-primary text-sm">Bayar</a>
                </div>
            </div>
            @empty
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="mt-2 text-gray-500">Tidak ada invoice pending</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection