<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Skip this migration if running fresh - tables are already created with correct structure
        // This migration is for modifying existing database only
        
        // Check if we need to modify (if pemilik_hewan id_pemilik is still integer)
        $pemilikColumn = DB::select("SHOW COLUMNS FROM pemilik_hewan WHERE Field = 'id_pemilik'");
        if (empty($pemilikColumn)) {
            // Column doesn't exist yet or already modified, skip
            return;
        }
        
        $columnType = $pemilikColumn[0]->Type;
        if (str_contains($columnType, 'varchar') || str_contains($columnType, 'char')) {
            // Already modified
            return;
        }

        // Modify Pemilik Hewan - PMH001
        Schema::table('pemilik_hewan', function (Blueprint $table) {
            $table->dropPrimary();
        });
        DB::statement('ALTER TABLE pemilik_hewan MODIFY id_pemilik VARCHAR(10) NOT NULL');
        Schema::table('pemilik_hewan', function (Blueprint $table) {
            $table->primary('id_pemilik');
        });

        // Modify Hewan - HWN001
        Schema::table('hewan', function (Blueprint $table) {
            $table->dropPrimary();
        });
        DB::statement('ALTER TABLE hewan MODIFY id_hewan VARCHAR(10) NOT NULL');
        DB::statement('ALTER TABLE hewan MODIFY id_pemilik VARCHAR(10) NOT NULL');
        Schema::table('hewan', function (Blueprint $table) {
            $table->primary('id_hewan');
        });

        // Modify Pegawai - PGW001
        Schema::table('pegawai', function (Blueprint $table) {
            $table->dropPrimary();
        });
        DB::statement('ALTER TABLE pegawai MODIFY id_pegawai VARCHAR(10) NOT NULL');
        Schema::table('pegawai', function (Blueprint $table) {
            $table->primary('id_pegawai');
        });

        // Modify Pendaftaran - DFT001
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropPrimary();
        });
        DB::statement('ALTER TABLE pendaftaran MODIFY id_pendaftaran VARCHAR(10) NOT NULL');
        DB::statement('ALTER TABLE pendaftaran MODIFY id_hewan VARCHAR(10) NOT NULL');
        DB::statement('ALTER TABLE pendaftaran MODIFY id_pegawai VARCHAR(10) NOT NULL');
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->primary('id_pendaftaran');
        });

        // Modify Dokter Hewan - DKT001
        Schema::table('dokter_hewan', function (Blueprint $table) {
            $table->dropPrimary();
        });
        DB::statement('ALTER TABLE dokter_hewan MODIFY id_dokter VARCHAR(10) NOT NULL');
        Schema::table('dokter_hewan', function (Blueprint $table) {
            $table->primary('id_dokter');
        });

        // Modify Obat - OBT001
        Schema::table('obat', function (Blueprint $table) {
            $table->dropPrimary();
        });
        DB::statement('ALTER TABLE obat MODIFY id_obat VARCHAR(10) NOT NULL');
        Schema::table('obat', function (Blueprint $table) {
            $table->primary('id_obat');
        });

        // Modify Pemeriksaan - PMR001
        Schema::table('pemeriksaan', function (Blueprint $table) {
            $table->dropPrimary();
        });
        DB::statement('ALTER TABLE pemeriksaan MODIFY id_pemeriksaan VARCHAR(10) NOT NULL');
        DB::statement('ALTER TABLE pemeriksaan MODIFY id_pendaftaran VARCHAR(10) NOT NULL');
        DB::statement('ALTER TABLE pemeriksaan MODIFY id_dokter VARCHAR(10) NOT NULL');
        Schema::table('pemeriksaan', function (Blueprint $table) {
            $table->primary('id_pemeriksaan');
        });

        // Modify Pemeriksaan Obat (pivot table)
        DB::statement('ALTER TABLE pemeriksaan_obat MODIFY id_pemeriksaan VARCHAR(10) NOT NULL');
        DB::statement('ALTER TABLE pemeriksaan_obat MODIFY id_obat VARCHAR(10) NOT NULL');

        // Modify Pembayaran - PBY001
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropPrimary();
        });
        DB::statement('ALTER TABLE pembayaran MODIFY id_pembayaran VARCHAR(10) NOT NULL');
        DB::statement('ALTER TABLE pembayaran MODIFY id_pemeriksaan VARCHAR(10) NOT NULL');
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->primary('id_pembayaran');
        });

        // Modify Detail Pembayaran Obat - DPO001
        Schema::table('detail_pembayaran_obat', function (Blueprint $table) {
            $table->dropPrimary();
        });
        DB::statement('ALTER TABLE detail_pembayaran_obat MODIFY id_detail VARCHAR(10) NOT NULL');
        DB::statement('ALTER TABLE detail_pembayaran_obat MODIFY id_pembayaran VARCHAR(10) NOT NULL');
        DB::statement('ALTER TABLE detail_pembayaran_obat MODIFY id_obat VARCHAR(10) NOT NULL');
        Schema::table('detail_pembayaran_obat', function (Blueprint $table) {
            $table->primary('id_detail');
        });

        // Recreate foreign keys
        Schema::table('hewan', function (Blueprint $table) {
            $table->foreign('id_pemilik')->references('id_pemilik')->on('pemilik_hewan')->onDelete('cascade');
        });

        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->foreign('id_hewan')->references('id_hewan')->on('hewan')->onDelete('cascade');
            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawai')->onDelete('cascade');
        });

        Schema::table('pemeriksaan', function (Blueprint $table) {
            $table->foreign('id_pendaftaran')->references('id_pendaftaran')->on('pendaftaran')->onDelete('cascade');
            $table->foreign('id_dokter')->references('id_dokter')->on('dokter_hewan')->onDelete('cascade');
        });

        Schema::table('pemeriksaan_obat', function (Blueprint $table) {
            $table->foreign('id_pemeriksaan')->references('id_pemeriksaan')->on('pemeriksaan')->onDelete('cascade');
            $table->foreign('id_obat')->references('id_obat')->on('obat')->onDelete('cascade');
        });

        Schema::table('pembayaran', function (Blueprint $table) {
            $table->foreign('id_pemeriksaan')->references('id_pemeriksaan')->on('pemeriksaan')->onDelete('cascade');
        });

        Schema::table('detail_pembayaran_obat', function (Blueprint $table) {
            $table->foreign('id_pembayaran')->references('id_pembayaran')->on('pembayaran')->onDelete('cascade');
            $table->foreign('id_obat')->references('id_obat')->on('obat')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign keys
        Schema::table('hewan', function (Blueprint $table) {
            $table->dropForeign(['id_pemilik']);
        });

        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropForeign(['id_hewan']);
            $table->dropForeign(['id_pegawai']);
        });

        Schema::table('pemeriksaan', function (Blueprint $table) {
            $table->dropForeign(['id_pendaftaran']);
            $table->dropForeign(['id_dokter']);
        });

        Schema::table('pemeriksaan_obat', function (Blueprint $table) {
            $table->dropForeign(['id_pemeriksaan']);
            $table->dropForeign(['id_obat']);
        });

        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropForeign(['id_pemeriksaan']);
        });

        Schema::table('detail_pembayaran_obat', function (Blueprint $table) {
            $table->dropForeign(['id_pembayaran']);
            $table->dropForeign(['id_obat']);
        });

        // Revert back
        Schema::table('pemilik_hewan', function (Blueprint $table) {
            $table->dropPrimary();
        });
        DB::statement('ALTER TABLE pemilik_hewan MODIFY id_pemilik BIGINT UNSIGNED AUTO_INCREMENT');
        Schema::table('pemilik_hewan', function (Blueprint $table) {
            $table->primary('id_pemilik');
        });

        // Similar revert for other tables...
        // (Implementation similar to up but reversed)
    }
};
