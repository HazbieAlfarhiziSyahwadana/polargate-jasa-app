<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';

    protected $fillable = [
        'client_id',
        'layanan_id',
        'paket_id',
        'kode_pesanan',
        'brief', // ← PASTIKAN INI ADA
        'harga_paket',
        'total_harga',
        'status',
        'preview_link',
        'preview_expired_at',
        'is_preview_active',
        'file_final',
        'file_pendukung',
        'catatan_revisi',
    ];

    protected $casts = [
        'harga_paket' => 'decimal:2',
        'total_harga' => 'decimal:2',
        'is_preview_active' => 'boolean',
        'preview_expired_at' => 'datetime',
        'file_final' => 'array',
        'file_pendukung' => 'array',
    ];

    // Relasi
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
}

