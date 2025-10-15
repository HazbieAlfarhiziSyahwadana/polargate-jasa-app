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

    // Relasi
    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }

    public function pesanan()
    {
        return $this->belongsToMany(Pesanan::class, 'pesanan_addons')
            ->withPivot('harga')
            ->withTimestamps();
    }
}