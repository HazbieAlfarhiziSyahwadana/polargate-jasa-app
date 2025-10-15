@extends('layouts.client')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <!-- Icon Success -->
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
                    </div>

                    <!-- Pesan Sukses -->
                    <h3 class="text-success mb-3">Bukti Pembayaran Berhasil Diupload!</h3>
                    <p class="text-gray-600 mb-4">Bukti pembayaran Anda telah diterima dan akan diverifikasi oleh tim kami.</p>

                    <!-- Invoice Info -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-6 text-left">
                        <h3 class="font-semibold text-gray-800 mb-2">📄 Informasi Selanjutnya</h3>
                        <ul class="text-gray-700 space-y-1">
                            <li>• <strong>Invoice:</strong> {{ $pembayaran->invoice->nomor_invoice }}</li>
                            <li>• <strong>Jumlah:</strong> Rp {{ number_format($pembayaran->jumlah_dibayar, 0, ',', '.') }}</li>
                            <li>• Pembayaran akan diverifikasi maksimal 1x24 jam</li>
                            <li>• Anda akan menerima notifikasi setelah verifikasi</li>
                            <li>• Cek status pembayaran di halaman invoice</li>
                        </ul>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('client.invoice.index') }}" class="btn btn-primary">
                            <i class="fas fa-file-invoice me-2"></i>Lihat Invoice
                        </a>
                        <a href="{{ route('client.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-home me-2"></i>Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection