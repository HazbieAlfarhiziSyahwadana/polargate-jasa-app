@extends('layouts.admin')

@section('title', 'Kelola Invoice')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Kelola Invoice</h1>
    <p class="text-gray-600">Manajemen invoice pembayaran</p>
</div>

<!-- Filter -->
<div class="card mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label for="tipe" class="block text-sm font-medium text-gray-700 mb-2">Tipe Invoice</label>
            <select name="tipe" id="tipe" class="input-field" onchange="this.form.submit()">
                <option value="">Semua Tipe</option>
                <option value="DP" {{ request('tipe') == 'DP' ? 'selected' : '' }}>DP</option>
                <option value="Pelunasan" {{ request('tipe') == 'Pelunasan' ? 'selected' : '' }}>Pelunasan</option>
            </select>
        </div>
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select name="status" id="status" class="input-field" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="Belum Dibayar" {{ request('status') == 'Belum Dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                <option value="Menunggu Verifikasi" {{ request('status') == 'Menunggu Verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                <option value="Lunas" {{ request('status') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
            </select>
        </div>
        <div class="flex items-end">
            <a href="{{ route('admin.invoice.index') }}" class="btn-secondary">Reset</a>
        </div>
    </form>
</div>

<!-- Table -->
<div class="card">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Invoice</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pesanan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jatuh Tempo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($invoices as $invoice)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-gray-900">{{ $invoice->nomor_invoice }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $invoice->pesanan->client->name }}</div>
                        <div class="text-sm text-gray-500">{{ $invoice->pesanan->client->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $invoice->pesanan->kode_pesanan }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="badge-info text-xs">{{ $invoice->tipe }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                        Rp {{ number_format($invoice->jumlah, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($invoice->status == 'Lunas')
                        <span class="badge-success">Lunas</span>
                        @elseif($invoice->status == 'Menunggu Verifikasi')
                        <span class="badge-warning">Menunggu Verifikasi</span>
                        @else
                        <span class="badge-danger">Belum Dibayar</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $invoice->tanggal_jatuh_tempo->format('d/m/Y') }}
                        @if($invoice->is_jatuh_tempo)
                        <span class="text-red-600 text-xs block">● Jatuh Tempo</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <a href="{{ route('admin.invoice.show', $invoice) }}" class="text-blue-600 hover:text-blue-900">Detail</a>
                        <a href="{{ route('admin.invoice.print', $invoice) }}" target="_blank" class="text-green-600 hover:text-green-900">Print</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">Tidak ada invoice</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="px-6 py-4">
        {{ $invoices->links() }}
    </div>
</div>
@endsection