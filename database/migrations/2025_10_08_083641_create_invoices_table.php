<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_invoice')->unique();
            $table->foreignId('pesanan_id')->constrained('pesanan')->onDelete('cascade');
            $table->enum('tipe', ['DP', 'Pelunasan']);
            $table->decimal('jumlah', 15, 2);
            $table->enum('status', ['Belum Dibayar', 'Menunggu Verifikasi', 'Lunas'])->default('Belum Dibayar');
            $table->date('tanggal_jatuh_tempo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};