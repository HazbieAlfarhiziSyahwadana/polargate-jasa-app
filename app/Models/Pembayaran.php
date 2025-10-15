<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'invoice_id',
        'jumlah_dibayar',        // PENTING: harus ada
        'metode_pembayaran',
        'bukti_pembayaran',
        'status',
        'catatan_verifikasi',    // PENTING: harus ada
        'tanggal_verifikasi',
        'verified_by',
    ];

    protected $casts = [
        'jumlah_dibayar' => 'decimal:2',
        'tanggal_verifikasi' => 'datetime',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}