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
    public function scopeDiminta($query)
    {
        return $query->where('status', 'Diminta');
    }

    public function scopeSedangDikerjakan($query)
    {
        return $query->where('status', 'Sedang Dikerjakan');
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', 'Selesai');
    }

    public function scopeDitolak($query)
    {
        return $query->where('status', 'Ditolak');
    }

    // Scope untuk filter by user
    public function scopeByUser($query, $userId)
    {
        return $query->whereHas('pesanan', function($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }

    // Helper Methods
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'Diminta' => 'bg-yellow-100 text-yellow-800 border border-yellow-300',
            'Sedang Dikerjakan' => 'bg-blue-100 text-blue-800 border border-blue-300',
            'Selesai' => 'bg-green-100 text-green-800 border border-green-300',
            'Ditolak' => 'bg-red-100 text-red-800 border border-red-300',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800 border border-gray-300';
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

    // Get file extension
    public function getFileExtension($index)
    {
        if (!$this->hasFiles() || !isset($this->file_referensi[$index])) {
            return null;
        }

        return pathinfo($this->file_referensi[$index], PATHINFO_EXTENSION);
    }

    // Get file name
    public function getFileName($index)
    {
        if (!$this->hasFiles() || !isset($this->file_referensi[$index])) {
            return null;
        }

        return basename($this->file_referensi[$index]);
    }

    // Check if revisi can be edited
    public function canBeEdited()
    {
        return $this->status === 'Diminta';
    }

    // Check if revisi is completed
    public function isCompleted()
    {
        return $this->status === 'Selesai';
    }

    // Check if revisi is in progress
    public function isInProgress()
    {
        return $this->status === 'Sedang Dikerjakan';
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

            // Set status default jika belum ada
            if (empty($revisi->status)) {
                $revisi->status = 'Diminta';
            }
        });

        static::updated(function ($revisi) {
            // Auto set tanggal selesai ketika status berubah menjadi Selesai
            if ($revisi->status === 'Selesai' && empty($revisi->tanggal_selesai)) {
                $revisi->tanggal_selesai = now();
                $revisi->saveQuietly();
            }
        });
    }
}