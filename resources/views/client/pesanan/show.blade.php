@extends('layouts.client')

@section('title', 'Detail Pesanan')

@section('content')
<div class="mb-6">
    <a href="{{ route('client.pesanan.index') }}" class="text-primary-600 hover:text-primary-700 text-sm mb-4 inline-block">
        ← Kembali ke Daftar Pesanan
    </a>
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ $pesanan->kode_pesanan }}</h1>
            <p class="text-gray-600">Detail pesanan Anda</p>
        </div>
        @php
            $statusClass = 'badge-info';
            if($pesanan->status == 'Selesai') $statusClass = 'badge-success';
            if(in_array($pesanan->status, ['Menunggu Pembayaran DP', 'Menunggu Pelunasan'])) $statusClass = 'badge-warning';
        @endphp
        <span class="{{ $statusClass }}">{{ $pesanan->status }}</span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Detail Pesanan -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Info Layanan -->
        <div class="card">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Layanan</h2>
            
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Layanan:</span>
                    <span class="font-semibold text-gray-900">{{ $pesanan->layanan->nama_layanan }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Paket:</span>
                    <span class="font-semibold text-gray-900">{{ $pesanan->paket->nama_paket ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Harga Paket:</span>
                    <span class="font-semibold text-gray-900">Rp {{ number_format($pesanan->harga_paket, 0, ',', '.') }}</span>
                </div>
                
                @if($pesanan->addons->count() > 0)
                <div class="pt-3 border-t">
                    <p class="text-sm font-semibold text-gray-700 mb-2">Add-ons:</p>
                    @foreach($pesanan->addons as $addon)
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">• {{ $addon->nama_addon }}</span>
                        <span class="text-gray-900">Rp {{ number_format($addon->pivot->harga, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
                
                <div class="flex justify-between pt-3 border-t text-lg">
                    <span class="font-bold text-gray-900">Total:</span>
                    <span class="font-bold text-primary-600">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Detail Pesanan -->
        <div class="card">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Detail Pesanan</h2>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $pesanan->detail_pesanan }}</p>
            </div>
        </div>

        <!-- Preview -->
        @if($pesanan->status == 'Preview Siap' && $pesanan->is_preview_active)
        <div class="card bg-blue-50 border border-blue-200">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Preview Tersedia</h2>
            <p class="text-sm text-gray-700 mb-4">Preview pekerjaan Anda sudah siap untuk dilihat.</p>
            <a href="{{ $pesanan->preview_link }}" target="_blank" class="btn-primary">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Lihat Preview
            </a>
        </div>
        @endif

        <!-- File Final -->
        @if($pesanan->status == 'Selesai' && $pesanan->file_final)
        <div class="card bg-green-50 border border-green-200">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Pekerjaan Selesai</h2>
            <p class="text-sm text-gray-700 mb-4">File final sudah tersedia untuk didownload.</p>
            <a href="{{ route('client.pesanan.download-final', $pesanan) }}" class="btn-success">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Download File Final
            </a>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Status Timeline -->
        <div class="card">
            <h3 class="font-semibold text-gray-800 mb-4">Status Pesanan</h3>
            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Pesanan Dibuat</p>
                        <p class="text-xs text-gray-500">{{ $pesanan->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full {{ in_array($pesanan->status, ['Sedang Diproses', 'Preview Siap', 'Menunggu Pelunasan', 'Selesai']) ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Sedang Diproses</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full {{ $pesanan->status == 'Selesai' ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Selesai</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice -->
        <div class="card">
            <h3 class="font-semibold text-gray-800 mb-4">Invoice</h3>
            <div class="space-y-2">
                @foreach($pesanan->invoices as $invoice)
                <div class="bg-gray-50 p-3 rounded flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $invoice->tipe }}</p>
                        <p class="text-xs text-gray-600">Rp {{ number_format($invoice->jumlah, 0, ',', '.') }}</p>
                    </div>
                    @if($invoice->status == 'Lunas')
                    <span class="badge-success text-xs">Lunas</span>
                    @elseif($invoice->status == 'Menunggu Verifikasi')
                    <span class="badge-warning text-xs">Pending</span>
                    @else
                    <a href="{{ route('client.pembayaran.invoice', $invoice) }}" class="text-sm text-blue-600 hover:underline">Bayar</a>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <!-- Contact -->
        <div class="card bg-blue-50 border border-blue-200">
            <h3 class="font-semibold text-gray-800 mb-2">Butuh Bantuan?</h3>
            <p class="text-sm text-gray-700 mb-3">Hubungi kami jika ada pertanyaan</p>
            <a href="https://wa.me/6281234567890" target="_blank" class="text-sm text-blue-600 hover:underline">
                WhatsApp: 0812-3456-7890
            </a>
        </div>
    </div>
</div>
@endsection