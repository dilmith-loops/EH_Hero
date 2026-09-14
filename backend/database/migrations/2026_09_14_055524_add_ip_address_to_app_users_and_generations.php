<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('app_users', function (Blueprint $table) {
            $table->string('ip_address', 45)->nullable()->after('phone');
        });

        Schema::table('generations', function (Blueprint $table) {
            $table->string('ip_address', 45)->nullable()->after('generated_image_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_users', function (Blueprint $table) {
            $table->dropColumn('ip_address');
        });

        Schema::table('generations', function (Blueprint $table) {
            $table->dropColumn('ip_address');
        });
    }
};
