<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Hapus kolom yang typo/duplikat
        Schema::table('revisi', function (Blueprint $table) {
            // Hapus kolom typo
            if (Schema::hasColumn('revisi', 'link_basii')) {
                $table->dropColumn('link_basii');
            }
            if (Schema::hasColumn('revisi', 'file_basii')) {
                $table->dropColumn('file_basii');
            }
            if (Schema::hasColumn('revisi', 'catalan_basii')) {
                $table->dropColumn('catalan_basii');
            }
            if (Schema::hasColumn('revisi', 'preview_mix')) {
                $table->dropColumn('preview_mix');
            }
            
            // Tambah kolom yang benar jika belum ada
            if (!Schema::hasColumn('revisi', 'preview_link')) {
                $table->string('preview_link')->nullable()->after('file_hasil');
            }
            if (!Schema::hasColumn('revisi', 'catatan_hasil')) {
                $table->text('catatan_hasil')->nullable()->after('preview_link');
            }
            if (!Schema::hasColumn('revisi', 'preview_expired_at')) {
                $table->timestamp('preview_expired_at')->nullable()->after('catatan_hasil');
            }
            if (!Schema::hasColumn('revisi', 'is_preview_active')) {
                $table->boolean('is_preview_active')->default(false)->after('preview_expired_at');
            }
        });
    }

    public function down()
    {
        // Tidak perlu rollback untuk perbaikan structure
    }
};