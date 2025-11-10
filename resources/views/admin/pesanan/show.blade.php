@extends('layouts.admin')

@section('title', 'Detail Pesanan')

@section('content')
<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
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
    .page-load .delay-500 { animation-delay: 0.5s; }

    .card {
        transition: box-shadow 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .btn-primary, .btn-secondary, .btn-info, .btn-danger, .btn-sm {
        transition: all 0.2s ease;
    }

    .btn-primary:hover, .btn-secondary:hover, .btn-info:hover, .btn-danger:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.12);
    }

    .btn-primary:active, .btn-secondary:active {
        transform: translateY(0);
    }

    .input-field, select {
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .input-field:focus, select:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .file-item {
        transition: all 0.2s ease;
    }

    .file-item:hover {
        background-color: #f3f4f6;
        transform: translateX(4px);
    }

    .progress-bar {
        transition: width 1s ease-out;
    }

    @media (max-width: 1024px) {
        .grid-cols-1.lg\\:grid-cols-3 {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="mb-6 animate-fade">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Detail Pesanan {{ $pesanan->kode_pesanan }}</h1>
            <p class="text-gray-600 mt-1">Informasi lengkap pesanan</p>
        </div>
        <a href="{{ route('admin.pesanan.index') }}" class="btn-secondary w-full sm:w-auto text-center inline-flex items-center justify-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">
    <!-- Informasi Pesanan -->
    <div class="lg:col-span-2 space-y-4 lg:space-y-6">
        <!-- Status & Info Umum -->
        <div class="card animate-slide">
            <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-4">Informasi Pesanan</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Kode Pesanan</p>
                    <p class="font-semibold text-gray-900">{{ $pesanan->kode_pesanan }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tanggal Pesanan</p>
                    <p class="font-semibold text-gray-900">{{ $pesanan->created_at->format('d M Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    @php
                        $statusClass = 'badge-info';
                        if(in_array($pesanan->status, ['Selesai'])) $statusClass = 'badge-success';
                        if(in_array($pesanan->status, ['Menunggu Pembayaran DP', 'Menunggu Pelunasan'])) $statusClass = 'badge-warning';
                        if(in_array($pesanan->status, ['Dibatalkan'])) $statusClass = 'badge-danger';
                    @endphp
                    <span class="{{ $statusClass }}">{{ $pesanan->status }}</span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Harga</p>
                    <p class="font-semibold text-gray-900 text-lg">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Update Status -->
            <form action="{{ route('admin.pesanan.update-status', $pesanan) }}" method="POST" class="mt-6">
                @csrf
                @method('PATCH')
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Update Status Pesanan</label>
                <div class="flex flex-col sm:flex-row gap-2">
                    @php
                        $statusOptions = [
                            'Menunggu Pembayaran DP',
                            'DP Dibayar - Menunggu Verifikasi',
                            'Sedang Diproses',
                            'Preview Siap',
                            'Revisi Diminta',
                            'Menunggu Pelunasan',
                            'Pelunasan Dibayar - Menunggu Verifikasi',
                            'Selesai',
                            'Dibatalkan',
                        ];
                    @endphp
                    <select name="status" id="status" class="input-field flex-1">
                        @foreach($statusOptions as $statusOption)
                            <option value="{{ $statusOption }}" {{ $pesanan->status == $statusOption ? 'selected' : '' }}>
                                {{ $statusOption }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn-primary w-full sm:w-auto">Update</button>
                </div>
            </form>
        </div>

        <!-- Detail Layanan -->
        <div class="card animate-slide delay-100">
            <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-4">Detail Layanan</h2>
            
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Layanan</p>
                    <p class="font-semibold text-gray-900">{{ $pesanan->layanan->nama }}</p>
                    <p class="text-sm text-gray-600">{{ $pesanan->layanan->kategori }}</p>
                </div>
                
                @if($pesanan->paket)
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-500">Paket</p>
                    <p class="font-semibold text-gray-900">{{ $pesanan->paket->nama }}</p>
                    <p class="text-sm text-gray-600">Rp {{ number_format($pesanan->paket->harga, 0, ',', '.') }}</p>
                    <div class="mt-2">
                        <p class="text-xs text-gray-500">Fitur:</p>
                        <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                            @if(is_array($pesanan->paket->fitur))
                                @foreach($pesanan->paket->fitur as $fitur)
                                    <li>{{ $fitur }}</li>
                                @endforeach
                            @elseif(is_string($pesanan->paket->fitur))
                                @php
                                    $fiturArray = json_decode($pesanan->paket->fitur, true);
                                @endphp
                                @if(is_array($fiturArray))
                                    @foreach($fiturArray as $fitur)
                                        <li>{{ $fitur }}</li>
                                    @endforeach
                                @else
                                    <li>{{ $pesanan->paket->fitur }}</li>
                                @endif
                            @endif
                        </ul>
                    </div>
                </div>
                @endif

                @if($pesanan->addons->count() > 0)
                <div>
                    <p class="text-sm text-gray-500 mb-2">Add-ons</p>
                    <div class="space-y-2">
                        @foreach($pesanan->addons as $addon)
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center bg-gray-50 p-3 rounded file-item gap-2">
                            <div>
                                <p class="font-medium text-gray-900">{{ $addon->nama }}</p>
                                <p class="text-xs text-gray-600">{{ $addon->deskripsi }}</p>
                            </div>
                            <p class="font-semibold text-gray-900 text-sm sm:text-base">Rp {{ number_format($addon->pivot->harga, 0, ',', '.') }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

         <!-- Brief & Referensi -->
        <div class="card animate-slide delay-200">
            <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-4">Detail Pesanan & Referensi</h2>
            
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500 mb-2">Detail Pesanan dari Client</p>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        {{-- FIX: Gunakan detail_pesanan bukan brief --}}
                        <p class="text-gray-700 whitespace-pre-line text-sm">{{ $pesanan->detail_pesanan ?? 'Tidak ada brief' }}</p>
                    </div>
                </div>

                @if($pesanan->file_pendukung)
                    @php
                        $filePendukung = is_string($pesanan->file_pendukung) 
                            ? json_decode($pesanan->file_pendukung, true) 
                            : $pesanan->file_pendukung;
                    @endphp
                    @if(is_array($filePendukung) && count($filePendukung) > 0)
                    <div>
                        <p class="text-sm text-gray-500 mb-2">File Pendukung</p>
                        <div class="space-y-2">
                            @foreach($filePendukung as $file)
                            @php
                                $fileUrl = asset('uploads/pesanan/' . ltrim($file, '/'));
                            @endphp
                            <a href="{{ $fileUrl }}" target="_blank" class="flex items-center bg-gray-50 p-3 rounded hover:bg-gray-100 file-item">
                                <svg class="w-5 h-5 text-gray-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="text-sm text-gray-700 truncate">{{ basename($file) }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                @endif
            </div>
        </div>

        <!-- Upload Preview -->
        @if(in_array($pesanan->status, ['Sedang Diproses', 'Revisi Diminta', 'Preview Siap', 'Menunggu Pelunasan']))
        <div class="card animate-slide delay-300">
            <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-4">
                {{ $pesanan->preview_link ? 'Update Link Preview' : 'Upload Link Preview' }}
            </h2>
            
            <form action="{{ route('admin.pesanan.upload-preview', $pesanan) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="preview_link" class="block text-sm font-medium text-gray-700 mb-2">Link Preview</label>
                        <input type="url" name="preview_link" id="preview_link" class="input-field w-full" placeholder="https://drive.google.com/file/d/..." value="{{ old('preview_link', $pesanan->preview_link) }}" required>
                        <p class="text-xs text-gray-500 mt-1">Upload file preview ke Google Drive/Dropbox dan masukkan link sharing-nya (pastikan akses diset ke "Anyone with the link").</p>
                    </div>
                    <div>
                        <label for="duration_hours" class="block text-sm font-medium text-gray-700 mb-2">Masa Aktif Link</label>
                        <select name="duration_hours" id="duration_hours" class="input-field w-full">
                            <option value="6">6 jam</option>
                            <option value="12">12 jam</option>
                            <option value="24" selected>24 jam</option>
                            <option value="48">48 jam</option>
                            <option value="72">72 jam</option>
                            <option value="168">7 hari</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Client hanya bisa mengakses preview dalam durasi yang dipilih.</p>
                    </div>
                    <button type="submit" class="btn-primary w-full sm:w-auto">
                        {{ $pesanan->preview_link ? '🔄 Update Link Preview' : '📤 Upload Link Preview' }}
                    </button>
                </div>
            </form>
        </div>
        @endif

        <!-- Preview Link -->
        @if($pesanan->preview_link)
        <div class="card animate-slide delay-300">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-3">
                <h2 class="text-lg sm:text-xl font-bold text-gray-800">Preview</h2>
                <div class="flex flex-wrap gap-2">
                    <form action="{{ route('admin.pesanan.extend-preview', $pesanan) }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        @method('PATCH')
                        <select name="duration_hours" class="input-field text-sm py-1 px-2">
                            <option value="6">+6 jam</option>
                            <option value="12">+12 jam</option>
                            <option value="24" selected>+24 jam</option>
                            <option value="48">+48 jam</option>
                            <option value="72">+72 jam</option>
                            <option value="168">+7 hari</option>
                        </select>
                        <button type="submit" class="btn-sm btn-info whitespace-nowrap">
                            Perpanjang
                        </button>
                    </form>
                    <form action="{{ route('admin.pesanan.delete-preview', $pesanan) }}" method="POST" onsubmit="return confirm('Hapus preview?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-sm btn-danger whitespace-nowrap">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg">
                <p class="text-sm text-gray-700 mb-2 font-medium">Link Preview:</p>
                <a href="{{ $pesanan->preview_link }}" target="_blank" class="text-blue-600 hover:underline break-all text-sm">{{ $pesanan->preview_link }}</a>
                @php
                    $previewExpiry = $pesanan->preview_expired_at ? \Carbon\Carbon::parse($pesanan->preview_expired_at) : null;
                    $previewStillActive = $previewExpiry && $previewExpiry->isFuture();
                @endphp
                <p class="text-xs mt-2">
                    @if($previewStillActive)
                    <span class="text-green-600 font-medium">✓ Aktif hingga {{ $previewExpiry->format('d M Y H:i') }}</span>
                    @else
                    <span class="text-red-600 font-medium">⚠ Link sudah kadaluarsa. Perpanjang untuk mengaktifkan kembali.</span>
                    @endif
                </p>
            </div>
        </div>
        @endif

        <!-- Upload File Final -->
        @if(in_array($pesanan->status, ['Menunggu Pelunasan', 'Pelunasan Dibayar - Menunggu Verifikasi', 'Selesai']))
        <div class="card animate-slide delay-400">
            <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-4">
                {{ $pesanan->file_final ? 'Perbarui File Final' : 'Upload File Final' }}
            </h2>
            
            <form action="{{ route('admin.pesanan.upload-final', $pesanan) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div>
                    <label for="file_final" class="block text-sm font-medium text-gray-700 mb-2">File Final (Bisa multiple)</label>
                    <input type="file" name="file_final[]" id="file_final" class="input-field w-full" multiple required>
                    <p class="text-xs text-gray-500 mt-1">
                        Max 100MB per file. Mengunggah ulang akan menggantikan file final yang ada.
                    </p>
                </div>
                <button type="submit" class="btn-primary mt-4 w-full sm:w-auto">
                    {{ $pesanan->file_final ? 'Perbarui File Final' : 'Upload File Final' }}
                </button>
            </form>
        </div>
        @endif

        <!-- File Final -->
        @if($pesanan->file_final)
            @php
                $fileFinal = $pesanan->file_final;
                if (is_string($fileFinal)) {
                    $decoded = json_decode($fileFinal, true);
                    $fileFinal = is_array($decoded) ? $decoded : [$fileFinal];
                }
                $fileFinal = array_filter(is_array($fileFinal) ? $fileFinal : (array) $fileFinal);
            @endphp
            @if(count($fileFinal) > 0)
            <div class="card animate-slide delay-400">
                <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-4">File Final</h2>
                
                <div class="space-y-2">
                    @foreach($fileFinal as $file)
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between bg-green-50 p-3 rounded file-item gap-2">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-700 break-all">{{ basename($file) }}</span>
                                <span class="text-xs text-gray-500 uppercase">{{ strtoupper(pathinfo($file, PATHINFO_EXTENSION)) }}</span>
                            </div>
                        </div>
                        <a href="{{ asset('uploads/final/' . ltrim($file, '/')) }}" download class="text-blue-600 hover:text-blue-800 text-sm whitespace-nowrap">Download</a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        @endif

        <!-- Revisi -->
        @if($pesanan->revisi->count() > 0)
        <div class="card animate-slide delay-500">
            <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-4">Riwayat Revisi</h2>
            
            <div class="space-y-4">
                @foreach($pesanan->revisi as $revisi)
                <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2 gap-2">
                        <p class="font-semibold text-gray-800">Revisi ke-{{ $revisi->revisi_ke }}</p>
                        <span class="{{ $revisi->status_badge }} text-xs">{{ $revisi->status }}</span>
                    </div>
                    <p class="text-sm text-gray-700 mb-2">{{ $revisi->catatan_revisi }}</p>
                    @if($revisi->hasFiles())
                    <div class="mt-2">
                        <p class="text-xs text-gray-500 mb-1">File Referensi ({{ $revisi->file_count }}):</p>
                        @foreach($revisi->file_referensi as $file)
                        <a href="{{ asset('storage/' . $file) }}" target="_blank" class="text-xs text-blue-600 hover:underline block truncate">{{ basename($file) }}</a>
                        @endforeach
                    </div>
                    @endif
                    <p class="text-xs text-gray-500 mt-2">{{ $revisi->formatted_created_at }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-4 lg:space-y-6">
        <!-- Info Client -->
        <div class="card animate-slide delay-100">
            <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-4">Informasi Client</h2>
            
            <div class="flex items-start mb-4">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center mr-3 flex-shrink-0">
                    <span class="text-xl font-bold text-white">{{ substr($pesanan->client->name, 0, 1) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-900 truncate">{{ $pesanan->client->name }}</p>
                    <p class="text-sm text-gray-500 truncate">{{ $pesanan->client->email }}</p>
                </div>
            </div>

            <div class="space-y-2 text-sm bg-gray-50 p-3 rounded-lg">
                @if($pesanan->client->no_hp)
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Telepon:</span>
                    <span class="text-gray-900 font-medium">{{ $pesanan->client->no_hp }}</span>
                </div>
                @endif
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Total Pesanan:</span>
                    <span class="text-gray-900 font-medium">{{ $pesanan->client->pesanan->count() }} pesanan</span>
                </div>
            </div>

            <a href="{{ route('admin.client.show', $pesanan->client) }}" class="btn-secondary w-full mt-4 text-center block">Lihat Profil Client</a>
        </div>

        <!-- Invoice -->
        <div class="card animate-slide delay-200">
            <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-4">Invoice & Pembayaran</h2>
            
            @php
                $invoiceDP = $pesanan->invoices->where('tipe', 'DP')->first();
                $invoicePelunasan = $pesanan->invoices->where('tipe', 'Pelunasan')->first();
            @endphp

            <!-- Invoice DP -->
            <div class="mb-4">
                <p class="text-sm font-medium text-gray-700 mb-2">Invoice DP (50%)</p>
                @if($invoiceDP)
                <div class="bg-gray-50 p-3 rounded-lg">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs text-gray-600 truncate">{{ $invoiceDP->nomor_invoice }}</span>
                        @if($invoiceDP->status == 'Lunas')
                        <span class="badge-success text-xs">Lunas</span>
                        @elseif($invoiceDP->status == 'Menunggu Verifikasi')
                        <span class="badge-warning text-xs">Pending</span>
                        @else
                        <span class="badge-danger text-xs">Belum Dibayar</span>
                        @endif
                    </div>
                    <p class="font-semibold text-gray-900">Rp {{ number_format($invoiceDP->jumlah, 0, ',', '.') }}</p>
                    <a href="{{ route('admin.invoice.show', $invoiceDP) }}" class="text-xs text-blue-600 hover:underline">Lihat Invoice</a>
                </div>
                @else
                <form action="{{ route('admin.invoice.create-dp', $pesanan) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-primary w-full text-sm">Terbitkan Invoice DP</button>
                </form>
                @endif
            </div>

            <!-- Invoice Pelunasan -->
            <div>
                <p class="text-sm font-medium text-gray-700 mb-2">Invoice Pelunasan (50%)</p>
                @if($invoicePelunasan)
                <div class="bg-gray-50 p-3 rounded-lg">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs text-gray-600 truncate">{{ $invoicePelunasan->nomor_invoice }}</span>
                        @if($invoicePelunasan->status == 'Lunas')
                        <span class="badge-success text-xs">Lunas</span>
                        @elseif($invoicePelunasan->status == 'Menunggu Verifikasi')
                        <span class="badge-warning text-xs">Pending</span>
                        @else
                        <span class="badge-danger text-xs">Belum Dibayar</span>
                        @endif
                    </div>
                    <p class="font-semibold text-gray-900">Rp {{ number_format($invoicePelunasan->jumlah, 0, ',', '.') }}</p>
                    <a href="{{ route('admin.invoice.show', $invoicePelunasan) }}" class="text-xs text-blue-600 hover:underline">Lihat Invoice</a>
                </div>
                @elseif($invoiceDP && $invoiceDP->status == 'Lunas' && in_array($pesanan->status, ['Preview Siap', 'Menunggu Pelunasan']))
                <form action="{{ route('admin.invoice.create-pelunasan', $pesanan) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-primary w-full text-sm">Terbitkan Invoice Pelunasan</button>
                </form>
                @else
                <p class="text-xs text-gray-500 text-center py-2 bg-gray-50 rounded-lg">DP harus lunas terlebih dahulu</p>
                @endif
            </div>
        </div>

        <!-- Quick Stats -->
        @if($pesanan->paket)
        <div class="card animate-slide delay-300">
            <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-4">Progress</h2>
            
            <div class="space-y-4">
                <div class="bg-gray-50 p-3 rounded-lg">
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Durasi Pengerjaan</span>
                        <span class="font-semibold text-gray-900">{{ $pesanan->paket->durasi_pengerjaan }} hari</span>
                    </div>
                </div>
                @if(isset($pesanan->paket->jumlah_revisi))
                <div class="bg-gray-50 p-3 rounded-lg">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-600">Jumlah Revisi</span>
                        <span class="font-semibold text-gray-900">{{ $pesanan->paket->jumlah_revisi }}x</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                        @php
                            $revisiTerpakai = $pesanan->revisi->count();
                            $revisiMax = $pesanan->paket->jumlah_revisi;
                            $revisiProgress = $revisiMax > 0 ? (($revisiMax - $revisiTerpakai) / $revisiMax) * 100 : 0;
                        @endphp
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2 rounded-full progress-bar" style="width: {{ number_format($revisiProgress, 0) }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Tersisa: {{ max(0, $revisiMax - $revisiTerpakai) }}x revisi</p>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isFirstLoad = !sessionStorage.getItem('pesanan_detail_loaded');
        
        if (isFirstLoad) {
            document.body.classList.add('page-load');
            sessionStorage.setItem('pesanan_detail_loaded', 'true');
            
            // Animate progress bar on first load
            setTimeout(() => {
                const progressBar = document.querySelector('.progress-bar');
                if (progressBar) {
                    const finalWidth = progressBar.style.width;
                    progressBar.style.width = '0%';
                    setTimeout(() => {
                        progressBar.style.width = finalWidth;
                    }, 100);
                }
            }, 500);
            
            setTimeout(() => {
                document.body.classList.remove('page-load');
            }, 1500);
        }
    });

    window.addEventListener('beforeunload', function(e) {
        if (e.target.activeElement.tagName === 'A' && 
            e.target.activeElement.getAttribute('href') !== '#') {
            sessionStorage.removeItem('pesanan_detail_loaded');
        }
    });
</script>
@endsection