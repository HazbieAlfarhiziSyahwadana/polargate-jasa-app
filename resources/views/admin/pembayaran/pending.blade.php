@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Verifikasi Pembayaran</h1>
    <p class="text-gray-600">Pembayaran yang menunggu verifikasi</p>
</div>

@if($pembayaran->count() > 0)
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    @foreach($pembayaran as $item)
    <div class="card">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h3 class="font-bold text-gray-800">{{ $item->invoice->nomor_invoice }}</h3>
                <p class="text-sm text-gray-600">{{ $item->invoice->pesanan->kode_pesanan }}</p>
            </div>
            <span class="badge-warning">Pending</span>
        </div>

        <!-- Info Client -->
        <div class="bg-gray-50 p-3 rounded mb-4">
            <p class="text-sm text-gray-600">Client:</p>
            <p class="font-semibold text-gray-900">{{ $item->invoice->pesanan->client->name }}</p>
            <p class="text-sm text-gray-600">{{ $item->invoice->pesanan->client->email }}</p>
        </div>

        <!-- Detail Pembayaran -->
        <div class="space-y-2 mb-4">
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Tipe Invoice:</span>
                <span class="font-medium text-gray-900">{{ $item->invoice->tipe }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Jumlah Tagihan:</span>
                <span class="font-medium text-gray-900">Rp {{ number_format($item->invoice->jumlah, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Jumlah Dibayar:</span>
                <span class="font-semibold text-primary-600">Rp {{ number_format($item->jumlah_dibayar, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Metode:</span>
                <span class="font-medium text-gray-900">{{ $item->metode_pembayaran }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Tanggal Upload:</span>
                <span class="font-medium text-gray-900">{{ $item->created_at->format('d M Y H:i') }}</span>
            </div>
        </div>

        <!-- Bukti Transfer -->
        <div class="mb-4">
            <p class="text-sm text-gray-600 mb-2">Bukti Transfer:</p>
            <a href="{{ asset('uploads/pembayaran/' . $item->bukti_pembayaran) }}" target="_blank">
                <img src="{{ asset('uploads/pembayaran/' . $item->bukti_pembayaran) }}" alt="Bukti Transfer" class="w-full h-48 object-contain bg-gray-100 rounded border cursor-pointer hover:opacity-80">
            </a>
        </div>

        <!-- Form Verifikasi -->
        <div class="border-t pt-4">
            <form action="{{ route('admin.pembayaran.verify', $item) }}" method="POST" class="mb-2" onsubmit="return confirm('Yakin menerima pembayaran ini?')">
                @csrf
                <textarea name="catatan_verifikasi" rows="2" class="input-field text-sm mb-2" placeholder="Catatan (opsional)"></textarea>
                <button type="submit" class="btn-success w-full">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Terima Pembayaran
                </button>
            </form>
            
            <form action="{{ route('admin.pembayaran.reject', $item) }}" method="POST" x-data="{ showReject: false }">
                @csrf
                <button type="button" @click="showReject = !showReject" class="btn-danger w-full">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Tolak Pembayaran
                </button>
                
                <div x-show="showReject" x-transition class="mt-2">
                    <textarea name="catatan_verifikasi" rows="2" class="input-field text-sm mb-2" placeholder="Alasan penolakan (wajib)" required></textarea>
                    <button type="submit" class="btn-danger w-full" onclick="return confirm('Yakin menolak pembayaran ini?')">
                        Konfirmasi Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endforeach
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $pembayaran->links() }}
</div>

@else
<div class="card text-center py-12">
    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak Ada Pembayaran Pending</h3>
    <p class="mt-2 text-sm text-gray-500">Semua pembayaran sudah diverifikasi</p>
</div>
@endif
@endsection