<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $services = [
            'multimedia' => [
                [
                    'name' => 'Animasi 3D',
                    'description' => 'Pembuatan animasi 3D berkualitas tinggi untuk kebutuhan bisnis Anda',
                    'icon' => 'fa-cube',
                    'image' => Setting::get('animasi_3d_image')
                ],
                [
                    'name' => 'Visual Effect',
                    'description' => 'Efek visual profesional untuk video dan film Anda',
                    'icon' => 'fa-magic',
                    'image' => Setting::get('visual_effect_image')
                ],
                [
                    'name' => 'Video Company Profile',
                    'description' => 'Video profil perusahaan yang menarik dan profesional',
                    'icon' => 'fa-video',
                    'image' => Setting::get('company_profile_image')
                ],
                [
                    'name' => 'TVC',
                    'description' => 'Iklan televisi komersial yang menarik perhatian',
                    'icon' => 'fa-tv',
                    'image' => Setting::get('tvc_image')
                ],
            ],
            'it' => [
                [
                    'name' => 'Web Developer',
                    'description' => 'Pembuatan website modern dan responsif sesuai kebutuhan',
                    'icon' => 'fa-laptop-code',
                    'image' => Setting::get('web_developer_image')
                ],
                [
                    'name' => 'Apps Developer',
                    'description' => 'Pengembangan aplikasi mobile Android dan iOS',
                    'icon' => 'fa-mobile-alt',
                    'image' => Setting::get('apps_developer_image')
                ],
            ]
        ];

        $logo = Setting::get('logo');
        $whatsapp = Setting::get('whatsapp_number', '6281234567890');

        return view('landing', compact('services', 'logo', 'whatsapp'));
    }
}