@extends('layouts.client')

@section('title', 'Pembayaran Berhasil')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="card shadow-lg">
            <div class="text-center py-8">
                <!-- Icon Success -->
                <div class="mb-6">
                    <svg class="w-24 h-24 mx-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>

                <!-- Pesan Sukses -->
                <h1 class="text-3xl font-bold text-green-600 mb-3">Bukti Pembayaran Berhasil Diupload!</h1>
                <p class="text-gray-600 mb-6">Bukti pembayaran Anda telah diterima dan akan diverifikasi oleh tim kami.</p>

                <!-- Invoice Info -->
                <div class="bg-gray-50 p-6 rounded-lg mb-6 text-left max-w-md mx-auto">
                    <h3 class="font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Detail Pembayaran
                    </h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Nomor Invoice:</span>
                            <span class="font-semibold text-gray-900">{{ $pembayaran->invoice->nomor_invoice }}</span>
                        </div>
                        
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Jumlah Dibayar:</span>
                            <span class="font-semibold text-green-600">Rp {{ number_format($pembayaran->jumlah_dibayar, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Metode:</span>
                            <span class="font-semibold text-gray-900">{{ $pembayaran->metode_pembayaran }}</span>
                        </div>
                        
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-gray-600">Status:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                {{ $pembayaran->status }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between py-2">
                            <span class="text-gray-600">Waktu Upload:</span>
                            <span class="font-semibold text-gray-900">{{ $pembayaran->created_at->format('d M Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Informasi Selanjutnya -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6 text-left max-w-md mx-auto">
                    <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Langkah Selanjutnya
                    </h3>
                    <ul class="text-sm text-gray-700 space-y-2">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Pembayaran akan diverifikasi maksimal <strong>1x24 jam</strong></span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Anda akan menerima <strong>notifikasi</strong> setelah verifikasi</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Cek status pembayaran di <strong>halaman invoice</strong></span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Jika ada kendala, hubungi <strong>customer service</strong></span>
                        </li>
                    </ul>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex flex-col sm:flex-row justify-center gap-3 mt-8">
                    <a href="{{ route('client.invoice.index') }}" class="btn-primary inline-flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Lihat Daftar Invoice
                    </a>
                    <a href="{{ route('client.dashboard') }}" class="btn-secondary inline-flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Additional Info Card -->
        <div class="mt-6 card bg-gray-50">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-gray-400 mr-3 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h4 class="font-semibold text-gray-800 mb-2">Perlu Bantuan?</h4>
                    <p class="text-sm text-gray-600 mb-2">
                        Jika pembayaran Anda tidak diverifikasi dalam 1x24 jam atau ada pertanyaan, silakan hubungi customer service kami:
                    </p>
                    <div class="text-sm text-gray-700">
                        <p>📧 Email: support@polargate.com</p>
                        <p>📱 WhatsApp: 0812-3456-7890</p>
                        <p>⏰ Senin - Jumat: 09:00 - 17:00 WIB</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection