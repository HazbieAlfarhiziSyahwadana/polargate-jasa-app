<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\User;
use App\Models\Layanan;
use App\Models\Invoice;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Helpers\FileHelper;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'total_pesanan' => Pesanan::count(),
            'total_client' => User::where('role', 'client')->count(),
            'pesanan_aktif' => Pesanan::whereIn('status', [
                'Sedang Diproses',
                'Preview Siap',
                'Revisi Diminta',
                'Menunggu Pelunasan'
            ])->count(),
            'pendapatan_total' => Invoice::where('status', 'Lunas')->sum('jumlah'),
            'pesanan_terbaru' => Pesanan::with(['client', 'layanan'])
                ->latest()
                ->take(5)
                ->get(),
            'layanan_populer' => Layanan::withCount('pesanan')
                ->orderBy('pesanan_count', 'desc')
                ->take(10)
                ->get(),
            'pembayaran_pending' => Pembayaran::where('status', 'Menunggu Verifikasi')
                ->with(['invoice.pesanan.client'])
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('admin.dashboard', $data);
    }

    public function profil()
    {
        return view('admin.profil.index', [
            'user' => Auth::user()
        ]);
    }

    public function updateProfil(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'alamat' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'required|date|before:today',
            'no_telepon' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format foto harus jpg, jpeg, atau png.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
            'alamat.required' => 'Alamat wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            'no_telepon.required' => 'Nomor telepon wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
        ]);

        try {
            DB::beginTransaction();

            // Siapkan data untuk update
            $dataToUpdate = [
                'name' => $validated['name'],
                'alamat' => $validated['alamat'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'usia' => Carbon::parse($validated['tanggal_lahir'])->age,
                'no_telepon' => $validated['no_telepon'],
                'email' => $validated['email'],
            ];

            // Upload foto jika ada
            if ($request->hasFile('foto')) {
                $dataToUpdate['foto'] = FileHelper::uploadFile(
                    $request->file('foto'),
                    'users',
                    $user->foto
                );
            }

            // Update menggunakan DB query builder untuk menghindari error
            DB::table('users')
                ->where('id', $user->id)
                ->update($dataToUpdate);

            DB::commit();

            return redirect()->back()->with('success', 'Profil berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Hapus foto yang sudah diupload jika terjadi error
            if (isset($dataToUpdate['foto'])) {
                FileHelper::deleteFile('users', $dataToUpdate['foto']);
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

            return redirect()->back()->with('success', 'Password berhasil diubah!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengubah password: ' . $e->getMessage());
        }
    }
}