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
        Schema::create('detail_pembayaran_obat', function (Blueprint $table) {
            $table->string('id_detail', 10)->primary();
            $table->string('id_pembayaran', 10);
            $table->string('id_obat', 10);
            $table->integer('jumlah')->nullable();
            $table->integer('subtotal')->nullable();
            $table->timestamps();
            
            $table->foreign('id_pembayaran')->references('id_pembayaran')->on('pembayaran');
            $table->foreign('id_obat')->references('id_obat')->on('obat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pembayaran_obat');
    }
};
