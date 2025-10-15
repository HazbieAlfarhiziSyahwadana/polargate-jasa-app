<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paket', function (Blueprint $table) {
            $table->id();
            $table->foreignId('layanan_id')->constrained('layanan')->onDelete('cascade');
            $table->string('nama_paket');
            $table->text('deskripsi');
            $table->decimal('harga', 15, 2);
            $table->text('fitur'); // JSON format
            $table->integer('durasi_pengerjaan'); // dalam hari
            $table->integer('jumlah_revisi')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paket');
    }
};