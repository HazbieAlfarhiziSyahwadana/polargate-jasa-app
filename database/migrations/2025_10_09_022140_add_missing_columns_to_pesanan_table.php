<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pesanan', function (Blueprint $table) {
            // Cek kolom mana yang belum ada, tambahkan di sini
            if (!Schema::hasColumn('pesanan', 'harga_paket')) {
                $table->decimal('harga_paket', 15, 2)->after('paket_id')->nullable();
            }
            if (!Schema::hasColumn('pesanan', 'preview_link')) {
                $table->string('preview_link')->nullable()->after('status');
            }
            if (!Schema::hasColumn('pesanan', 'is_preview_active')) {
                $table->boolean('is_preview_active')->default(false)->after('preview_link');
            }
            if (!Schema::hasColumn('pesanan', 'file_final')) {
                $table->string('file_final')->nullable()->after('is_preview_active');
            }
            if (!Schema::hasColumn('pesanan', 'file_pendukung')) {
                $table->text('file_pendukung')->nullable()->after('file_final');
            }
            if (!Schema::hasColumn('pesanan', 'catatan_revisi')) {
                $table->text('catatan_revisi')->nullable()->after('file_pendukung');
            }
        });
    }

    public function down()
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn([
                'harga_paket',
                'preview_link',
                'is_preview_active',
                'file_final',
                'file_pendukung',
                'catatan_revisi'
            ]);
        });
    }
};