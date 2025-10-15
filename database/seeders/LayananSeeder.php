<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Layanan;
use App\Models\Paket;
use App\Models\Addon;

class LayananSeeder extends Seeder
{
    public function run(): void
    {
        // ===== MULTIMEDIA =====
        
        // 1. Animasi 3D
        $animasi3d = Layanan::create([
            'kategori' => 'Multimedia',
            'nama_layanan' => 'Animasi 3D',
            'deskripsi' => 'Layanan pembuatan animasi 3D profesional untuk berbagai kebutuhan seperti iklan, presentasi, atau konten digital.',
            'harga_mulai' => 5000000,
            'is_active' => true,
        ]);

        Paket::create([
            'layanan_id' => $animasi3d->id,
            'nama_paket' => 'Basic 3D',
            'deskripsi' => 'Paket dasar untuk animasi 3D sederhana',
            'harga' => 5000000,
            'fitur' => json_encode([
                'Durasi maksimal 30 detik',
                'Modeling sederhana',
                'Render HD 720p',
                '1x Revisi'
            ]),
            'durasi_pengerjaan' => 14,
            'jumlah_revisi' => 1,
            'is_active' => true,
        ]);

        Paket::create([
            'layanan_id' => $animasi3d->id,
            'nama_paket' => 'Professional 3D',
            'deskripsi' => 'Paket profesional dengan kualitas tinggi',
            'harga' => 10000000,
            'fitur' => json_encode([
                'Durasi maksimal 60 detik',
                'Modeling kompleks',
                'Render Full HD 1080p',
                'Texturing & Lighting premium',
                '3x Revisi'
            ]),
            'durasi_pengerjaan' => 21,
            'jumlah_revisi' => 3,
            'is_active' => true,
        ]);

        Addon::create([
            'layanan_id' => $animasi3d->id,
            'nama_addon' => 'Render 4K',
            'deskripsi' => 'Upgrade render ke resolusi 4K',
            'harga' => 2000000,
            'is_active' => true,
        ]);

        Addon::create([
            'layanan_id' => $animasi3d->id,
            'nama_addon' => 'Character Rigging',
            'deskripsi' => 'Penambahan rigging untuk karakter',
            'harga' => 1500000,
            'is_active' => true,
        ]);

        // 2. Visual Effect
        $vfx = Layanan::create([
            'kategori' => 'Multimedia',
            'nama_layanan' => 'Visual Effect (VFX)',
            'deskripsi' => 'Layanan visual effect untuk video, film pendek, atau konten kreatif dengan efek khusus yang memukau.',
            'harga_mulai' => 3000000,
            'is_active' => true,
        ]);

        Paket::create([
            'layanan_id' => $vfx->id,
            'nama_paket' => 'VFX Basic',
            'deskripsi' => 'Efek visual sederhana',
            'harga' => 3000000,
            'fitur' => json_encode([
                'Durasi video maksimal 2 menit',
                'Green screen removal',
                'Color grading basic',
                '2x Revisi'
            ]),
            'durasi_pengerjaan' => 7,
            'jumlah_revisi' => 2,
            'is_active' => true,
        ]);

        Paket::create([
            'layanan_id' => $vfx->id,
            'nama_paket' => 'VFX Advanced',
            'deskripsi' => 'Efek visual kompleks dan profesional',
            'harga' => 7000000,
            'fitur' => json_encode([
                'Durasi video maksimal 5 menit',
                'Compositing advanced',
                'Motion tracking',
                'Particle effects',
                'Color grading professional',
                '4x Revisi'
            ]),
            'durasi_pengerjaan' => 14,
            'jumlah_revisi' => 4,
            'is_active' => true,
        ]);

        // 3. Video Company Profile
        $compro = Layanan::create([
            'kategori' => 'Multimedia',
            'nama_layanan' => 'Video Company Profile',
            'deskripsi' => 'Pembuatan video profil perusahaan yang profesional dan menarik untuk meningkatkan brand awareness.',
            'harga_mulai' => 4000000,
            'is_active' => true,
        ]);

        Paket::create([
            'layanan_id' => $compro->id,
            'nama_paket' => 'Company Profile Standard',
            'deskripsi' => 'Video company profile standar',
            'harga' => 4000000,
            'fitur' => json_encode([
                'Durasi 3-5 menit',
                'Shooting 1 hari',
                'Basic editing',
                'Background music',
                '2x Revisi'
            ]),
            'durasi_pengerjaan' => 10,
            'jumlah_revisi' => 2,
            'is_active' => true,
        ]);

        Paket::create([
            'layanan_id' => $compro->id,
            'nama_paket' => 'Company Profile Premium',
            'deskripsi' => 'Video company profile dengan kualitas sinematik',
            'harga' => 8000000,
            'fitur' => json_encode([
                'Durasi 5-8 menit',
                'Shooting 2 hari',
                'Cinematic editing',
                'Drone footage',
                'Voice over profesional',
                'Original music scoring',
                '3x Revisi'
            ]),
            'durasi_pengerjaan' => 14,
            'jumlah_revisi' => 3,
            'is_active' => true,
        ]);

        Addon::create([
            'layanan_id' => $compro->id,
            'nama_addon' => 'Subtitle Bahasa Inggris',
            'deskripsi' => 'Penambahan subtitle bahasa Inggris',
            'harga' => 500000,
            'is_active' => true,
        ]);

        // 4. TVC (TV Commercial)
        $tvc = Layanan::create([
            'kategori' => 'Multimedia',
            'nama_layanan' => 'TVC (TV Commercial)',
            'deskripsi' => 'Produksi iklan televisi profesional dengan konsep kreatif dan eksekusi berkualitas tinggi.',
            'harga_mulai' => 15000000,
            'is_active' => true,
        ]);

        Paket::create([
            'layanan_id' => $tvc->id,
            'nama_paket' => 'TVC 30 Detik',
            'deskripsi' => 'Iklan TV durasi 30 detik',
            'harga' => 15000000,
            'fitur' => json_encode([
                'Durasi 30 detik',
                'Konsep kreatif',
                'Shooting 2 hari',
                'Talent 2 orang',
                'Professional editing',
                '3x Revisi'
            ]),
            'durasi_pengerjaan' => 21,
            'jumlah_revisi' => 3,
            'is_active' => true,
        ]);

        Paket::create([
            'layanan_id' => $tvc->id,
            'nama_paket' => 'TVC 60 Detik Premium',
            'deskripsi' => 'Iklan TV durasi 60 detik dengan produksi premium',
            'harga' => 30000000,
            'fitur' => json_encode([
                'Durasi 60 detik',
                'Konsep kreatif + storyboard',
                'Shooting 3 hari',
                'Talent unlimited',
                'Multiple location',
                'VFX & color grading premium',
                '5x Revisi'
            ]),
            'durasi_pengerjaan' => 30,
            'jumlah_revisi' => 5,
            'is_active' => true,
        ]);

        // ===== IT =====

        // 5. Web Developer
        $webdev = Layanan::create([
            'kategori' => 'IT',
            'nama_layanan' => 'Web Developer',
            'deskripsi' => 'Jasa pembuatan website profesional, mulai dari landing page hingga sistem web kompleks.',
            'harga_mulai' => 3000000,
            'is_active' => true,
        ]);

        Paket::create([
            'layanan_id' => $webdev->id,
            'nama_paket' => 'Landing Page',
            'deskripsi' => 'Website satu halaman untuk promosi',
            'harga' => 3000000,
            'fitur' => json_encode([
                '1 halaman',
                'Responsive design',
                'Contact form',
                'SEO basic',
                'Free domain .com (1 tahun)',
                'Free hosting (1 tahun)',
                '2x Revisi'
            ]),
            'durasi_pengerjaan' => 7,
            'jumlah_revisi' => 2,
            'is_active' => true,
        ]);

        Paket::create([
            'layanan_id' => $webdev->id,
            'nama_paket' => 'Company Profile Website',
            'deskripsi' => 'Website profil perusahaan multi halaman',
            'harga' => 7000000,
            'fitur' => json_encode([
                'Maksimal 10 halaman',
                'Responsive design',
                'Admin panel CMS',
                'Contact form & Google Maps',
                'SEO optimized',
                'Free domain .com (1 tahun)',
                'Free hosting (1 tahun)',
                '3x Revisi'
            ]),
            'durasi_pengerjaan' => 14,
            'jumlah_revisi' => 3,
            'is_active' => true,
        ]);

        Paket::create([
            'layanan_id' => $webdev->id,
            'nama_paket' => 'E-Commerce',
            'deskripsi' => 'Website toko online lengkap',
            'harga' => 15000000,
            'fitur' => json_encode([
                'Unlimited produk',
                'Shopping cart',
                'Payment gateway integration',
                'Admin panel lengkap',
                'Inventory management',
                'Order tracking',
                'SEO optimized',
                'Free domain .com (1 tahun)',
                'Free hosting (1 tahun)',
                '5x Revisi'
            ]),
            'durasi_pengerjaan' => 30,
            'jumlah_revisi' => 5,
            'is_active' => true,
        ]);

        Addon::create([
            'layanan_id' => $webdev->id,
            'nama_addon' => 'Live Chat Integration',
            'deskripsi' => 'Integrasi fitur live chat',
            'harga' => 1000000,
            'is_active' => true,
        ]);

        Addon::create([
            'layanan_id' => $webdev->id,
            'nama_addon' => 'Multilanguage',
            'deskripsi' => 'Fitur multi bahasa (ID & EN)',
            'harga' => 2000000,
            'is_active' => true,
        ]);

        // 6. Apps Developer
        $appdev = Layanan::create([
            'kategori' => 'IT',
            'nama_layanan' => 'Apps Developer',
            'deskripsi' => 'Jasa pembuatan aplikasi mobile Android dan iOS untuk berbagai kebutuhan bisnis.',
            'harga_mulai' => 20000000,
            'is_active' => true,
        ]);

        Paket::create([
            'layanan_id' => $appdev->id,
            'nama_paket' => 'Mobile App Basic',
            'deskripsi' => 'Aplikasi mobile sederhana',
            'harga' => 20000000,
            'fitur' => json_encode([
                'Android atau iOS (pilih salah satu)',
                'Maksimal 10 screen',
                'Basic features',
                'Admin panel web',
                'Free maintenance 3 bulan',
                '3x Revisi'
            ]),
            'durasi_pengerjaan' => 45,
            'jumlah_revisi' => 3,
            'is_active' => true,
        ]);

        Paket::create([
            'layanan_id' => $appdev->id,
            'nama_paket' => 'Mobile App Professional',
            'deskripsi' => 'Aplikasi mobile profesional',
            'harga' => 40000000,
            'fitur' => json_encode([
                'Android DAN iOS',
                'Unlimited screen',
                'Advanced features',
                'Push notification',
                'In-app purchase',
                'Admin panel web advanced',
                'Free maintenance 6 bulan',
                '5x Revisi'
            ]),
            'durasi_pengerjaan' => 60,
            'jumlah_revisi' => 5,
            'is_active' => true,
        ]);

        Addon::create([
            'layanan_id' => $appdev->id,
            'nama_addon' => 'Payment Gateway Integration',
            'deskripsi' => 'Integrasi payment gateway',
            'harga' => 5000000,
            'is_active' => true,
        ]);

        Addon::create([
            'layanan_id' => $appdev->id,
            'nama_addon' => 'Social Media Login',
            'deskripsi' => 'Login dengan Facebook, Google, Apple',
            'harga' => 2000000,
            'is_active' => true,
        ]);
    }
}