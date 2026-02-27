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
        Schema::table('pemilik_hewan', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id_pemilik')->constrained('users')->onDelete('cascade');
            $table->enum('jenis_pendaftaran', ['online', 'offline'])->default('offline')->after('alamat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemilik_hewan', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'jenis_pendaftaran']);
        });
    }
};
