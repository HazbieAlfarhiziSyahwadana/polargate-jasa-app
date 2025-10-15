<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            // Tambah kolom jika belum ada
            if (!Schema::hasColumn('pembayaran', 'jumlah_dibayar')) {
                $table->decimal('jumlah_dibayar', 15, 2)->after('invoice_id');
            }
            
            if (!Schema::hasColumn('pembayaran', 'catatan_verifikasi')) {
                $table->text('catatan_verifikasi')->nullable()->after('bukti_pembayaran');
            }

            // Hapus kolom lama jika ada
            if (Schema::hasColumn('pembayaran', 'jumlah')) {
                $table->dropColumn('jumlah');
            }
            
            if (Schema::hasColumn('pembayaran', 'catatan')) {
                $table->dropColumn('catatan');
            }
        });
    }

    public function down()
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropColumn(['jumlah_dibayar', 'catatan_verifikasi']);
        });
    }
};