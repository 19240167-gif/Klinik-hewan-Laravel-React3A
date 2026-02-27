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
        Schema::create('pemeriksaan_obat', function (Blueprint $table) {
            $table->string('id_pemeriksaan', 10);
            $table->string('id_obat', 10);
            $table->integer('jumlah')->default(1);
            $table->timestamps();
            
            $table->primary(['id_pemeriksaan', 'id_obat']);
            $table->foreign('id_pemeriksaan')->references('id_pemeriksaan')->on('pemeriksaan')->onDelete('cascade');
            $table->foreign('id_obat')->references('id_obat')->on('obat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan_obat');
    }
};
