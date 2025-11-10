@extends('layouts.client')

@section('title', 'Dashboard Client')

@section('content')
<style>
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Animasi hanya untuk first load */
    .page-load .animate-fade {
        animation: fadeIn 0.4s ease-out;
    }

    .page-load .animate-slide {
        animation: slideUp 0.5s ease-out;
        opacity: 0;
        animation-fill-mode: forwards;
    }

    .page-load .delay-100 { animation-delay: 0.1s; }
    .page-load .delay-200 { animation-delay: 0.2s; }
    .page-load .delay-300 { animation-delay: 0.3s; }
    .page-load .delay-400 { animation-delay: 0.4s; }

    .card {
        transition: box-shadow 0.3s ease, transform 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        transform: translateY(-2px);
    }

    .stats-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stats-card:hover {
        transform: scale(1.03);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    }

    .item-row {
        transition: all 0.2s ease;
    }

    .item-row:hover {
        background-color: #f9fafb;
        transform: translateX(4px);
    }

    @media (max-width: 768px) {
        .overflow-x-auto {
            -webkit-overflow-scrolling: touch;
        }
    }

    /* Pulse animation for realtime update */
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    .updating {
        animation: pulse 1s ease-in-out;
    }
</style>

<div class="mb-6 animate-fade">
    <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
    <p class="text-gray-600">Selamat datang, {{ Auth::user()->name }}!</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6">
    <div class="card bg-gradient-to-br from-blue-500 to-blue-600 text-white stats-card animate-slide cursor-pointer">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium">Total Pesanan</p>
                <p class="text-3xl font-bold mt-2" id="total-pesanan">{{ $total_pesanan }}</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="card bg-gradient-to-br from-yellow-500 to-yellow-600 text-white stats-card animate-slide delay-100 cursor-pointer">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-yellow-100 text-sm font-medium">Pesanan Aktif</p>
                <p class="text-3xl font-bold mt-2" id="pesanan-aktif">{{ $pesanan_aktif }}</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="card bg-gradient-to-br from-green-500 to-green-600 text-white stats-card animate-slide delay-200 cursor-pointer">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium">Pesanan Selesai</p>
                <p class="text-3xl font-bold mt-2" id="pesanan-selesai">{{ $pesanan_selesai }}</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="card bg-gradient-to-br from-purple-500 to-purple-600 text-white stats-card animate-slide delay-300 cursor-pointer">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium">Total Belanja</p>
                <p class="text-2xl md:text-3xl font-bold mt-2" id="total-pembayaran">Rp {{ number_format($total_pembayaran, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
    <!-- Pesanan Terbaru -->
    <div class="card animate-slide delay-100">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-800">Pesanan Terbaru</h2>
            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>

        <div class="space-y-3" id="pesanan-terbaru-container">
            @forelse($pesanan_terbaru as $pesanan)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg item-row">
                <div class="flex-1">
                    <p class="font-semibold text-gray-800">{{ $pesanan->kode_pesanan }}</p>
                    <p class="text-sm text-gray-600">{{ $pesanan->layanan->nama_layanan }}</p>
                    <p class="text-sm font-medium text-gray-900 mt-1">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</p>
                </div>
                <div class="text-right">
                    @php
                        $statusClass = 'badge-info';
                        if($pesanan->status == 'Selesai') $statusClass = 'badge-success';
                        if(in_array($pesanan->status, ['Menunggu Pembayaran DP', 'Menunggu Pelunasan'])) $statusClass = 'badge-warning';
                    @endphp
                    <span class="{{ $statusClass }} text-xs">{{ $pesanan->status }}</span>
                    <a href="{{ route('client.pesanan.show', $pesanan) }}" class="block text-sm text-blue-600 hover:underline mt-1">Detail</a>
                </div>
            </div>
            @empty
            <div class="text-center py-8">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-gray-500">Belum ada pesanan</p>
            </div>
            @endforelse
        </div>

        <a href="{{ route('client.layanan.index') }}" class="btn-primary w-full mt-4 flex items-center justify-center">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Buat Pesanan Baru
        </a>
    </div>

    <!-- Invoice Pending -->
    <div class="card animate-slide delay-200">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-800">Invoice Pending</h2>
            <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <div class="space-y-3" id="invoice-pending-container">
            @forelse($invoice_pending as $invoice)
            <div class="flex items-center justify-between p-4 bg-yellow-50 border border-yellow-200 rounded-lg item-row">
                <div class="flex-1">
                    <p class="font-semibold text-gray-800">{{ $invoice->nomor_invoice }}</p>
                    <p class="text-sm text-gray-600">{{ $invoice->pesanan->layanan->nama_layanan }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $invoice->tipe }} - Jatuh tempo: {{ $invoice->tanggal_jatuh_tempo->format('d M Y') }}</p>
                    <p class="text-lg font-bold text-gray-900 mt-2">Rp {{ number_format($invoice->jumlah, 0, ',', '.') }}</p>
                </div>
                <div class="text-right flex flex-col items-end gap-2">
                    @php
                        // Cek status pembayaran
                        $latestPayment = $invoice->pembayaran()->latest()->first();
                        $hasPendingPayment = $invoice->pembayaran()
                            ->whereIn('status', ['Menunggu Verifikasi', 'Terverifikasi'])
                            ->exists();
                        $hasRejectedPayment = $latestPayment && $latestPayment->status == 'Ditolak';
                    @endphp
                    
                    @if($hasPendingPayment)
                        <span class="badge-warning text-xs">Menunggu Verifikasi</span>
                    @elseif($hasRejectedPayment)
                        <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full font-medium">Pembayaran Ditolak</span>
                        <a href="{{ route('client.pembayaran.invoice', $invoice) }}" class="btn-primary text-sm">Bayar Ulang</a>
                    @else
                        <span class="badge-warning text-xs">{{ $invoice->status }}</span>
                        <a href="{{ route('client.pembayaran.invoice', $invoice) }}" class="btn-primary text-sm">Bayar</a>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-8">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-gray-500">Tidak ada invoice pending</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cek apakah ini first load
        const isFirstLoad = !sessionStorage.getItem('dashboard_loaded');
        
        if (isFirstLoad) {
            document.body.classList.add('page-load');
            sessionStorage.setItem('dashboard_loaded', 'true');
            
            setTimeout(() => {
                document.body.classList.remove('page-load');
            }, 1000);
        }

        // Setup realtime updates
        setupRealtimeUpdates();
    });

    // Reset flag saat navigasi ke halaman lain
    window.addEventListener('beforeunload', function(e) {
        if (e.target.activeElement.tagName === 'A' && 
            e.target.activeElement.getAttribute('href') !== '#') {
            sessionStorage.removeItem('dashboard_loaded');
        }
    });

    // Fungsi untuk format rupiah
    function formatRupiah(angka) {
        return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Fungsi untuk mendapatkan badge class
    function getStatusBadgeClass(status) {
        if (status === 'Selesai') return 'badge-success';
        if (status === 'Menunggu Pembayaran DP' || status === 'Menunggu Pelunasan') return 'badge-warning';
        return 'badge-info';
    }

    // Fungsi untuk format tanggal
    function formatTanggal(tanggal) {
        const bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        const date = new Date(tanggal);
        return date.getDate() + ' ' + bulan[date.getMonth()] + ' ' + date.getFullYear();
    }

    // Fungsi untuk update data dashboard
    function updateDashboard(data) {
        // Update stats cards dengan animasi
        const totalPesanan = document.getElementById('total-pesanan');
        const pesananAktif = document.getElementById('pesanan-aktif');
        const pesananSelesai = document.getElementById('pesanan-selesai');
        const totalPembayaran = document.getElementById('total-pembayaran');

        if (totalPesanan.textContent != data.total_pesanan) {
            totalPesanan.classList.add('updating');
            totalPesanan.textContent = data.total_pesanan;
            setTimeout(() => totalPesanan.classList.remove('updating'), 1000);
        }

        if (pesananAktif.textContent != data.pesanan_aktif) {
            pesananAktif.classList.add('updating');
            pesananAktif.textContent = data.pesanan_aktif;
            setTimeout(() => pesananAktif.classList.remove('updating'), 1000);
        }

        if (pesananSelesai.textContent != data.pesanan_selesai) {
            pesananSelesai.classList.add('updating');
            pesananSelesai.textContent = data.pesanan_selesai;
            setTimeout(() => pesananSelesai.classList.remove('updating'), 1000);
        }

        const formattedPembayaran = formatRupiah(data.total_pembayaran);
        if (totalPembayaran.textContent != formattedPembayaran) {
            totalPembayaran.classList.add('updating');
            totalPembayaran.textContent = formattedPembayaran;
            setTimeout(() => totalPembayaran.classList.remove('updating'), 1000);
        }

        // Update pesanan terbaru
        updatePesananTerbaru(data.pesanan_terbaru);

        // Update invoice pending
        updateInvoicePending(data.invoice_pending);
    }

    // Update pesanan terbaru
    function updatePesananTerbaru(pesanan) {
        const container = document.getElementById('pesanan-terbaru-container');
        
        if (pesanan.length === 0) {
            container.innerHTML = `
                <div class="text-center py-8">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-gray-500">Belum ada pesanan</p>
                </div>
            `;
            return;
        }

        let html = '';
        pesanan.forEach(item => {
            const statusClass = getStatusBadgeClass(item.status);
            const detailUrl = `/client/pesanan/${item.id}`;
            
            html += `
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg item-row">
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800">${item.kode_pesanan}</p>
                        <p class="text-sm text-gray-600">${item.layanan.nama_layanan}</p>
                        <p class="text-sm font-medium text-gray-900 mt-1">${formatRupiah(item.total_harga)}</p>
                    </div>
                    <div class="text-right">
                        <span class="${statusClass} text-xs">${item.status}</span>
                        <a href="${detailUrl}" class="block text-sm text-blue-600 hover:underline mt-1">Detail</a>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
    }

    // Update invoice pending
    function updateInvoicePending(invoices) {
        const container = document.getElementById('invoice-pending-container');
        
        if (invoices.length === 0) {
            container.innerHTML = `
                <div class="text-center py-8">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-gray-500">Tidak ada invoice pending</p>
                </div>
            `;
            return;
        }

        let html = '';
        invoices.forEach(invoice => {
            const pembayaranUrl = `/client/pembayaran/invoice/${invoice.id}`;
            
            // Cek status pembayaran
            const hasPendingPayment = invoice.pembayaran && invoice.pembayaran.some(p => 
                p.status === 'Menunggu Verifikasi' || p.status === 'Terverifikasi'
            );
            const latestPayment = invoice.pembayaran && invoice.pembayaran.length > 0 ? 
                invoice.pembayaran[invoice.pembayaran.length - 1] : null;
            const hasRejectedPayment = latestPayment && latestPayment.status === 'Ditolak';

            let actionButton = '';
            if (hasPendingPayment) {
                actionButton = '<span class="badge-warning text-xs">Menunggu Verifikasi</span>';
            } else if (hasRejectedPayment) {
                actionButton = `
                    <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full font-medium">Pembayaran Ditolak</span>
                    <a href="${pembayaranUrl}" class="btn-primary text-sm">Bayar Ulang</a>
                `;
            } else {
                actionButton = `
                    <span class="badge-warning text-xs">${invoice.status}</span>
                    <a href="${pembayaranUrl}" class="btn-primary text-sm">Bayar</a>
                `;
            }

            html += `
                <div class="flex items-center justify-between p-4 bg-yellow-50 border border-yellow-200 rounded-lg item-row">
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800">${invoice.nomor_invoice}</p>
                        <p class="text-sm text-gray-600">${invoice.pesanan.layanan.nama_layanan}</p>
                        <p class="text-xs text-gray-500 mt-1">${invoice.tipe} - Jatuh tempo: ${formatTanggal(invoice.tanggal_jatuh_tempo)}</p>
                        <p class="text-lg font-bold text-gray-900 mt-2">${formatRupiah(invoice.jumlah)}</p>
                    </div>
                    <div class="text-right flex flex-col items-end gap-2">
                        ${actionButton}
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
    }

    // Setup realtime updates dengan polling
    function setupRealtimeUpdates() {
        // Fetch data setiap 5 detik
        setInterval(async () => {
            try {
                const response = await fetch('{{ route("client.dashboard.realtime") }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    updateDashboard(data);
                }
            } catch (error) {
                console.error('Error fetching realtime data:', error);
            }
        }, 5000); // Update setiap 5 detik
    }
</script>
@endsection