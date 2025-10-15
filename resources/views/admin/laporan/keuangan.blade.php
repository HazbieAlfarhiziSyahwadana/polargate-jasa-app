@extends('layouts.admin')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Laporan Keuangan</h1>
    <p class="text-gray-600">Laporan pendapatan dan transaksi keuangan</p>
</div>

<div class="card mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
            <select name="bulan" class="input-field">
                <option value="">Semua Bulan</option>
                @for($i = 1; $i <= 12; $i++)
                <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                    {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                </option>
                @endfor
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
            <select name="tahun" class="input-field">
                @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                <option value="{{ $i }}" {{ request('tahun', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="btn-primary mr-2">Filter</button>
            <a href="{{ route('admin.laporan.keuangan') }}" class="btn-secondary">Reset</a>
        </div>
    </form>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="card bg-gradient-to-br from-green-500 to-green-600 text-white">
        <p class="text-green-100 text-sm">Total Pendapatan</p>
        <p class="text-3xl font-bold mt-2">Rp {{ number_format($pendapatanPerBulan->sum('total'), 0, ',', '.') }}</p>
    </div>
    <div class="card bg-gradient-to-br from-blue-500 to-blue-600 text-white">
        <p class="text-blue-100 text-sm">Total Invoice</p>
        <p class="text-3xl font-bold mt-2">{{ $invoices->count() }}</p>
    </div>
    <div class="card bg-gradient-to-br from-purple-500 to-purple-600 text-white">
        <p class="text-purple-100 text-sm">Invoice Lunas</p>
        <p class="text-3xl font-bold mt-2">{{ $invoices->where('status', 'Lunas')->count() }}</p>
    </div>
</div>

<div class="card">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-gray-800">Daftar Invoice</h2>
        <a href="{{ route('admin.laporan.export-keuangan', request()->all()) }}" class="btn-success text-sm">
            Export Excel
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No Invoice</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($invoices as $invoice)
                <tr>
                    <td class="px-4 py-3">{{ $invoice->nomor_invoice }}</td>
                    <td class="px-4 py-3">{{ $invoice->pesanan->client->name }}</td>
                    <td class="px-4 py-3">{{ $invoice->tipe }}</td>
                    <td class="px-4 py-3">Rp {{ number_format($invoice->jumlah, 0, ',', '.') }}</td>
                    <td class="px-4 py-3">
                        @if($invoice->status == 'Lunas')
                        <span class="badge-success">Lunas</span>
                        @else
                        <span class="badge-danger">Belum Lunas</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">{{ $invoice->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection