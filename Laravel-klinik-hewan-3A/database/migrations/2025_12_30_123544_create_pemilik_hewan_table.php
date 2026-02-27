<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePemilikHewanTable extends Migration
{
    public function up(): void
    {
        Schema::create('pemilik_hewan', function (Blueprint $table) {
            $table->string('id_pemilik', 10)->primary();
            $table->string('nama_pemilik', 25)->nullable();
            $table->string('no_tlp', 15)->nullable();
            $table->text('alamat')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemilik_hewan');
    }
}
