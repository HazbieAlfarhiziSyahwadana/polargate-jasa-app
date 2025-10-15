<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'logo' => Setting::get('logo'),
            'whatsapp_number' => Setting::get('whatsapp_number', '6281234567890'),
            'animasi_3d_image' => Setting::get('animasi_3d_image'),
            'visual_effect_image' => Setting::get('visual_effect_image'),
            'company_profile_image' => Setting::get('company_profile_image'),
            'tvc_image' => Setting::get('tvc_image'),
            'web_developer_image' => Setting::get('web_developer_image'),
            'apps_developer_image' => Setting::get('apps_developer_image'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function updateLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048'
        ]);

        // Delete old logo
        $oldLogo = Setting::get('logo');
        if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
            Storage::disk('public')->delete($oldLogo);
        }

        // Upload new logo
        $path = $request->file('logo')->store('settings', 'public');
        Setting::set('logo', $path);

        return back()->with('success', 'Logo berhasil diperbarui');
    }

    public function updateWa(Request $request)
    {
        $request->validate([
            'whatsapp_number' => 'required|string|max:20'
        ]);

        Setting::set('whatsapp_number', $request->whatsapp_number);

        return back()->with('success', 'Nomor WhatsApp berhasil diperbarui');
    }

    public function updateLayananImage(Request $request)
    {
        $request->validate([
            'layanan_key' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $validKeys = [
            'animasi_3d_image',
            'visual_effect_image',
            'company_profile_image',
            'tvc_image',
            'web_developer_image',
            'apps_developer_image'
        ];

        if (!in_array($request->layanan_key, $validKeys)) {
            return back()->with('error', 'Key layanan tidak valid');
        }

        // Delete old image
        $oldImage = Setting::get($request->layanan_key);
        if ($oldImage && Storage::disk('public')->exists($oldImage)) {
            Storage::disk('public')->delete($oldImage);
        }

        // Upload new image
        $path = $request->file('image')->store('services', 'public');
        Setting::set($request->layanan_key, $path);

        return back()->with('success', 'Gambar layanan berhasil diperbarui');
    }
}