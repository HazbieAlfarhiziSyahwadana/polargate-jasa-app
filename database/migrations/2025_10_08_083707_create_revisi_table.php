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
            
            // Kolom untuk menyimpan file referensi dari client
            $table->json('file_referensi')->nullable()->comment('Array of file paths from client');
            
            // Kolom untuk menyimpan link referensi
            $table->json('link_referensi')->nullable()->comment('Array of URLs');
            
            // Metadata file untuk tracking
            $table->json('file_metadata')->nullable()->comment('File info: name, size, type, etc');
            
            // ✅ KOLOM BARU: File hasil revisi dari admin
            $table->json('file_hasil')->nullable()->comment('Array of result file paths from admin');
            $table->json('file_hasil_metadata')->nullable()->comment('Result file info: name, size, type, etc');
            $table->text('catatan_admin')->nullable()->comment('Admin notes when completing revision');
            
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