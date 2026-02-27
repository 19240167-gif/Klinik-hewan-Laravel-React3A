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
        Schema::create('pemeriksaan', function (Blueprint $table) {
            $table->string('id_pemeriksaan', 10)->primary();
            $table->string('id_pendaftaran', 10)->nullable();
            $table->string('id_dokter', 10)->nullable();
            $table->text('diagnosa')->nullable();
            $table->text('tindakan')->nullable();
            $table->date('tanggal_periksa')->nullable();
            $table->timestamps();
            
            $table->foreign('id_pendaftaran')->references('id_pendaftaran')->on('pendaftaran');
            $table->foreign('id_dokter')->references('id_dokter')->on('dokter_hewan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan');
    }
};
