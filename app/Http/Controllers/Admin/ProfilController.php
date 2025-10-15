<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('admin.profil.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'alamat' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'required|date|before:today',
            'no_telepon' => 'required|string|max:15',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'alamat.required' => 'Alamat wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            'no_telepon.required' => 'Nomor telepon wajib diisi.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format foto harus jpeg, png, atau jpg.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        try {
            DB::beginTransaction();

            // Siapkan data untuk update
            $dataToUpdate = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'alamat' => $validated['alamat'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'usia' => Carbon::parse($validated['tanggal_lahir'])->age,
                'no_telepon' => $validated['no_telepon'],
                'updated_at' => now(),
            ];

            // Handle foto upload
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                if ($user->foto && $user->foto != 'default-avatar.png') {
                    $oldPath = public_path('uploads/users/' . $user->foto);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }

                // Upload foto baru
                $file = $request->file('foto');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/users'), $filename);
                $dataToUpdate['foto'] = $filename;
            }

            // Update menggunakan DB query builder
            DB::table('users')
                ->where('id', $user->id)
                ->update($dataToUpdate);

            DB::commit();

            return redirect()->route('admin.profil')->with('success', 'Profil berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Hapus foto yang sudah diupload jika terjadi error
            if (isset($dataToUpdate['foto'])) {
                $photoPath = public_path('uploads/users/' . $dataToUpdate['foto']);
                if (file_exists($photoPath)) {
                    unlink($photoPath);
                }
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = Auth::user();

        // Cek password lama
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password lama tidak sesuai.'
            ])->withInput();
        }

        try {
            // Update password menggunakan DB query builder
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'password' => Hash::make($validated['password']),
                    'updated_at' => now(),
                ]);

            return redirect()->route('admin.profil')->with('success', 'Password berhasil diubah!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengubah password: ' . $e->getMessage());
        }
    }
}