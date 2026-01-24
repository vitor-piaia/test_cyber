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
        Schema::table('device_network_access', function (Blueprint $table) {
            $table->unique([
                'device_id',
                'ip'
            ], 'uniq_device_ip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('device_network_access', function (Blueprint $table) {
            $table->dropUnique('uniq_device_ip');
        });
    }
};
