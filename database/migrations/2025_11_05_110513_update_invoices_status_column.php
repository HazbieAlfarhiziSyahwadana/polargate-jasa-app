<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Ubah kolom status menjadi enum dengan semua kemungkinan nilai
            $table->enum('status', [
                'Belum Dibayar', 
                'Menunggu Verifikasi', 
                'Ditolak', 
                'Lunas'
            ])->default('Belum Dibayar')->change();
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->enum('status', [
                'Belum Dibayar', 
                'Menunggu Verifikasi', 
                'Lunas'
            ])->default('Belum Dibayar')->change();
        });
    }
};