@extends('layouts.admin')

@section('title', 'Detail Invoice')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Invoice {{ $invoice->nomor_invoice }}</h1>
            <p class="text-gray-600">Detail invoice pembayaran</p>
            
            {{-- ✅ Badge pesanan dibatalkan --}}
            @if($invoice->pesanan->status === 'Dibatalkan')
            <div class="mt-2">
                <span class="inline-flex items-center gap-2 px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Pesanan Telah Dibatalkan
                </span>
            </div>
            @endif
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.invoice.download', $invoice) }}" class="btn-primary">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Download PDF
            </a>
            <a href="{{ route('admin.invoice.index') }}" class="btn-secondary">Kembali</a>
        </div>
    </div>
</div>

{{-- ✅ Alert Pembatalan --}}
@if($invoice->status === 'Dibatalkan' || $invoice->pesanan->status === 'Dibatalkan')
<div class="bg-gray-50 border-l-4 border-gray-500 p-4 mb-6 rounded-r-lg">
    <div class="flex items-start gap-3">
        <svg class="w-6 h-6 text-gray-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        <div class="flex-1">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                {{ $invoice->pesanan->status === 'Dibatalkan' ? 'Pesanan Dibatalkan' : 'Invoice Dibatalkan' }}
            </h3>
            <div class="space-y-2">
                <p class="text-sm text-gray-700">
                    <span class="font-medium">Alasan:</span>
                    @if($invoice->pesanan->status === 'Dibatalkan' && $invoice->pesanan->alasan_pembatalan)
                        {{ $invoice->pesanan->alasan_pembatalan }}
                    @elseif($invoice->alasan_penolakan)
                        {{ $invoice->alasan_penolakan }}
                    @else
                        Invoice dibatalkan karena melewati batas jatuh tempo
                    @endif
                </p>
                @if($invoice->pesanan->dibatalkan_at)
                <p class="text-sm text-gray-600 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Dibatalkan pada: {{ \Carbon\Carbon::parse($invoice->pesanan->dibatalkan_at)->format('d M Y H:i') }}
                </p>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Detail Invoice -->
    <div class="lg:col-span-2">
        <div class="card {{ $invoice->status === 'Dibatalkan' ? 'opacity-75' : '' }}">
            <div class="border-b pb-4 mb-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">PT Polargate Indonesia Kreasi</h2>
                        <p class="text-sm text-gray-600 mt-2">
                            Jl. Contoh No. 123<br>
                            Jakarta Selatan, 12345<br>
                            Telepon: (021) 1234-5678<br>
                            Email: info@polargate.com
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="text-3xl font-bold text-primary-600">INVOICE</span>
                        <p class="text-sm text-gray-600 mt-2">{{ $invoice->nomor_invoice }}</p>
                    </div>
                </div>
            </div>

            <!-- Info Invoice -->
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <p class="text-sm font-semibold text-gray-700 mb-2">Tagihan Kepada:</p>
                    <p class="font-semibold text-gray-900">{{ $invoice->pesanan->client->name }}</p>
                    <p class="text-sm text-gray-600">{{ $invoice->pesanan->client->email }}</p>
                    <p class="text-sm text-gray-600">{{ $invoice->pesanan->client->no_telepon }}</p>
                    <p class="text-sm text-gray-600 mt-2">{{ $invoice->pesanan->client->alamat }}</p>
                </div>
                <div class="text-right">
                    <div class="mb-3">
                        <p class="text-sm text-gray-600">Tanggal Invoice</p>
                        <p class="font-semibold text-gray-900">{{ $invoice->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="mb-3">
                        <p class="text-sm text-gray-600">Jatuh Tempo</p>
                        <p class="font-semibold {{ $invoice->is_jatuh_tempo ? 'text-red-600' : 'text-gray-900' }}">
                            {{ $invoice->tanggal_jatuh_tempo->format('d M Y') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        @if($invoice->status == 'Lunas')
                        <span class="badge-success">Lunas</span>
                        @elseif($invoice->status == 'Menunggu Verifikasi')
                        <span class="badge-warning">Menunggu Verifikasi</span>
                        @elseif($invoice->status == 'Dibatalkan')
                        <span class="badge-secondary">Dibatalkan</span>
                        @else
                        <span class="badge-danger">Belum Dibayar</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Detail Pesanan -->
            <div class="mb-6">
                <h3 class="font-semibold text-gray-800 mb-3">Detail Pesanan</h3>
                <div class="bg-gray-50 p-4 rounded">
                    <p class="text-sm text-gray-600">
                        Kode Pesanan: <span class="font-semibold text-gray-900">{{ $invoice->pesanan->kode_pesanan }}</span>
                        @if($invoice->pesanan->status === 'Dibatalkan')
                        <span class="inline-flex items-center gap-1 ml-2 px-2 py-0.5 bg-gray-200 text-gray-700 rounded text-xs">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Dibatalkan
                        </span>
                        @endif
                    </p>
                    <p class="text-sm text-gray-600 mt-1">Layanan: <span class="font-semibold text-gray-900">{{ $invoice->pesanan->layanan->nama_layanan }}</span></p>
                    <p class="text-sm text-gray-600 mt-1">Paket: <span class="font-semibold text-gray-900">{{ $invoice->pesanan->paket->nama_paket ?? '-' }}</span></p>
                </div>
            </div>

            <!-- Tabel Item -->
            <div class="overflow-x-auto mb-6">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-4 py-3">
                                <p class="font-medium text-gray-900">Pembayaran {{ $invoice->tipe }}</p>
                                <p class="text-sm text-gray-600">{{ $invoice->pesanan->layanan->nama_layanan }} - {{ $invoice->pesanan->paket->nama_paket ?? '-' }}</p>
                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-gray-900">
                                Rp {{ number_format($invoice->jumlah, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td class="px-4 py-3 text-right font-bold text-gray-900">Total:</td>
                            <td class="px-4 py-3 text-right font-bold text-primary-600 text-lg">
                                Rp {{ number_format($invoice->jumlah, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Catatan -->
            <div class="border-t pt-4">
                <p class="text-sm font-semibold text-gray-700 mb-2">Catatan Pembayaran:</p>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Harap melakukan pembayaran sebelum tanggal jatuh tempo</li>
                    <li>• Setelah melakukan pembayaran, silakan upload bukti transfer</li>
                    <li>• Pembayaran akan diverifikasi maksimal 1x24 jam</li>
                    <li>• Untuk pertanyaan, hubungi customer service kami</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Status Pembayaran -->
        <div class="card">
            <h3 class="font-semibold text-gray-800 mb-4">Status Pembayaran</h3>
            
            @if($invoice->pembayaran->count() > 0)
            <div class="space-y-3">
                @foreach($invoice->pembayaran as $pembayaran)
                <div class="bg-gray-50 p-3 rounded">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs text-gray-600">{{ $pembayaran->created_at->format('d M Y H:i') }}</span>
                        @if($pembayaran->status == 'Diterima')
                        <span class="badge-success text-xs">Diterima</span>
                        @elseif($pembayaran->status == 'Ditolak')
                        <span class="badge-danger text-xs">Ditolak</span>
                        @else
                        <span class="badge-warning text-xs">Pending</span>
                        @endif
                    </div>
                    <p class="text-sm font-semibold text-gray-900">Rp {{ number_format($pembayaran->jumlah_dibayar, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-600">Via {{ $pembayaran->metode_pembayaran }}</p>
                    
                    @if($pembayaran->bukti_pembayaran)
                    <a href="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" target="_blank" class="text-xs text-blue-600 hover:underline block mt-2">
                        Lihat Bukti Transfer
                    </a>
                    @endif

                    @if($pembayaran->catatan_verifikasi)
                    <p class="text-xs text-gray-600 mt-2 italic">{{ $pembayaran->catatan_verifikasi }}</p>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-4">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="mt-2 text-sm text-gray-500">Belum ada pembayaran</p>
            </div>
            @endif
        </div>

        <!-- Link Pesanan -->
        <div class="card">
            <h3 class="font-semibold text-gray-800 mb-4">Terkait</h3>
            <a href="{{ route('admin.pesanan.show', $invoice->pesanan) }}" class="block text-sm text-blue-600 hover:underline mb-2">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Lihat Detail Pesanan
            </a>
            <a href="{{ route('admin.client.show', $invoice->pesanan->client) }}" class="block text-sm text-blue-600 hover:underline">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Lihat Profil Client
            </a>
        </div>
    </div>
</div>
@endsection