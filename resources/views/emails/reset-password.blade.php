<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Polargate</title>
    <style>
        /* Email Client Safe Styles */
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f3f4f6;
            line-height: 1.6;
        }
        
        .email-wrapper {
            width: 100%;
            background-color: #f3f4f6;
            padding: 40px 20px;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .email-header {
            padding: 40px;
            text-align: center;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        }
        
        .logo-circle {
            background-color: white;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .email-title {
            color: white;
            margin: 0;
            font-size: 32px;
            font-weight: bold;
        }
        
        .email-subtitle {
            color: rgba(255,255,255,0.9);
            margin: 10px 0 0;
            font-size: 16px;
        }
        
        .email-content {
            background-color: white;
            padding: 40px;
        }
        
        .email-text {
            color: #374151;
            font-size: 16px;
            line-height: 1.6;
            margin: 0 0 20px;
        }
        
        .btn-container {
            text-align: center;
            margin: 30px 0;
        }
        
        .btn-reset {
            display: inline-block;
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            color: white !important;
            text-decoration: none;
            padding: 16px 40px;
            border-radius: 12px;
            font-weight: bold;
            font-size: 16px;
            box-shadow: 0 4px 6px rgba(37, 99, 235, 0.3);
        }
        
        .info-box {
            background-color: #f9fafb;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            margin-top: 30px;
            border-radius: 8px;
        }
        
        .info-text {
            color: #6b7280;
            font-size: 13px;
            margin: 0 0 10px;
        }
        
        .url-text {
            color: #3b82f6;
            font-size: 12px;
            word-break: break-all;
            margin: 0;
        }
        
        .email-footer {
            background-color: #1e293b;
            padding: 30px;
            text-align: center;
        }
        
        .footer-text {
            color: rgba(255,255,255,0.7);
            font-size: 14px;
            margin: 0 0 10px;
        }
        
        .footer-subtext {
            color: rgba(255,255,255,0.5);
            font-size: 12px;
            margin: 0;
        }
        
        /* Responsive */
        @media only screen and (max-width: 600px) {
            .email-wrapper {
                padding: 20px 10px;
            }
            
            .email-header,
            .email-content {
                padding: 30px 20px;
            }
            
            .email-title {
                font-size: 24px;
            }
            
            .btn-reset {
                padding: 14px 30px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="email-wrapper">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" border="0" class="email-container" style="max-width: 600px; background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td class="email-header" style="padding: 40px; text-align: center;">
                            <!-- Logo -->
                            <table width="80" cellpadding="0" cellspacing="0" border="0" align="center" style="margin-bottom: 20px;">
                                <tr>
                                    <td align="center" style="background-color: white; width: 80px; height: 80px; border-radius: 50%; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                                        <span style="font-size: 40px; line-height: 80px;">🔐</span>
                                    </td>
                                </tr>
                            </table>
                            
                            <h1 class="email-title" style="color: white; margin: 0; font-size: 32px; font-weight: bold;">Reset Password</h1>
                            <p class="email-subtitle" style="color: rgba(255,255,255,0.9); margin: 10px 0 0; font-size: 16px;">PT Polargate Indonesia Kreasi</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td class="email-content" style="background-color: white; padding: 40px;">
                            <p class="email-text" style="color: #374151; font-size: 16px; line-height: 1.6; margin: 0 0 20px;">
                                Halo <strong>{{ $user->name ?? 'Pengguna' }}</strong>,
                            </p>
                            <p class="email-text" style="color: #374151; font-size: 16px; line-height: 1.6; margin: 0 0 20px;">
                                Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda di sistem <strong>Polargate</strong>.
                            </p>
                            
                            <!-- Button -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" class="btn-container" style="margin: 30px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $url }}" class="btn-reset" style="display: inline-block; background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%); color: white !important; text-decoration: none; padding: 16px 40px; border-radius: 12px; font-weight: bold; font-size: 16px; box-shadow: 0 4px 6px rgba(37, 99, 235, 0.3);">
                                            Reset Password Sekarang
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            
                            <p class="email-text" style="color: #6b7280; font-size: 14px; line-height: 1.6; margin: 20px 0;">
                                <strong>⏰ Penting:</strong> Link reset password ini akan kadaluarsa dalam <strong style="color: #ef4444;">60 menit</strong>.
                            </p>
                            
                            <p class="email-text" style="color: #374151; font-size: 16px; line-height: 1.6; margin: 20px 0 10px;">
                                Jika Anda <strong>tidak meminta</strong> reset password, abaikan email ini dan pastikan akun Anda aman. Pertimbangkan untuk mengganti password jika Anda mencurigai aktivitas yang tidak biasa.
                            </p>
                            
                            <!-- Alternative Link -->
                            <div class="info-box" style="background-color: #f9fafb; border-left: 4px solid #3b82f6; padding: 15px; margin-top: 30px; border-radius: 8px;">
                                <p class="info-text" style="color: #6b7280; font-size: 13px; margin: 0 0 10px;">
                                    <strong>Tombol tidak berfungsi?</strong> Salin dan tempel URL berikut ke browser Anda:
                                </p>
                                <p class="url-text" style="color: #3b82f6; font-size: 12px; word-break: break-all; margin: 0;">
                                    {{ $url }}
                                </p>
                            </div>
                            
                            <!-- Security Tips -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top: 30px; background-color: #fef3c7; border: 1px solid #fbbf24; border-radius: 8px; padding: 15px;">
                                <tr>
                                    <td>
                                        <p style="color: #92400e; font-size: 13px; margin: 0 0 8px; font-weight: bold;">
                                            🛡️ Tips Keamanan:
                                        </p>
                                        <ul style="color: #92400e; font-size: 12px; margin: 0; padding-left: 20px;">
                                            <li>Gunakan password minimal 8 karakter</li>
                                            <li>Kombinasikan huruf besar, kecil, angka, dan simbol</li>
                                            <li>Jangan gunakan password yang sama di berbagai layanan</li>
                                            <li>Aktifkan verifikasi dua faktor jika tersedia</li>
                                        </ul>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td class="email-footer" style="background-color: #1e293b; padding: 30px; text-align: center;">
                            <p class="footer-text" style="color: rgba(255,255,255,0.7); font-size: 14px; margin: 0 0 10px;">
                                © {{ date('Y') }} PT Polargate Indonesia Kreasi
                            </p>
                            <p class="footer-subtext" style="color: rgba(255,255,255,0.5); font-size: 12px; margin: 0;">
                                Email ini dikirim secara otomatis. Mohon tidak membalas email ini.
                            </p>
                            <p class="footer-subtext" style="color: rgba(255,255,255,0.5); font-size: 12px; margin: 10px 0 0;">
                                Jika Anda memiliki pertanyaan, silakan hubungi tim support kami.
                            </p>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html>