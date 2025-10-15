<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Superadmin
        User::create([
            'name' => 'Super Admin',
            'foto' => null, // Bisa tambahkan foto default jika ada
            'alamat' => 'Jakarta Pusat',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_lahir' => '1990-01-01',
            'usia' => 34,
            'no_telepon' => '081234567890',
            'email' => 'admin@polargate.com',
            'password' => Hash::make('admin123'),
            'role' => 'superadmin',
            'is_active' => true,
        ]);

        // Client Demo 1
        User::create([
            'name' => 'Hazbie Alfarhizi Syahwadana',
            'foto' => null,
            'alamat' => 'Jl. Sudirman No. 123, Jakarta Selatan',
            'jenis_kelamin' => 'Laki-laki',
            'tanggal_lahir' => '2002-01-13',
            'usia' => 23,
            'no_telepon' => '081291568820',
            'email' => 'hazbiealfarhizi@email.com',
            'password' => Hash::make('hazbie123'),
            'role' => 'client',
            'is_active' => true,
        ]);

        // Client Demo 2
        User::create([
            'name' => 'Siti Nurhaliza',
            'foto' => null,
            'alamat' => 'Jl. Gatot Subroto No. 45, Bandung',
            'jenis_kelamin' => 'Perempuan',
            'tanggal_lahir' => '1998-08-20',
            'usia' => 26,
            'no_telepon' => '082134567890',
            'email' => 'siti@email.com',
            'password' => Hash::make('client123'),
            'role' => 'client',
            'is_active' => true,
        ]);
    }
}