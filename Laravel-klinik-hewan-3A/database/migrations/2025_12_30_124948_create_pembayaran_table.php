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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->string('id_pembayaran', 10)->primary();
            $table->string('id_pemeriksaan', 10)->nullable();
            $table->date('tanggal_bayar')->nullable();
            $table->string('metode_bayar', 10)->nullable();
            $table->integer('total_bayar')->nullable();
            $table->timestamps();
            
            $table->foreign('id_pemeriksaan')->references('id_pemeriksaan')->on('pemeriksaan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
