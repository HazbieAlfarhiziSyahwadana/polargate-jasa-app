<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('revisi', function (Blueprint $table) {
            $table->string('preview_link')->nullable()->after('file_hasil');
            $table->text('catatan_hasil')->nullable()->after('preview_link');
            $table->timestamp('preview_expired_at')->nullable()->after('catatan_hasil');
            $table->boolean('is_preview_active')->default(false)->after('preview_expired_at');
        });
    }

    public function down()
    {
        Schema::table('revisi', function (Blueprint $table) {
            $table->dropColumn(['preview_link', 'catatan_hasil', 'preview_expired_at', 'is_preview_active']);
        });
    }
};