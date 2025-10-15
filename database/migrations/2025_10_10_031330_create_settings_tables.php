<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('settings')->insert([
            ['key' => 'logo', 'value' => null, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'whatsapp_number', 'value' => '6281234567890', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'animasi_3d_image', 'value' => null, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'visual_effect_image', 'value' => null, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'company_profile_image', 'value' => null, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'tvc_image', 'value' => null, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'web_developer_image', 'value' => null, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'apps_developer_image', 'value' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};