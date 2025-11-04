<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;

    protected $table = 'layanan';

    protected $fillable = [
        'nama_layanan',
        'kategori',
        'deskripsi',
        'harga_mulai',
        'gambar',
        'is_active',
    ];

    protected $casts = [
        'harga_mulai' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relasi
    public function paket()
    {
        return $this->hasMany(Paket::class);
    }

    public function addons()
    {
        return $this->hasMany(Addon::class);
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class);
    }

    // Scope untuk layanan aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessor untuk URL gambar
    public function getGambarUrlAttribute()
    {
        if ($this->gambar) {
            return asset('uploads/layanan/' . $this->gambar);
        }
        return asset('images/default-service.png');
    }
}