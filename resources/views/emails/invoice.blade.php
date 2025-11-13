<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Invoice {{ $invoice->nomor_invoice }}</title>
    <!--[if mso]>
    <style type="text/css">
        body, table, td {font-family: Arial, sans-serif !important;}
    </style>
    <![endif]-->
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background-color: #f5f5f5;">
    <!-- Wrapper Table -->
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f5f5f5;">
        <tr>
            <td style="padding: 20px 0;">
                <!-- Main Container -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="margin: 0 auto; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #1d4ed8 0%, #3b82f6 100%); padding: 30px; text-align: center; border-radius: 12px 12px 0 0;">
                            <h1 style="margin: 0; font-size: 24px; font-weight: 600; color: #ffffff;">
                                🧾 Invoice {{ $invoice->nomor_invoice }}
                            </h1>
                            <p style="margin: 8px 0 0; font-size: 14px; color: #ffffff; opacity: 0.9;">
                                PT Polargate Indonesia Kreasi
                            </p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 30px;">
                            <!-- Greeting -->
                            <p style="margin: 0 0 20px; font-size: 14px; line-height: 1.6; color: #333333;">
                                Halo <strong>{{ $client->name }}</strong>,
                            </p>

                            <!-- Status Alert -->
                            @if($type === 'dp_verified')
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 20px 0;">
                                <tr>
                                    <td style="background-color: #d1fae5; border-left: 4px solid #10b981; padding: 16px; border-radius: 8px;">
                                        <strong style="display: block; margin-bottom: 8px; font-size: 14px; color: #065f46;">
                                            ✅ Pembayaran DP Berhasil Diverifikasi!
                                        </strong>
                                        <span style="font-size: 13px; color: #065f46; line-height: 1.5;">
                                            Pembayaran Down Payment (DP) Anda telah kami terima dan verifikasi. Pesanan Anda akan segera kami proses.
                                        </span>
                                    </td>
                                </tr>
                            </table>
                            @elseif($type === 'pelunasan_verified')
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 20px 0;">
                                <tr>
                                    <td style="background-color: #d1fae5; border-left: 4px solid #10b981; padding: 16px; border-radius: 8px;">
                                        <strong style="display: block; margin-bottom: 8px; font-size: 14px; color: #065f46;">
                                            ✅ Pembayaran Pelunasan Berhasil Diverifikasi!
                                        </strong>
                                        <span style="font-size: 13px; color: #065f46; line-height: 1.5;">
                                            Pembayaran pelunasan Anda telah kami terima dan verifikasi. Terima kasih atas kepercayaan Anda menggunakan layanan kami.
                                        </span>
                                    </td>
                                </tr>
                            </table>
                            @elseif($type === 'rejected')
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 20px 0;">
                                <tr>
                                    <td style="background-color: #fee2e2; border-left: 4px solid #ef4444; padding: 16px; border-radius: 8px;">
                                        <strong style="display: block; margin-bottom: 8px; font-size: 14px; color: #991b1b;">
                                            ⚠️ Pembayaran Ditolak
                                        </strong>
                                        <span style="font-size: 13px; color: #991b1b; line-height: 1.5;">
                                            Mohon maaf, pembayaran Anda tidak dapat kami verifikasi. Silakan periksa detail penolakan di bawah ini.
                                        </span>
                                    </td>
                                </tr>
                            </table>
                            @endif

                            <!-- Invoice Details Box -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 20px 0; background-color: #f9fafb; border-left: 4px solid #3b82f6; border-radius: 4px;">
                                <tr>
                                    <td style="padding: 16px;">
                                        <h3 style="margin: 0 0 12px; font-size: 14px; color: #1f2937; text-transform: uppercase; letter-spacing: 0.5px;">
                                            📋 Detail Invoice
                                        </h3>
                                        
                                        <!-- Info Row 1 -->
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="border-bottom: 1px solid #e5e7eb;">
                                            <tr>
                                                <td style="padding: 8px 0; font-size: 13px; color: #6b7280;">Nomor Invoice</td>
                                                <td style="padding: 8px 0; font-size: 13px; color: #111827; font-weight: 600; text-align: right;">{{ $invoice->nomor_invoice }}</td>
                                            </tr>
                                        </table>

                                        <!-- Info Row 2 -->
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="border-bottom: 1px solid #e5e7eb;">
                                            <tr>
                                                <td style="padding: 8px 0; font-size: 13px; color: #6b7280;">Kode Pesanan</td>
                                                <td style="padding: 8px 0; font-size: 13px; color: #111827; font-weight: 600; text-align: right;">{{ $invoice->pesanan->kode_pesanan }}</td>
                                            </tr>
                                        </table>

                                        <!-- Info Row 3 -->
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="border-bottom: 1px solid #e5e7eb;">
                                            <tr>
                                                <td style="padding: 8px 0; font-size: 13px; color: #6b7280;">Layanan</td>
                                                <td style="padding: 8px 0; font-size: 13px; color: #111827; font-weight: 600; text-align: right;">{{ $invoice->pesanan->layanan->nama_layanan ?? $invoice->pesanan->layanan->nama }}</td>
                                            </tr>
                                        </table>

                                        <!-- Info Row 4 -->
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="border-bottom: 1px solid #e5e7eb;">
                                            <tr>
                                                <td style="padding: 8px 0; font-size: 13px; color: #6b7280;">Tipe Pembayaran</td>
                                                <td style="padding: 8px 0; font-size: 13px; color: #111827; font-weight: 600; text-align: right;">{{ $invoice->tipe }}</td>
                                            </tr>
                                        </table>

                                        <!-- Info Row 5 -->
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="border-bottom: 1px solid #e5e7eb;">
                                            <tr>
                                                <td style="padding: 8px 0; font-size: 13px; color: #6b7280;">Jumlah</td>
                                                <td style="padding: 8px 0; font-size: 13px; color: #111827; font-weight: 600; text-align: right;">Rp {{ number_format($invoice->jumlah, 0, ',', '.') }}</td>
                                            </tr>
                                        </table>

                                        <!-- Info Row 6 -->
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td style="padding: 8px 0; font-size: 13px; color: #6b7280;">Status</td>
                                                <td style="padding: 8px 0; text-align: right;">
                                                    <span style="display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; background-color: {{ $invoice->status == 'Lunas' ? '#d1fae5' : '#fee2e2' }}; color: {{ $invoice->status == 'Lunas' ? '#065f46' : '#991b1b' }};">
                                                        {{ $invoice->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Rejection Reason (if rejected) -->
                            @if($type === 'rejected' && $invoice->alasan_penolakan)
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 20px 0;">
                                <tr>
                                    <td style="background-color: #fee2e2; border-left: 4px solid #ef4444; padding: 16px; border-radius: 8px;">
                                        <strong style="display: block; margin-bottom: 8px; font-size: 14px; color: #991b1b;">
                                            Alasan Penolakan:
                                        </strong>
                                        <span style="font-size: 13px; color: #991b1b; line-height: 1.5;">
                                            {{ $invoice->alasan_penolakan }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                            <p style="margin: 16px 0; font-size: 13px; line-height: 1.6; color: #6b7280;">
                                Silakan upload ulang bukti pembayaran yang benar melalui portal client kami.
                            </p>
                            @endif

                            <!-- Admin Notes (if exists) -->
                            @if($pembayaran->catatan_verifikasi)
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 20px 0; background-color: #f9fafb; border-left: 4px solid #3b82f6; border-radius: 4px;">
                                <tr>
                                    <td style="padding: 16px;">
                                        <h3 style="margin: 0 0 12px; font-size: 14px; color: #1f2937; text-transform: uppercase; letter-spacing: 0.5px;">
                                            📝 Catatan dari Admin
                                        </h3>
                                        <p style="margin: 0; font-size: 13px; color: #4b5563; line-height: 1.5;">
                                            {{ $pembayaran->catatan_verifikasi }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            @endif

                            <!-- Divider -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 24px 0;">
                                <tr>
                                    <td style="height: 1px; background-color: #e5e7eb;"></td>
                                </tr>
                            </table>

                            <!-- Button: View Invoice -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 20px 0;">
                                <tr>
                                    <td style="text-align: center;">
                                        <a href="{{ route('client.invoice.index') }}" style="display: inline-block; padding: 12px 28px; background: linear-gradient(135deg, #1d4ed8 0%, #3b82f6 100%); color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 14px;">
                                            Lihat Detail Invoice
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Button: Re-upload Payment (if rejected) -->
                            @if($type === 'rejected')
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 10px 0;">
                                <tr>
                                    <td style="text-align: center;">
                                        <a href="{{ route('client.pembayaran.invoice', $invoice) }}" style="display: inline-block; padding: 12px 28px; background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%); color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 14px;">
                                            Upload Bukti Pembayaran Ulang
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            @endif

                            <!-- Footer Note -->
                            <p style="margin: 24px 0 0; font-size: 13px; line-height: 1.6; color: #6b7280; text-align: center;">
                                Invoice lengkap terlampir dalam email ini dalam format PDF.<br>
                                Anda juga dapat mengunduh invoice melalui portal client kami.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 24px 30px; text-align: center; border-top: 1px solid #e5e7eb; border-radius: 0 0 12px 12px;">
                            <p style="margin: 0 0 8px; font-size: 14px; font-weight: 600; color: #1f2937;">
                                PT Polargate Indonesia Kreasi
                            </p>
                            <p style="margin: 0 0 16px; font-size: 12px; color: #6b7280;">
                                Email: info@polargate.com | Telepon: (021) 1234-5678
                            </p>
                            <p style="margin: 0; font-size: 11px; color: #9ca3af; line-height: 1.5;">
                                Email ini dikirim secara otomatis. Mohon tidak membalas email ini.<br>
                                Untuk pertanyaan, silakan hubungi customer service kami.
                            </p>
                        </td>
                    </tr>

                </table>
                <!-- End Main Container -->
            </td>
        </tr>
    </table>
    <!-- End Wrapper Table -->
</body>
</html>