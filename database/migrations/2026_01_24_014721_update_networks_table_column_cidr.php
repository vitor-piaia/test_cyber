<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE networks ALTER COLUMN cidr TYPE inet USING cidr::inet');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE networks ALTER COLUMN cidr TYPE varchar(255) USING cidr::varchar');
    }
};
