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
        Schema::create('device_network_access', function (Blueprint $table) {
            $table->id();
            $table->string('ip');
            $table->unsignedBigInteger('device_id');
            $table->unsignedBigInteger('network_id');
            $table->timestamp('accessed_at')->nullable();
            $table->timestamp('disconnected_at')->nullable();
            $table->timestamps();

            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');;
            $table->foreign('network_id')->references('id')->on('networks')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_network_access');
    }
};
