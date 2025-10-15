<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pesanan')->unique();
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('layanan_id')->constrained('layanan')->onDelete('cascade');
            $table->foreignId('paket_id')->nullable()->constrained('paket')->onDelete('set null');
            $table->text('brief');
            $table->text('referensi')->nullable(); // file paths JSON
            $table->decimal('total_harga', 15, 2);
            $table->enum('status', [
                'Menunggu Pembayaran DP',
                'DP Dibayar - Menunggu Verifikasi',
                'Sedang Diproses',
                'Preview Siap',
                'Revisi Diminta',
                'Menunggu Pelunasan',
                'Pelunasan Dibayar - Menunggu Verifikasi',
                'Selesai',
                'Dibatalkan'
            ])->default('Menunggu Pembayaran DP');
            $table->string('preview_link')->nullable();
            $table->timestamp('preview_expired_at')->nullable();
            $table->text('file_final')->nullable(); // file paths JSON
            $table->integer('jumlah_revisi_tersisa')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};