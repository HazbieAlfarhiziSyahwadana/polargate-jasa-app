<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_invoice',
        'pesanan_id',
        'tipe',
        'jumlah',
        'status',
        'tanggal_jatuh_tempo',
        'alasan_penolakan',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'tanggal_jatuh_tempo' => 'date',
    ];

    // Relasi
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class);
    }

    // Generate nomor invoice otomatis
    public static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            if (!$invoice->nomor_invoice) {
                $prefix = $invoice->tipe === 'DP' ? 'INV-DP-' : 'INV-PL-';
                $invoice->nomor_invoice = $prefix . date('Ymd') . '-' . str_pad(static::count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    // Accessor
    public function getIsLunasAttribute()
    {
        return $this->status === 'Lunas';
    }

    public function getIsJatuhTempoAttribute()
    {
        return now()->greaterThan($this->tanggal_jatuh_tempo) && $this->status !== 'Lunas';
    }
}