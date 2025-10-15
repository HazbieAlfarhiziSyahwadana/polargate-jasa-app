<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->nomor_invoice }}</title>
    <style>
        @page { margin: 25mm 20mm; }
        * { box-sizing: border-box; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #111827;
            font-size: 12px;
            margin: 0;
        }
        .invoice-wrapper { width: 100%; }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .company-name {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 6px;
        }
        .company-meta {
            color: #4b5563;
            line-height: 1.5;
        }
        .invoice-label {
            font-size: 26px;
            font-weight: 700;
            color: #1d4ed8;
            text-transform: uppercase;
        }
        .invoice-number {
            color: #6b7280;
            font-size: 12px;
            margin-top: 6px;
        }
        .section { margin-bottom: 24px; }
        .section-title {
            font-size: 13px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .info-grid {
            width: 100%;
            border-collapse: collapse;
        }
        .info-grid td {
            vertical-align: top;
            padding: 0;
            font-size: 12px;
            color: #111827;
        }
        .info-grid .label {
            color: #6b7280;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 4px;
        }
        .info-grid .value {
            font-weight: 600;
            color: #111827;
            margin-bottom: 10px;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
            color: #ffffff;
        }
        .badge-success { background-color: #16a34a; }
        .badge-warning { background-color: #f59e0b; }
        .badge-danger { background-color: #dc2626; }
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #f9fafb;
            border-radius: 10px;
            overflow: hidden;
        }
        .detail-table td {
            padding: 10px 14px;
            font-size: 12px;
            color: #111827;
        }
        .detail-table tr + tr td {
            border-top: 1px solid #e5e7eb;
        }
        .detail-label {
            color: #6b7280;
            width: 35%;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            font-size: 11px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }
        .items-table th {
            background-color: #f3f4f6;
            color: #0f172a;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 10px 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        .items-table td {
            padding: 12px;
            font-size: 12px;
            color: #111827;
            border-bottom: 1px solid #e5e7eb;
        }
        .text-right { text-align: right; }
        .total-row td {
            border-bottom: none;
            font-size: 13px;
            font-weight: 700;
        }
        .notes {
            font-size: 11px;
            color: #4b5563;
            line-height: 1.6;
            border-top: 1px solid #e5e7eb;
            padding-top: 12px;
            margin-top: 18px;
        }
        ul {
            margin: 6px 0 0 16px;
            padding: 0;
        }
        ul li { margin-bottom: 4px; }
    </style>
</head>
<body>
    <div class="invoice-wrapper">
        @php
            $company = [
                'name' => 'PT Polargate Indonesia Kreasi',
                'address' => ['Jl. Contoh No. 123', 'Jakarta Selatan, 12345'],
                'phone' => 'Telepon: (021) 1234-5678',
                'email' => 'Email: info@polargate.com',
            ];
            $pesanan = $invoice->pesanan;
            $client = $pesanan?->client;
            $addons = $pesanan?->addons ?? collect();
            $currency = function ($value) {
                return 'Rp ' . number_format((float) $value, 0, ',', '.');
            };
            $status = $invoice->status ?? '-';
            $statusClass = 'badge-warning';
            if ($status === 'Lunas') {
                $statusClass = 'badge-success';
            } elseif (in_array($status, ['Belum Dibayar', 'Ditolak'])) {
                $statusClass = 'badge-danger';
            }
            $invoiceDate = $invoice->created_at ? $invoice->created_at->format('d M Y') : '-';
            $dueDate = $invoice->tanggal_jatuh_tempo ? $invoice->tanggal_jatuh_tempo->format('d M Y') : '-';
        @endphp

        <table class="header-table">
            <tr>
                <td>
                    <div class="company-name">{{ $company['name'] }}</div>
                    <div class="company-meta">
                        @foreach ($company['address'] as $line)
                            {{ $line }}<br>
                        @endforeach
                        {{ $company['phone'] }}<br>
                        {{ $company['email'] }}
                    </div>
                </td>
                <td style="text-align: right;">
                    <div class="invoice-label">Invoice</div>
                    <div class="invoice-number">{{ $invoice->nomor_invoice }}</div>
                </td>
            </tr>
        </table>

        <div class="section">
            <table class="info-grid">
                <tr>
                    <td style="width: 55%;">
                        <div class="label">Tagihan Kepada</div>
                        <div class="value">{{ $client->name ?? '-' }}</div>
                        <div class="company-meta">
                            {{ $client->email ?? '-' }}<br>
                            {{ $client->no_telepon ?? '-' }}<br>
                            {{ $client->alamat ?? '' }}
                        </div>
                    </td>
                    <td style="width: 45%; text-align: right;">
                        <div class="label">Tanggal Invoice</div>
                        <div class="value">{{ $invoiceDate }}</div>

                        <div class="label">Jatuh Tempo</div>
                        <div class="value">{{ $dueDate }}</div>

                        <div class="label">Status</div>
                        <div class="value">
                            <span class="badge {{ $statusClass }}">{{ $status }}</span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">Detail Pesanan</div>
            <table class="detail-table">
                <tr>
                    <td class="detail-label">Kode Pesanan</td>
                    <td>{{ $pesanan->kode_pesanan ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="detail-label">Layanan</td>
                    <td>{{ $pesanan?->layanan?->nama_layanan ?? $pesanan?->layanan?->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="detail-label">Paket</td>
                    <td>{{ $pesanan?->paket?->nama_paket ?? $pesanan?->paket?->nama ?? '-' }}</td>
                </tr>
                @if($addons->count() > 0)
                <tr>
                    <td class="detail-label">Addon</td>
                    <td>
                        @foreach($addons as $addon)
                            @php
                                $addonName = $addon->nama_addon ?? ($addon->nama ?? ($addon->title ?? 'Addon'));
                            @endphp
                            {{ $addonName }} ({{ $currency($addon->pivot->harga ?? $addon->harga ?? 0) }})
                            @if(!$loop->last)
                                <br>
                            @endif
                        @endforeach
                    </td>
                </tr>
                @endif
            </table>
        </div>

        <div class="section">
            <div class="section-title">Ringkasan Pembayaran</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Deskripsi</th>
                        <th class="text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            Pembayaran {{ $invoice->tipe ?? 'Invoice' }}<br>
                            @if($pesanan?->layanan)
                                <span style="color:#6b7280;">
                                    {{ $pesanan->layanan->nama_layanan ?? $pesanan->layanan->nama }}{{ $pesanan?->paket ? ' - ' . ($pesanan->paket->nama_paket ?? $pesanan->paket->nama) : '' }}
                                </span>
                            @endif
                        </td>
                        <td class="text-right">{{ $currency($invoice->jumlah) }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td>Total</td>
                        <td class="text-right">{{ $currency($invoice->jumlah) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="notes">
            <div class="section-title" style="margin-bottom: 6px;">Catatan Pembayaran</div>
            <ul>
                <li>Harap melakukan pembayaran sebelum tanggal jatuh tempo.</li>
                <li>Setelah melakukan pembayaran, silakan upload bukti transfer pada portal client.</li>
                <li>Pembayaran akan diverifikasi maksimal 1x24 jam pada hari kerja.</li>
                <li>Untuk pertanyaan, hubungi customer service kami melalui WhatsApp atau email.</li>
            </ul>
        </div>
    </div>
</body>
</html>
