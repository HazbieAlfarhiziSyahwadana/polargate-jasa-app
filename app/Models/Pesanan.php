<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';

    protected $fillable = [
        'user_id',
        'client_id',
        'layanan_id',
        'paket_id',
        'kode_pesanan',
        'detail_pesanan', // ← Ubah dari 'brief' ke 'detail_pesanan'
        'harga_paket',
        'total_harga',
        'status',
        'preview_link',
        'preview_expired_at',
        'is_preview_active',
        'file_final',
        'file_pendukung',
        'catatan_revisi',
        'alasan_pembatalan', // ← Tambahkan ini
        'dibatalkan_at', // ← Tambahkan ini
    ];

    protected $casts = [
        'harga_paket' => 'decimal:2',
        'total_harga' => 'decimal:2',
        'is_preview_active' => 'boolean',
        'preview_expired_at' => 'datetime',
        'file_final' => 'array',
        'file_pendukung' => 'array',
        'dibatalkan_at' => 'datetime',
    ];

    // Relasi ke User (jika menggunakan user_id)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Client (jika menggunakan client_id)
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class);
    }

    public function addons()
    {
        return $this->belongsToMany(Addon::class, 'pesanan_addons')
            ->withPivot('harga')
            ->withTimestamps();
    }

    public function pesananAddons()
    {
        return $this->hasMany(PesananAddon::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function revisi()
    {
        return $this->hasMany(Revisi::class);
    }

    // Helper Methods
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'Menunggu Pembayaran DP' => 'bg-yellow-100 text-yellow-800',
            'Menunggu Pembayaran' => 'bg-yellow-100 text-yellow-800',
            'Sedang Diproses' => 'bg-blue-100 text-blue-800',
            'Preview Siap' => 'bg-purple-100 text-purple-800',
            'Menunggu Pelunasan' => 'bg-orange-100 text-orange-800',
            'Selesai' => 'bg-green-100 text-green-800',
            'Dibatalkan' => 'bg-red-100 text-red-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getTotalRevisiAttribute()
    {
        return $this->revisi()->count();
    }

    public function getKuotaRevisiAttribute()
    {
        return $this->paket->jumlah_revisi ?? 0;
    }

    public function getSisaRevisiAttribute()
    {
        return max(0, $this->kuota_revisi - $this->total_revisi);
    }

    // ← PERBAIKAN: Tambahkan method untuk cek preview aktif
    public function isPreviewActive()
    {
        if (!$this->preview_link || !$this->is_preview_active) {
            return false;
        }

        if (!$this->preview_expired_at) {
            return true; // Jika tidak ada expiry, dianggap aktif
        }

        return Carbon::parse($this->preview_expired_at)->isFuture();
    }

    // ← PERBAIKAN: Method untuk auto-update status preview jika expired
    public function checkAndUpdatePreviewExpiry()
    {
        if ($this->preview_expired_at && Carbon::parse($this->preview_expired_at)->isPast()) {
            $this->update(['is_preview_active' => false]);
            return false;
        }
        return $this->is_preview_active;
    }

    public function canRequestRevisi()
    {
        // Cek apakah DP sudah lunas
        $invoiceDP = $this->invoices()->where('tipe', 'DP')->first();
        $dpLunas = $invoiceDP && $invoiceDP->status === 'Lunas';

        // Cek apakah ada revisi aktif (sedang diproses)
        $hasActiveRevisi = $this->revisi()
            ->whereIn('status', ['Diminta', 'Sedang Dikerjakan'])
            ->exists();
        
        return in_array($this->status, ['Preview Siap', 'Menunggu Pelunasan']) 
            && $dpLunas // ← Pastikan DP sudah lunas
            && $this->sisa_revisi > 0
            && !$hasActiveRevisi;
    }
    
    public function hasActiveRevisi()
    {
        return $this->revisi()
            ->whereIn('status', ['Diminta', 'Sedang Dikerjakan'])
            ->exists();
    }
    
    public function getActiveRevisiAttribute()
    {
        return $this->revisi()
            ->whereIn('status', ['Diminta', 'Sedang Dikerjakan'])
            ->latest()
            ->first();
    }

    public function hasFiles($type = 'final')
    {
        $field = $type === 'final' ? 'file_final' : 'file_pendukung';
        return !empty($this->$field) && is_array($this->$field);
    }

    public function getFileCount($type = 'final')
    {
        return $this->hasFiles($type) ? count($this->{$type === 'final' ? 'file_final' : 'file_pendukung'}) : 0;
    }

    // ← TAMBAHAN: Method untuk reset preview expiry saat upload baru
    public function resetPreviewExpiry($days = 7)
    {
        $this->update([
            'preview_expired_at' => Carbon::now()->addDays($days),
            'is_preview_active' => true,
        ]);
    }
}