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

    // Accessor untuk URL gambar
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
        
        // Jika ada video URL, return YouTube thumbnail
        if ($this->video_url && $this->youtube_thumbnail) {
            return $this->youtube_thumbnail;
        }
        
        return asset('images/default-service.png');
    }

    // Accessor untuk mengecek apakah memiliki video
    public function getHasVideoAttribute()
    {
        return !empty($this->video_url);
    }

    // Accessor untuk mendapatkan YouTube ID dari URL
    public function getYoutubeIdAttribute()
    {
        if (!$this->video_url) {
            return null;
        }

        $patterns = [
            '/youtube\.com\/watch\?v=([^&]+)/',
            '/youtube\.com\/embed\/([^&]+)/',
            '/youtube\.com\/v\/([^&]+)/',
            '/youtu\.be\/([^&]+)/',
            '/youtube\.com\/verify_age\?next_url=\/watch%3Fv%3D([^&]+)/'
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
            // Coba berbagai kualitas thumbnail
            $qualities = [
                "https://img.youtube.com/vi/{$youtubeId}/maxresdefault.jpg",
                "https://img.youtube.com/vi/{$youtubeId}/sddefault.jpg",
                "https://img.youtube.com/vi/{$youtubeId}/hqdefault.jpg",
                "https://img.youtube.com/vi/{$youtubeId}/mqdefault.jpg",
                "https://img.youtube.com/vi/{$youtubeId}/default.jpg"
            ];
            
            // Return yang pertama tersedia (maxresdefault biasanya terbaik)
            return $qualities[0];
        }
        
        return null;
    }

    // Method untuk mendapatkan embed URL YouTube
    public function getYoutubeEmbedUrlAttribute()
    {
        if ($youtubeId = $this->youtube_id) {
            return "https://www.youtube.com/embed/{$youtubeId}?rel=0&showinfo=0";
        }
        
        // Jika bukan YouTube, return URL asli (untuk video lain)
        return $this->video_url;
    }

    // Method untuk mengecek apakah URL adalah YouTube
    public function getIsYoutubeVideoAttribute()
    {
        if (!$this->video_url) {
            return false;
        }
        
        $youtubeDomains = [
            'youtube.com',
            'www.youtube.com',
            'm.youtube.com',
            'youtu.be',
            'www.youtu.be'
        ];
        
        $host = parse_url($this->video_url, PHP_URL_HOST);
        
        foreach ($youtubeDomains as $domain) {
            if (str_contains($host, $domain)) {
                return true;
            }
        }
        
        return false;
    }

    // Method untuk mendapatkan tipe media
    public function getMediaTypeAttribute()
    {
        if ($this->video_url) {
            return 'video';
        }
        if ($this->gambar) {
            return 'image';
        }
        return 'none';
    }

    // Method untuk mendapatkan media URL utama (prioritas video)
    public function getMediaUrlAttribute()
    {
        if ($this->video_url) {
            return $this->youtube_embed_url ?? $this->video_url;
        }
        if ($this->gambar) {
            return $this->gambar_url;
        }
        return null;
    }

    // Method untuk mendapatkan thumbnail yang optimal
    public function getOptimalThumbnailAttribute()
    {
        if ($this->video_url && $this->is_youtube_video) {
            return $this->youtube_thumbnail;
        }
        if ($this->gambar) {
            return $this->gambar_url;
        }
        return asset('images/default-service.png');
    }

    // Method untuk cek apakah media tersedia
    public function getHasMediaAttribute()
    {
        return $this->video_url || $this->gambar;
    }

    // Method untuk mendapatkan icon berdasarkan tipe media
    public function getMediaIconAttribute()
    {
        if ($this->video_url) {
            return 'fas fa-video';
        }
        if ($this->gambar) {
            return 'fas fa-image';
        }
        return 'fas fa-file';
    }

    // Method untuk mendapatkan label media
    public function getMediaLabelAttribute()
    {
        if ($this->video_url) {
            return $this->is_youtube_video ? 'Video YouTube' : 'Video';
        }
        if ($this->gambar) {
            return 'Gambar';
        }
        return 'Tidak ada media';
    }

    // Override delete method untuk handle related data
    public function delete()
    {
        // Hapus semua paket terkait
        $this->paket()->delete();
        
        // Hapus semua addons terkait
        $this->addons()->delete();
        
        // Hapus file gambar jika ada
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