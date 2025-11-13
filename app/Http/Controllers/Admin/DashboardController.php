<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\User;
use App\Models\Layanan;
use App\Models\Invoice;
use App\Models\Pembayaran;
use App\Models\Revisi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Helpers\FileHelper;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
{
    // ==========================================
    // 📊 DATA STATISTIK UTAMA
    // ==========================================
    
    // Total semua pesanan (termasuk dibatalkan)
    $total_pesanan = Pesanan::count();
    
    // Total client
    $total_client = User::where('role', 'client')->count();
    
    // ✅ Pesanan aktif (EXCLUDE Selesai dan Dibatalkan)
    $pesanan_aktif = Pesanan::whereNotIn('status', ['Selesai', 'Dibatalkan'])
        ->count();
    
    // ✅ Pendapatan total (hanya dari invoice lunas, EXCLUDE dibatalkan)
    $pendapatan_total = Invoice::where('status', 'Lunas')->sum('jumlah');

    // ==========================================
    // 📈 STATISTIK PESANAN PER STATUS
    // ==========================================
    $pesanan_per_status = Pesanan::select('status', DB::raw('count(*) as total'))
        ->groupBy('status')
        ->get()
        ->pluck('total', 'status');

    // ==========================================
    // 💰 STATISTIK INVOICE PER TIPE
    // ==========================================
    $invoice_stats = [
        'dp' => [
            // Total invoice DP (EXCLUDE dibatalkan)
            'total' => Invoice::where('tipe', 'DP')
                ->where('status', '!=', 'Dibatalkan')
                ->count(),
            
            // Invoice DP yang lunas
            'lunas' => Invoice::where('tipe', 'DP')
                ->where('status', 'Lunas')
                ->count(),
            
            // Invoice DP yang belum dibayar (EXCLUDE dibatalkan)
            'pending' => Invoice::where('tipe', 'DP')
                ->whereIn('status', ['Belum Dibayar', 'Belum Lunas'])
                ->count(),
            
            // Total nilai semua invoice DP (EXCLUDE dibatalkan)
            'total_amount' => Invoice::where('tipe', 'DP')
                ->where('status', '!=', 'Dibatalkan')
                ->sum('jumlah'),
            
            // Total nilai invoice DP yang lunas
            'lunas_amount' => Invoice::where('tipe', 'DP')
                ->where('status', 'Lunas')
                ->sum('jumlah'),
            
            // Total nilai invoice DP yang pending
            'pending_amount' => Invoice::where('tipe', 'DP')
                ->whereIn('status', ['Belum Dibayar', 'Belum Lunas'])
                ->sum('jumlah')
        ],
        'pelunasan' => [
            // Total invoice Pelunasan (EXCLUDE dibatalkan)
            'total' => Invoice::where('tipe', 'Pelunasan')
                ->where('status', '!=', 'Dibatalkan')
                ->count(),
            
            // Invoice Pelunasan yang lunas
            'lunas' => Invoice::where('tipe', 'Pelunasan')
                ->where('status', 'Lunas')
                ->count(),
            
            // Invoice Pelunasan yang pending (EXCLUDE dibatalkan)
            'pending' => Invoice::where('tipe', 'Pelunasan')
                ->whereIn('status', ['Belum Dibayar', 'Belum Lunas'])
                ->count(),
            
            // Total nilai invoice Pelunasan (EXCLUDE dibatalkan)
            'total_amount' => Invoice::where('tipe', 'Pelunasan')
                ->where('status', '!=', 'Dibatalkan')
                ->sum('jumlah'),
            
            // Total nilai invoice Pelunasan yang lunas
            'lunas_amount' => Invoice::where('tipe', 'Pelunasan')
                ->where('status', 'Lunas')
                ->sum('jumlah'),
            
            // Total nilai invoice Pelunasan yang pending
            'pending_amount' => Invoice::where('tipe', 'Pelunasan')
                ->whereIn('status', ['Belum Dibayar', 'Belum Lunas'])
                ->sum('jumlah')
        ]
    ];

    // ==========================================
    // 📋 INVOICE PENDING (Perlu Perhatian)
    // ==========================================
    // ✅ EXCLUDE: Invoice dibatalkan & Pesanan dibatalkan
    $invoice_pending = Invoice::with(['pesanan.client', 'pesanan.layanan', 'pembayaran'])
        ->where(function($query) {
            // Invoice belum dibayar ATAU ada pembayaran menunggu verifikasi
            $query->whereIn('status', ['Belum Dibayar', 'Belum Lunas'])
                ->orWhereHas('pembayaran', function($q) {
                    $q->where('status', 'Menunggu Verifikasi');
                });
        })
        // EXCLUDE invoice yang dibatalkan
        ->where('status', '!=', 'Dibatalkan')
        // EXCLUDE invoice dari pesanan yang dibatalkan
        ->whereHas('pesanan', function($q) {
            $q->where('status', '!=', 'Dibatalkan');
        })
        ->whereIn('tipe', ['DP', 'Pelunasan'])
        ->latest()
        ->take(5)
        ->get();

    // ==========================================
    // 📊 PENDAPATAN BULAN INI VS BULAN LALU
    // ==========================================
    $current_month = date('m');
    $current_year = date('Y');
    $last_month = $current_month == 1 ? 12 : $current_month - 1;
    $last_year = $current_month == 1 ? $current_year - 1 : $current_year;

    // ✅ Hanya hitung invoice yang lunas (EXCLUDE dibatalkan otomatis karena status = 'Lunas')
    $pendapatan_bulan_ini = Invoice::where('status', 'Lunas')
        ->whereMonth('created_at', $current_month)
        ->whereYear('created_at', $current_year)
        ->sum('jumlah');

    $pendapatan_bulan_lalu = Invoice::where('status', 'Lunas')
        ->whereMonth('created_at', $last_month)
        ->whereYear('created_at', $last_year)
        ->sum('jumlah');

    // Hitung persentase pertumbuhan
    $persentase_pertumbuhan = $pendapatan_bulan_lalu > 0 
        ? (($pendapatan_bulan_ini - $pendapatan_bulan_lalu) / $pendapatan_bulan_lalu) * 100 
        : ($pendapatan_bulan_ini > 0 ? 100 : 0);

    // ==========================================
    // 📦 PESANAN TERBARU (EXCLUDE Dibatalkan)
    // ==========================================
    $pesanan_terbaru = Pesanan::with(['client', 'layanan'])
        ->where('status', '!=', 'Dibatalkan')
        ->latest()
        ->take(5)
        ->get();

    // ==========================================
    // 🔥 LAYANAN POPULER (EXCLUDE Pesanan Dibatalkan)
    // ==========================================
    $layanan_populer = Layanan::withCount(['pesanan' => function($query) {
            $query->where('status', '!=', 'Dibatalkan');
        }])
        ->having('pesanan_count', '>', 0) // Hanya tampilkan yang ada pesanan
        ->orderBy('pesanan_count', 'desc')
        ->take(10)
        ->get();

    // ==========================================
    // 💳 PEMBAYARAN PENDING (Menunggu Verifikasi)
    // ==========================================
    // ✅ EXCLUDE: Invoice & Pesanan yang dibatalkan
    $pembayaran_pending = Pembayaran::where('status', 'Menunggu Verifikasi')
        ->with(['invoice.pesanan.client'])
        ->whereHas('invoice', function($q) {
            $q->where('status', '!=', 'Dibatalkan')
              ->whereHas('pesanan', function($q2) {
                  $q2->where('status', '!=', 'Dibatalkan');
              });
        })
        ->latest()
        ->take(5)
        ->get();

    // ==========================================
    // 👥 CLIENT TERBARU
    // ==========================================
    $client_terbaru = User::where('role', 'client')
        ->withCount(['pesanan' => function($query) {
            $query->where('status', '!=', 'Dibatalkan');
        }])
        ->latest()
        ->take(5)
        ->get();

    // ==========================================
    // ✏️ REVISI PENDING (EXCLUDE Pesanan Dibatalkan)
    // ==========================================
    $revisi_pending = Revisi::where('status', 'Diminta')
        ->with(['pesanan.client'])
        ->whereHas('pesanan', function($q) {
            $q->where('status', '!=', 'Dibatalkan');
        })
        ->latest()
        ->take(5)
        ->get();

    // ==========================================
    // 🏆 TOP CLIENTS (EXCLUDE Pesanan Dibatalkan)
    // ==========================================
    $top_clients = User::where('role', 'client')
        ->withCount(['pesanan' => function($query) {
            $query->where('status', '!=', 'Dibatalkan');
        }])
        ->with(['pesanan' => function($query) {
            $query->where('status', '!=', 'Dibatalkan')
                  ->select('client_id', DB::raw('SUM(total_harga) as total_pembelian'))
                  ->groupBy('client_id');
        }])
        ->get()
        ->map(function($client) {
            // Hitung total pembelian dari invoice yang lunas
            $total_pembelian = Invoice::whereHas('pesanan', function($q) use ($client) {
                    $q->where('client_id', $client->id)
                      ->where('status', '!=', 'Dibatalkan');
                })
                ->where('status', 'Lunas')
                ->sum('jumlah');
            
            $client->total_pembelian = $total_pembelian;
            return $client;
        })
        ->filter(function($client) {
            return $client->total_pembelian > 0;
        })
        ->sortByDesc('total_pembelian')
        ->take(5)
        ->values();

    // ==========================================
    // 📤 RETURN DATA KE VIEW
    // ==========================================
    $data = [
        // Stats utama
        'total_pesanan' => $total_pesanan,
        'total_client' => $total_client,
        'pesanan_aktif' => $pesanan_aktif,
        'pendapatan_total' => $pendapatan_total,
        
        // Statistik detail
        'pesanan_per_status' => $pesanan_per_status,
        'invoice_stats' => $invoice_stats,
        'invoice_pending' => $invoice_pending,
        'pendapatan_bulan_ini' => $pendapatan_bulan_ini,
        'pendapatan_bulan_lalu' => $pendapatan_bulan_lalu,
        'persentase_pertumbuhan' => $persentase_pertumbuhan,
        
        // Data tables
        'pesanan_terbaru' => $pesanan_terbaru,
        'layanan_populer' => $layanan_populer,
        'pembayaran_pending' => $pembayaran_pending,
        'client_terbaru' => $client_terbaru,
        'revisi_pending' => $revisi_pending,
        'top_clients' => $top_clients,
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
                'updated_at' => now(),
            ];

            // Upload foto jika ada
            if ($request->hasFile('foto')) {
                $dataToUpdate['foto'] = FileHelper::uploadFile(
                    $request->file('foto'),
                    'users',
                    $user->foto
                );
            }

            // Update user
            $user->update($dataToUpdate);

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
            // Update password
            $user->update([
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