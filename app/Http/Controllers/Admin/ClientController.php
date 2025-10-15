<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\FileHelper;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'client')->withCount('pesanan');

        // Filter pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('no_telepon', 'like', "%{$search}%");
            });
        }

        // Filter status
        if ($request->has('status') && $request->status != '') {
            $isActive = $request->status == 'active' ? true : false;
            $query->where('is_active', $isActive);
        }

        $clients = $query->latest()->paginate(15);

        return view('admin.client.index', compact('clients'));
    }

    public function show(User $user)
    {
        if ($user->role !== 'client') {
            abort(404);
        }

        $user->load(['pesanan.layanan', 'pesanan.invoices']);

        return view('admin.client.show', compact('user'));
    }

    public function edit(User $user)
    {
        if ($user->role !== 'client') {
            abort(404);
        }

        return view('admin.client.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->role !== 'client') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'alamat' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'required|date|before:today',
            'no_telepon' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'is_active' => 'boolean',
        ]);

        // Upload foto jika ada
        if ($request->hasFile('foto')) {
            $validated['foto'] = FileHelper::uploadFile(
                $request->file('foto'),
                'users',
                $user->foto
            );
        }

        // Hitung ulang usia
        $validated['usia'] = Carbon::parse($validated['tanggal_lahir'])->age;
        $validated['is_active'] = $request->has('is_active') ? true : false;

        $user->update($validated);

        return redirect()->route('admin.client.index')
            ->with('success', 'Data client berhasil diperbarui!');
    }

    public function toggleStatus(User $user)
    {
        if ($user->role !== 'client') {
            abort(404);
        }

        $user->update([
            'is_active' => !$user->is_active
        ]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()
            ->with('success', "Akun client berhasil {$status}!");
    }

    public function destroy(User $user)
    {
        if ($user->role !== 'client') {
            abort(404);
        }

        // Cek apakah client memiliki pesanan
        if ($user->pesanan()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Client tidak dapat dihapus karena memiliki riwayat pesanan!');
        }

        // Hapus foto jika ada
        if ($user->foto) {
            FileHelper::deleteFile('users', $user->foto);
        }

        $user->delete();

        return redirect()->route('admin.client.index')
            ->with('success', 'Client berhasil dihapus!');
    }
}