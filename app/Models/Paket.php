<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    use HasFactory;

    protected $table = 'paket';

    protected $fillable = [
        'layanan_id',
        'nama_paket',
        'deskripsi',
        'harga',
        'fitur',
        'durasi_pengerjaan',
        'jumlah_revisi',
        'is_active',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'is_active' => 'boolean',
        'fitur' => 'array', // Auto decode JSON ke array
    ];

    // Relasi
    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class);
    }
}