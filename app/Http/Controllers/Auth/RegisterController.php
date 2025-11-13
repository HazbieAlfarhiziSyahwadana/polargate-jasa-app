<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers\FileHelper;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'alamat' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'required|date|before:today',
            'no_telepon' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.max' => 'Nama lengkap maksimal 255 karakter.',
            'foto.required' => 'Foto profil wajib diupload.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format foto harus jpg, jpeg, atau png.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
            'alamat.required' => 'Alamat wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid.',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            'no_telepon.required' => 'Nomor telepon wajib diisi.',
            'no_telepon.max' => 'Nomor telepon maksimal 20 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'email.unique' => 'Email sudah terdaftar. Silakan gunakan email lain.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        try {
            DB::beginTransaction();

            // Upload foto profil
            $fotoName = null;
            if ($request->hasFile('foto')) {
                $fotoName = FileHelper::uploadFile(
                    $request->file('foto'),
                    'users'
                );
            }

            // Hitung usia dari tanggal lahir
            $usia = Carbon::parse($validated['tanggal_lahir'])->age;

            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'foto' => $fotoName,
                'alamat' => $validated['alamat'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'usia' => $usia,
                'no_telepon' => $validated['no_telepon'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'client',
                'is_active' => true,
            ]);

            DB::commit();

            return redirect()->route('login')
                ->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Hapus foto yang sudah diupload jika terjadi error
            if (isset($fotoName)) {
                FileHelper::deleteFile('users', $fotoName);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat registrasi: ' . $e->getMessage());
        }
    }
}