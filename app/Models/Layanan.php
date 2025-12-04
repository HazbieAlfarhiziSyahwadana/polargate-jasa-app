<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\FileHelper;

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
        'video_url',
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

    // Accessor untuk URL gambar - PRIORITAS GAMBAR
    public function getGambarUrlAttribute()
    {
        if ($this->gambar) {
            // Cek jika gambar adalah URL external
            if (filter_var($this->gambar, FILTER_VALIDATE_URL)) {
                return $this->gambar;
            }
            
            // Gunakan FileHelper untuk mendapatkan URL
            return FileHelper::getFileUrl('layanan', $this->gambar);
        }
        
        return asset('images/default-service.png');
    }

    // Accessor untuk mengecek apakah memiliki video
    public function getHasVideoAttribute()
    {
        return !empty($this->video_url);
    }

    // Accessor untuk mengecek apakah memiliki gambar
    public function getHasImageAttribute()
    {
        return !empty($this->gambar);
    }

    // Accessor untuk mendapatkan YouTube ID dari URL
    public function getYoutubeIdAttribute()
    {
        if (!$this->video_url) {
            return null;
        }

        $patterns = [
            '/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/|youtube\.com\/v\/|youtube\.com\/\?v=)([^&\n?#]+)/',
            '/youtube\.com\/watch\?.*v=([^&\n?#]+)/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $this->video_url, $matches)) {
                return $matches[1] ?? null;
            }
        }

        return null;
    }

    // Accessor untuk thumbnail YouTube
    public function getYoutubeThumbnailAttribute()
    {
        if ($youtubeId = $this->youtube_id) {
            return "https://img.youtube.com/vi/{$youtubeId}/maxresdefault.jpg";
        }
        
        return null;
    }

    // Method untuk mendapatkan embed URL YouTube
    public function getYoutubeEmbedUrlAttribute()
    {
        if ($youtubeId = $this->youtube_id) {
            return "https://www.youtube.com/embed/{$youtubeId}?rel=0&showinfo=0&autoplay=0";
        }
        
        return $this->video_url;
    }

    // Method untuk mengecek apakah URL adalah YouTube
    public function getIsYoutubeVideoAttribute()
    {
        if (!$this->video_url) {
            return false;
        }
        
        return (
            str_contains($this->video_url, 'youtube.com') || 
            str_contains($this->video_url, 'youtu.be')
        );
    }

    // Method untuk mendapatkan tipe media UTAMA (prioritas gambar)
    public function getMediaTypeAttribute()
    {
        if ($this->gambar) {
            return 'image';
        }
        if ($this->video_url) {
            return 'video';
        }
        return 'none';
    }

    // Override delete method untuk handle related data
    public function delete()
    {
        $this->paket()->delete();
        $this->addons()->delete();
        
        if ($this->gambar && !filter_var($this->gambar, FILTER_VALIDATE_URL)) {
            FileHelper::deleteFile('layanan', $this->gambar);
        }
        
        return parent::delete();
    }

    // Scope untuk mencari layanan
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('nama_layanan', 'like', "%{$search}%")
              ->orWhere('kategori', 'like', "%{$search}%")
              ->orWhere('deskripsi', 'like', "%{$search}%");
        });
    }

    // Scope untuk filter by kategori
    public function scopeByKategori($query, $kategori)
    {
        if ($kategori) {
            return $query->where('kategori', $kategori);
        }
        return $query;
    }

    // Scope untuk filter by status
    public function scopeByStatus($query, $status)
    {
        if ($status === 'aktif') {
            return $query->where('is_active', true);
        } elseif ($status === 'nonaktif') {
            return $query->where('is_active', false);
        }
        return $query;
    }
}