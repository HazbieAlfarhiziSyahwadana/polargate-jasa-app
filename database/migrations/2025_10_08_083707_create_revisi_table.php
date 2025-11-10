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
            
            // Kolom untuk menyimpan file (gambar/dokumen)
            $table->json('file_referensi')->nullable()->comment('Array of file paths');
            
            // Kolom untuk menyimpan link referensi
            $table->json('link_referensi')->nullable()->comment('Array of URLs');
            
            // Metadata file untuk tracking
            $table->json('file_metadata')->nullable()->comment('File info: name, size, type, etc');
            
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