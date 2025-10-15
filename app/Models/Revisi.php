<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Revisi extends Model
{
    use HasFactory;

    protected $table = 'revisi';

    protected $fillable = [
        'pesanan_id',
        'revisi_ke',
        'catatan_revisi',
        'file_referensi',
        'status',
        'tanggal_selesai',
    ];

    protected $casts = [
        'file_referensi' => 'array',
        'revisi_ke' => 'integer',
        'tanggal_selesai' => 'datetime',
    ];

    // Relasi
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    public function scopeDikerjakan($query)
    {
        return $query->where('status', 'Dikerjakan');
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', 'Selesai');
    }

    // Helper Methods
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'Pending' => 'bg-yellow-100 text-yellow-800',
            'Dikerjakan' => 'bg-blue-100 text-blue-800',
            'Selesai' => 'bg-green-100 text-green-800',
            'Ditolak' => 'bg-red-100 text-red-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('d M Y H:i');
    }

    public function getFormattedTanggalSelesaiAttribute()
    {
        return $this->tanggal_selesai ? $this->tanggal_selesai->format('d M Y H:i') : '-';
    }

    // Check if has files
    public function hasFiles()
    {
        return !empty($this->file_referensi) && is_array($this->file_referensi);
    }

    // Get total files count
    public function getFileCountAttribute()
    {
        return $this->hasFiles() ? count($this->file_referensi) : 0;
    }

    // Boot method untuk auto increment revisi_ke
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($revisi) {
            if (empty($revisi->revisi_ke)) {
                $lastRevisi = self::where('pesanan_id', $revisi->pesanan_id)
                    ->max('revisi_ke');
                $revisi->revisi_ke = $lastRevisi ? $lastRevisi + 1 : 1;
            }
        });
    }
}