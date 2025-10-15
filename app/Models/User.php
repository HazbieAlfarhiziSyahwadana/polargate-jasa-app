<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;
use App\Helpers\FileHelper;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'foto', // TAMBAHKAN INI
        'alamat',
        'jenis_kelamin',
        'tanggal_lahir',
        'usia',
        'no_telepon',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'tanggal_lahir' => 'date',
        'is_active' => 'boolean',
    ];

    // TAMBAHKAN ACCESSOR UNTUK FOTO
    public function getFotoUrlAttribute()
    {
        if ($this->foto) {
            return asset('uploads/users/' . $this->foto);
        }
        // Default avatar jika tidak ada foto
        return asset('images/default-avatar.png');
    }

    // Relasi
    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'client_id');
    }

    public function pembayaranVerified()
    {
        return $this->hasMany(Pembayaran::class, 'verified_by');
    }

    // Accessor
    public function getIsAdminAttribute()
    {
        return $this->role === 'superadmin';
    }

    public function getIsClientAttribute()
    {
        return $this->role === 'client';
    }

    // Hitung usia otomatis
    public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if ($user->tanggal_lahir) {
                $user->usia = Carbon::parse($user->tanggal_lahir)->age;
            }
        });

        static::updating(function ($user) {
            if ($user->tanggal_lahir) {
                $user->usia = Carbon::parse($user->tanggal_lahir)->age;
            }

            if ($user->isDirty('foto') && $user->getOriginal('foto')) {
                FileHelper::deleteFile('users', $user->getOriginal('foto'));
            }
        });

        static::deleting(function ($user) {
            if ($user->foto) {
                FileHelper::deleteFile('users', $user->foto);
            }
        });
    }

}
