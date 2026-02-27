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
        // id_hewan sudah ada di create pendaftaran table, skip
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Skip
    }
};
