<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('revisi', function (Blueprint $table) {
            if (!Schema::hasColumn('revisi', 'tanggal_selesai')) {
                $table->timestamp('tanggal_selesai')->nullable()->after('status');
            }
        });
    }

    public function down()
    {
        Schema::table('revisi', function (Blueprint $table) {
            $table->dropColumn('tanggal_selesai');
        });
    }
};