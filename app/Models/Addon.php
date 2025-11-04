<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    use HasFactory;

    protected $table = 'addons';

    protected $fillable = [
        'layanan_id',
        'nama_addon',
        'deskripsi',
        'harga',
        'is_active',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relasi ke Layanan
    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }

    // Relasi ke PesananAddon (TAMBAHKAN INI)
    public function pesananAddons()
    {
        return $this->hasMany(PesananAddon::class);
    }

    // Scope untuk addon aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}