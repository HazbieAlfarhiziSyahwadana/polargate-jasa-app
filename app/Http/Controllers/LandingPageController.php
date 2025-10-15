<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        // Data layanan yang ditawarkan
        $services = [
            'multimedia' => [
                [
                    'name' => 'Animasi 3D',
                    'icon' => 'cube',
                    'description' => 'Pembuatan animasi 3D berkualitas tinggi untuk berbagai kebutuhan bisnis dan entertainment'
                ],
                [
                    'name' => 'Visual Effect',
                    'icon' => 'wand-magic-sparkles',
                    'description' => 'Efek visual memukau untuk meningkatkan kualitas konten visual Anda'
                ],
                [
                    'name' => 'Video Company Profile',
                    'icon' => 'video',
                    'description' => 'Video profil perusahaan profesional untuk meningkatkan brand awareness'
                ],
                [
                    'name' => 'TVC (TV Commercial)',
                    'icon' => 'tv',
                    'description' => 'Iklan televisi yang menarik dan efektif untuk promosi produk/jasa Anda'
                ]
            ],
            'it' => [
                [
                    'name' => 'Web Developer',
                    'icon' => 'code',
                    'description' => 'Pembuatan website profesional, responsif, dan sesuai kebutuhan bisnis Anda'
                ],
                [
                    'name' => 'Apps Developer',
                    'icon' => 'mobile-screen',
                    'description' => 'Pengembangan aplikasi mobile Android dan iOS untuk digitalisasi bisnis'
                ]
            ]
        ];

        return view('landing', compact('services'));
    }
}