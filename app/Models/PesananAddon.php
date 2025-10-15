<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesananAddon extends Model
{
    use HasFactory;

    protected $table = 'pesanan_addons';

    protected $fillable = [
        'pesanan_id',
        'addon_id',
        'harga',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
    ];

    // Relasi
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function addon()
    {
        return $this->belongsTo(Addon::class);
    }
}