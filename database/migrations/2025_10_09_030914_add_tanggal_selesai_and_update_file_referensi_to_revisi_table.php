<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('revisi', function (Blueprint $table) {
            // Ubah file_referensi ke json jika masih text
            if (Schema::hasColumn('revisi', 'file_referensi')) {
                $table->json('file_referensi')->nullable()->change();
            }
            
            // Tambah tanggal_selesai jika belum ada
            if (!Schema::hasColumn('revisi', 'tanggal_selesai')) {
                $table->timestamp('tanggal_selesai')->nullable()->after('status');
            }
            
            // Tambah index
            if (!Schema::hasIndex('revisi', 'revisi_pesanan_id_index')) {
                $table->index('pesanan_id');
            }
            if (!Schema::hasIndex('revisi', 'revisi_status_index')) {
                $table->index('status');
            }
        });
    }

    public function down()
    {
        Schema::table('revisi', function (Blueprint $table) {
            $table->dropColumn('tanggal_selesai');
            $table->dropIndex('revisi_pesanan_id_index');
            $table->dropIndex('revisi_status_index');
        });
    }
};