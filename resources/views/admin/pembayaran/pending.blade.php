@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Verifikasi Pembayaran</h1>
    <p class="text-gray-600">Pembayaran yang menunggu verifikasi</p>
    
    <!-- 🔔 Status Banner dengan Animasi -->
    @if($pembayaran->count() > 0)
        <div class="mt-4 bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-4 rounded-xl shadow-lg animate-pulse">
            <div class="flex items-center gap-3">
                <div class="bg-white/20 p-3 rounded-full">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold">{{ $pembayaran->count() }} Pembayaran Menunggu Verifikasi</h3>
                    <p class="text-sm text-white/90">Segera verifikasi untuk melanjutkan pesanan client</p>
                </div>
                <div class="bg-white/20 px-4 py-2 rounded-lg text-2xl font-bold">
                    {{ $pembayaran->count() }}
                </div>
            </div>
        </div>
    @else
        <div class="mt-4 bg-gradient-to-r from-gray-500 to-gray-600 text-white px-6 py-4 rounded-xl shadow-lg">
            <div class="flex items-center gap-3">
                <div class="bg-white/20 p-3 rounded-full">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold">Tidak Ada Pembayaran Pending</h3>
                    <p class="text-sm text-white/90">Semua pembayaran sudah diverifikasi</p>
                </div>
            </div>
        </div>
    @endif
</div>

@if($pembayaran->count() > 0)
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    @foreach($pembayaran as $item)
    <div class="card relative overflow-hidden">
        <!-- Badge "BARU" untuk pembayaran hari ini -->
        @if($item->created_at->isToday())
            <div class="absolute top-4 right-4 z-10">
                <span class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg animate-bounce flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                    </svg>
                    BARU HARI INI
                </span>
            </div>
        @endif

        <div class="flex justify-between items-start mb-4">
            <div>
                <h3 class="font-bold text-gray-800">{{ $item->invoice->nomor_invoice }}</h3>
                <p class="text-sm text-gray-600">{{ $item->invoice->pesanan->kode_pesanan }}</p>
            </div>
            <span class="badge-warning">Pending</span>
        </div>

        <!-- Info Client -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-3 rounded-lg mb-4 border border-blue-100">
            <p class="text-xs text-blue-600 font-semibold mb-1">CLIENT</p>
            <p class="font-semibold text-gray-900">{{ $item->invoice->pesanan->client->name }}</p>
            <p class="text-sm text-gray-600">{{ $item->invoice->pesanan->client->email }}</p>
        </div>

        <!-- Detail Pembayaran dengan Icon -->
        <div class="space-y-2 mb-4">
            <div class="flex justify-between items-center text-sm bg-gray-50 p-2 rounded">
                <span class="text-gray-600 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                    Tipe Invoice
                </span>
                <span class="font-medium text-gray-900">{{ $item->invoice->tipe }}</span>
            </div>
            <div class="flex justify-between items-center text-sm bg-gray-50 p-2 rounded">
                <span class="text-gray-600 flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                    Jumlah Tagihan
                </span>
                <span class="font-medium text-gray-900">Rp {{ number_format($item->invoice->jumlah, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between items-center text-sm bg-green-50 p-2 rounded border border-green-200">
                <span class="text-green-700 font-semibold flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Jumlah Dibayar
                </span>
                <span class="font-bold text-green-700">Rp {{ number_format($item->jumlah_dibayar, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between items-center text-sm bg-gray-50 p-2 rounded">
                <span class="text-gray-600 flex items-center gap-2">
                    <svg class="w-4 h-4 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                        <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                    </svg>
                    Metode
                </span>
                <span class="font-medium text-gray-900">{{ $item->metode_pembayaran }}</span>
            </div>
            <div class="flex justify-between items-center text-sm bg-gray-50 p-2 rounded">
                <span class="text-gray-600 flex items-center gap-2">
                    <svg class="w-4 h-4 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    Tanggal Upload
                </span>
                <span class="font-medium text-gray-900">{{ $item->created_at->format('d M Y H:i') }}</span>
            </div>
        </div>

        <!-- Bukti Transfer -->
        <div class="mb-4">
            <p class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                </svg>
                Bukti Transfer
            </p>
            <a href="{{ asset('storage/' . $item->bukti_pembayaran) }}" target="_blank" class="block">
                <img src="{{ asset('storage/' . $item->bukti_pembayaran) }}" 
                     alt="Bukti Transfer" 
                     class="w-full h-48 object-contain bg-gray-100 rounded-lg border-2 border-gray-200 cursor-pointer hover:border-blue-500 hover:shadow-lg transition-all duration-300">
            </a>
            <p class="text-xs text-gray-500 mt-2 text-center flex items-center justify-center gap-1">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                </svg>
                Klik untuk memperbesar
            </p>
        </div>

        <!-- Form Verifikasi -->
        <div class="border-t pt-4">
            <!-- Form Terima -->
            <form action="{{ route('admin.pembayaran.verify', $item) }}" method="POST" class="mb-2" onsubmit="return confirm('✅ Yakin menerima pembayaran ini?')">
                @csrf
                <textarea name="catatan_verifikasi" rows="2" 
                          class="input-field text-sm mb-2" 
                          placeholder="💬 Catatan verifikasi (opsional)"></textarea>
                <button type="submit" class="btn-success w-full group hover:scale-[1.02] transition-transform">
                    <svg class="w-5 h-5 inline mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Terima Pembayaran
                </button>
            </form>
            
            <!-- Form Tolak -->
            <div x-data="{ showReject: false }">
                <button type="button" 
                        @click="showReject = !showReject" 
                        class="btn-danger w-full group hover:scale-[1.02] transition-transform">
                    <svg class="w-5 h-5 inline mr-2 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Tolak Pembayaran
                </button>
                
                <!-- Input Alasan Penolakan -->
                <form action="{{ route('admin.pembayaran.reject', $item->id) }}" method="POST" 
                      x-show="showReject" 
                      x-transition 
                      class="mt-3 p-4 bg-red-50 rounded-lg border-2 border-red-200">
                    @csrf
                    <label class="block text-sm font-bold text-red-800 mb-2 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        Alasan Penolakan <span class="text-red-600">*</span>
                    </label>
                    <textarea 
                        name="alasan_penolakan" 
                        rows="3" 
                        class="w-full px-3 py-2 border-2 border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none text-sm mb-3" 
                        placeholder="Contoh: Bukti transfer tidak jelas, nominal tidak sesuai, atau rekening tujuan salah"
                        required></textarea>
                    
                    <div class="bg-red-100 border-l-4 border-red-500 p-3 mb-3 rounded">
                        <p class="text-xs text-red-700 flex items-start gap-2">
                            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <span>Alasan ini akan dikirimkan ke client. Pastikan penjelasan Anda jelas dan membantu.</span>
                        </p>
                    </div>
                    
                    <div class="flex gap-2">
                        <button type="button" 
                                @click="showReject = false" 
                                class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition font-medium text-sm">
                            Batal
                        </button>
                        <button type="submit" 
                                class="flex-1 btn-danger text-sm hover:scale-105 transition-transform">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Konfirmasi Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $pembayaran->links() }}
</div>

@else
<!-- Empty State -->
<div class="card text-center py-16">
    <div class="max-w-md mx-auto">
        <div class="bg-gray-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Tidak Ada Pembayaran Pending</h3>
        <p class="text-gray-500 mb-4">Semua pembayaran sudah diverifikasi. Bagus! 🎉</p>
        <a href="{{ route('admin.pesanan.index') }}" class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-semibold">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Pesanan
        </a>
    </div>
</div>
@endif
@endsection