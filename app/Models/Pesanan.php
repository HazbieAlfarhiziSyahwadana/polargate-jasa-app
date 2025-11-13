<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';

    // Status constants untuk memudahkan referensi
    const STATUS_PESANAN_DIBUAT = 'Pesanan Dibuat';
    const STATUS_MENUNGGU_DP = 'Menunggu Pembayaran DP';
    const STATUS_DIPROSES = 'Sedang Diproses';
    const STATUS_PREVIEW_SIAP = 'Preview Siap';
    const STATUS_REVISI_DIPROSES = 'Revisi Diproses';
    const STATUS_REVISI_PREVIEW_SIAP = 'Revisi Preview Siap';
    const STATUS_REVISI_SELESAI = 'Revisi Selesai';
    const STATUS_MENUNGGU_PELUNASAN = 'Menunggu Pelunasan';
    const STATUS_MENUNGGU_FILE_FINAL = 'Menunggu File Final';
    const STATUS_SELESAI = 'Selesai';
    const STATUS_DIBATALKAN = 'Dibatalkan';

    protected $fillable = [
        'user_id',
        'client_id',
        'layanan_id',
        'paket_id',
        'kode_pesanan',
        'detail_pesanan',
        'harga_paket',
        'total_harga',
        'status',
        'preview_link',
        'preview_expired_at',
        'is_preview_active',
        'file_final',
        'file_pendukung',
        'catatan_revisi',
        'alasan_pembatalan',
        'dibatalkan_at',
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

    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

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

    // Helper Methods untuk Status Badge
    public function getStatusBadgeAttribute()
    {
        $badges = [
            self::STATUS_PESANAN_DIBUAT => 'bg-gray-100 text-gray-800',
            self::STATUS_MENUNGGU_DP => 'bg-yellow-100 text-yellow-800',
            self::STATUS_DIPROSES => 'bg-blue-100 text-blue-800',
            self::STATUS_PREVIEW_SIAP => 'bg-purple-100 text-purple-800',
            self::STATUS_REVISI_DIPROSES => 'bg-orange-100 text-orange-800',
            self::STATUS_REVISI_PREVIEW_SIAP => 'bg-indigo-100 text-indigo-800',
            self::STATUS_REVISI_SELESAI => 'bg-teal-100 text-teal-800',
            self::STATUS_MENUNGGU_PELUNASAN => 'bg-amber-100 text-amber-800',
            self::STATUS_MENUNGGU_FILE_FINAL => 'bg-cyan-100 text-cyan-800',
            self::STATUS_SELESAI => 'bg-green-100 text-green-800',
            self::STATUS_DIBATALKAN => 'bg-red-100 text-red-800',
            
            // Backward compatibility dengan status lama
            'Menunggu Pembayaran' => 'bg-yellow-100 text-yellow-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    // Helper untuk mendapatkan order status (untuk timeline)
    public function getStatusOrderAttribute()
    {
        $order = [
            self::STATUS_PESANAN_DIBUAT => 1,
            self::STATUS_MENUNGGU_DP => 2,
            self::STATUS_DIPROSES => 3,
            self::STATUS_PREVIEW_SIAP => 4,
            self::STATUS_REVISI_DIPROSES => 5,
            self::STATUS_REVISI_PREVIEW_SIAP => 6,
            self::STATUS_REVISI_SELESAI => 7,
            self::STATUS_MENUNGGU_PELUNASAN => 8,
            self::STATUS_MENUNGGU_FILE_FINAL => 9,
            self::STATUS_SELESAI => 10,
            self::STATUS_DIBATALKAN => 0,
        ];

        return $order[$this->status] ?? 0;
    }

    // Cek apakah status sudah melewati status tertentu
    public function hasPassedStatus($status)
    {
        if ($this->status === self::STATUS_DIBATALKAN) {
            return false;
        }
        
        return $this->status_order >= $this->getStatusOrder($status);
    }

    private function getStatusOrder($status)
    {
        $order = [
            self::STATUS_PESANAN_DIBUAT => 1,
            self::STATUS_MENUNGGU_DP => 2,
            self::STATUS_DIPROSES => 3,
            self::STATUS_PREVIEW_SIAP => 4,
            self::STATUS_REVISI_DIPROSES => 5,
            self::STATUS_REVISI_PREVIEW_SIAP => 6,
            self::STATUS_REVISI_SELESAI => 7,
            self::STATUS_MENUNGGU_PELUNASAN => 8,
            self::STATUS_MENUNGGU_FILE_FINAL => 9,
            self::STATUS_SELESAI => 10,
        ];

        return $order[$status] ?? 0;
    }

    // Revisi related methods
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

    // Preview methods
    public function isPreviewActive()
    {
        if (!$this->preview_link || !$this->is_preview_active) {
            return false;
        }

        if (!$this->preview_expired_at) {
            return true;
        }

        return Carbon::parse($this->preview_expired_at)->isFuture();
    }

    public function checkAndUpdatePreviewExpiry()
    {
        if ($this->preview_expired_at && Carbon::parse($this->preview_expired_at)->isPast()) {
            $this->update(['is_preview_active' => false]);
            return false;
        }
        return $this->is_preview_active;
    }

    public function resetPreviewExpiry($days = 7)
    {
        $this->update([
            'preview_expired_at' => Carbon::now()->addDays($days),
            'is_preview_active' => true,
        ]);
    }

    // Cek apakah bisa request revisi
    public function canRequestRevisi()
    {
        // Harus sudah bayar DP
        $invoiceDP = $this->invoices()->where('tipe', 'DP')->first();
        $dpLunas = $invoiceDP && $invoiceDP->status === 'Lunas';

        // Cek apakah ada revisi aktif
        $hasActiveRevisi = $this->revisi()
            ->whereIn('status', ['Diminta', 'Sedang Dikerjakan'])
            ->exists();
        
        // Status yang membolehkan revisi
        $allowedStatuses = [
            self::STATUS_PREVIEW_SIAP,
            self::STATUS_REVISI_PREVIEW_SIAP,
            self::STATUS_REVISI_SELESAI,
            self::STATUS_MENUNGGU_PELUNASAN
        ];
        
        return in_array($this->status, $allowedStatuses)
            && $dpLunas
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

    // File methods
    public function hasFiles($type = 'final')
    {
        $field = $type === 'final' ? 'file_final' : 'file_pendukung';
        return !empty($this->$field) && is_array($this->$field);
    }

    public function getFileCount($type = 'final')
    {
        return $this->hasFiles($type) ? count($this->{$type === 'final' ? 'file_final' : 'file_pendukung'}) : 0;
    }

    // Cek pembayaran
    public function isDPPaid()
    {
        $invoiceDP = $this->invoices()->where('tipe', 'DP')->first();
        return $invoiceDP && $invoiceDP->status === 'Lunas';
    }

    public function isPelunasanPaid()
    {
        $invoicePelunasan = $this->invoices()->where('tipe', 'Pelunasan')->first();
        return $invoicePelunasan && $invoicePelunasan->status === 'Lunas';
    }
}