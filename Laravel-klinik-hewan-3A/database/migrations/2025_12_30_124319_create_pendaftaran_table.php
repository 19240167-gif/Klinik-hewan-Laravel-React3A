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
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->string('id_pendaftaran', 10)->primary();
            $table->string('id_hewan', 10)->nullable();
            $table->string('id_pegawai', 10)->nullable();
            $table->date('tanggal_daftar')->nullable();
            $table->string('status', 10)->nullable();
            $table->text('keluhan')->nullable();
            $table->timestamps();
            
            $table->foreign('id_hewan')->references('id_hewan')->on('hewan');
            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};
