<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('revisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->onDelete('cascade');
            $table->integer('revisi_ke')->default(1);
            $table->text('catatan_revisi');
            $table->json('file_referensi')->nullable();
            $table->enum('status', ['Diminta', 'Sedang Dikerjakan', 'Selesai'])->default('Diminta');
            $table->timestamp('tanggal_selesai')->nullable();
            $table->timestamps();

            // Index untuk performa
            $table->index('pesanan_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revisi');
    }
};