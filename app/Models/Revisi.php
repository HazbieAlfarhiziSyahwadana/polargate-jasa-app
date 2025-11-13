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
        'file_hasil',
        'file_hasil_metadata',
        'catatan_admin',
        'status',
        'tanggal_selesai',
         'preview_link', // ✅ TAMBAH
        'catatan_hasil', // ✅ TAMBAH
        'preview_expired_at', // ✅ TAMBAH
        'is_preview_active', // ✅ TAMBAH
    ];

    protected $casts = [
        'file_referensi' => 'array',
        'file_hasil' => 'array',
        'file_hasil_metadata' => 'array',
        'revisi_ke' => 'integer',
        'tanggal_selesai' => 'datetime',
        'preview_expired_at' => 'datetime',
        'is_preview_active' => 'boolean',
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

    public function scopeByUser($query, $userId)
    {
        return $query->whereHas('pesanan', function($q) use ($userId) {
            // ✅ PERBAIKAN: Deteksi otomatis kolom user
            $column = \Schema::hasColumn('pesanan', 'client_id') ? 'client_id' : 'user_id';
            $q->where($column, $userId);
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

    // Check if has files (referensi dari client)
    public function hasFiles()
    {
        return !empty($this->file_referensi) && is_array($this->file_referensi);
    }

    // Check if has result files
    public function hasFileHasil()
    {
        return !empty($this->file_hasil) && is_array($this->file_hasil);
    }

    // ✅ TAMBAHAN: Check if has link preview
    public function hasLinkPreview()
    {
        return !empty($this->catatan_admin) && filter_var($this->catatan_admin, FILTER_VALIDATE_URL);
    }

    // Get total files count
    public function getFileCountAttribute()
    {
        return $this->hasFiles() ? count($this->file_referensi) : 0;
    }

    // Get total result files count
    public function getFileHasilCountAttribute()
    {
        return $this->hasFileHasil() ? count($this->file_hasil) : 0;
    }

    // Get file extension
    public function getFileExtension($index)
    {
        if (!$this->hasFiles() || !isset($this->file_referensi[$index])) {
            return null;
        }

        return pathinfo($this->file_referensi[$index], PATHINFO_EXTENSION);
    }

    // Get result file extension
    public function getFileHasilExtension($index)
    {
        if (!$this->hasFileHasil() || !isset($this->file_hasil[$index])) {
            return null;
        }

        return pathinfo($this->file_hasil[$index], PATHINFO_EXTENSION);
    }

    // Get file name
    public function getFileName($index)
    {
        if (!$this->hasFiles() || !isset($this->file_referensi[$index])) {
            return null;
        }

        return basename($this->file_referensi[$index]);
    }

    // Get result file name
    public function getFileHasilName($index)
    {
        if (!$this->hasFileHasil() || !isset($this->file_hasil[$index])) {
            return null;
        }

        return basename($this->file_hasil[$index]);
    }

    // ✅ TAMBAHAN: Get link domain untuk display
    public function getLinkDomain()
    {
        if (!$this->hasLinkPreview()) {
            return null;
        }
        
        $parsedUrl = parse_url($this->catatan_admin);
        $host = $parsedUrl['host'] ?? null;
        
        if ($host) {
            // Hapus 'www.' jika ada
            $host = preg_replace('/^www\./', '', $host);
        }
        
        return $host;
    }

    // ✅ TAMBAHAN: Get catatan hasil dari metadata
    public function getCatatanHasil()
    {
        if (empty($this->file_hasil_metadata) || !is_array($this->file_hasil_metadata)) {
            return null;
        }
        
        return $this->file_hasil_metadata['catatan_hasil'] ?? null;
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

    // ✅ TAMBAHAN: Check if revisi is ready for completion (ada link preview)
    public function isReadyForCompletion()
    {
        return $this->hasLinkPreview();
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

            if (empty($revisi->status)) {
                $revisi->status = 'Diminta';
            }
        });

        static::updated(function ($revisi) {
            if ($revisi->status === 'Selesai' && empty($revisi->tanggal_selesai)) {
                $revisi->tanggal_selesai = now();
                $revisi->saveQuietly();
            }
        });
    }
}