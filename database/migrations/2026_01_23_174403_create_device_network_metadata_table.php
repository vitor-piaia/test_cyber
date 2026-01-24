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
        Schema::create('device_network_metadata', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('device_network_access_id');
            $table->string('isp');
            $table->json('domains');
            $table->json('hostnames');
            $table->json('geolocation');
            $table->json('ports');
            $table->timestamp('last_shodan_scan_at');
            $table->timestamps();

            $table->foreign('device_network_access_id')->references('id')->on('device_network_access');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_network_metadata');
    }
};
