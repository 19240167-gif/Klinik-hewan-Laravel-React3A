<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\PemilikHewan;
use App\Models\Hewan;
use App\Models\Pegawai;
use App\Models\DokterHewan;
use App\Models\Obat;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@klinik.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Pegawai User
        $pegawaiUser = User::create([
            'name' => 'Pegawai Klinik',
            'email' => 'pegawai@klinik.com',
            'password' => Hash::make('password'),
            'role' => 'pegawai',
        ]);

        // Create Pegawai record
        Pegawai::create([
            'nama_pegawai' => 'Pegawai Klinik',
            'jenis_kelamin' => 'laki-laki',
            'no_telepon_pegawai' => '081234567890',
        ]);

        // Create Dokter User & Record
        $dokterUser = User::create([
            'name' => 'Dr. Ahmad',
            'email' => 'dokter@klinik.com',
            'password' => Hash::make('password'),
            'role' => 'dokter',
        ]);

        DokterHewan::create([
            'nama_dokter' => 'Dr. Ahmad',
            'no_sip' => 'SIP-DRH-2024-001',
            'biaya_periksa' => 50000,
        ]);

        DokterHewan::create([
            'nama_dokter' => 'Dr. Budi',
            'no_sip' => 'SIP-DRH-2024-002',
            'biaya_periksa' => 75000,
        ]);

        // Create Pemilik Hewan
        $pemilik1 = PemilikHewan::create([
            'nama_pemilik' => 'Andi Wijaya',
            'no_tlp' => '08123456789',
            'alamat' => 'Jl. Merdeka No. 10, Jakarta',
            'jenis_pendaftaran' => 'offline',
        ]);

        $pemilik2 = PemilikHewan::create([
            'nama_pemilik' => 'Siti Rahayu',
            'no_tlp' => '08567891234',
            'alamat' => 'Jl. Sudirman No. 25, Bandung',
            'jenis_pendaftaran' => 'online',
        ]);

        $pemilik3 = PemilikHewan::create([
            'nama_pemilik' => 'Budi Santoso',
            'no_tlp' => '08998765432',
            'alamat' => 'Jl. Diponegoro No. 5, Surabaya',
            'jenis_pendaftaran' => 'offline',
        ]);

        // Create Hewan
        Hewan::create([
            'nama_hewan' => 'Mochi',
            'jenis_hewan' => 'Kucing',
            'jenis_kelamin' => 'betina',
            'umur' => 2,
            'id_pemilik' => $pemilik1->id_pemilik,
        ]);

        Hewan::create([
            'nama_hewan' => 'Blacky',
            'jenis_hewan' => 'Anjing',
            'jenis_kelamin' => 'jantan',
            'umur' => 3,
            'id_pemilik' => $pemilik1->id_pemilik,
        ]);

        Hewan::create([
            'nama_hewan' => 'Tweety',
            'jenis_hewan' => 'Burung',
            'jenis_kelamin' => 'jantan',
            'umur' => 1,
            'id_pemilik' => $pemilik2->id_pemilik,
        ]);

        Hewan::create([
            'nama_hewan' => 'Bruno',
            'jenis_hewan' => 'Anjing',
            'jenis_kelamin' => 'jantan',
            'umur' => 5,
            'id_pemilik' => $pemilik3->id_pemilik,
        ]);

        // Create Obat
        Obat::create([
            'nama_obat' => 'Antibiotik A',
            'jenis_obat' => 'Tablet',
            'harga_obat' => 15000,
            'stok' => 100,
        ]);

        Obat::create([
            'nama_obat' => 'Vitamin B',
            'jenis_obat' => 'Kapsul',
            'harga_obat' => 10000,
            'stok' => 200,
        ]);

        Obat::create([
            'nama_obat' => 'Obat Cacing',
            'jenis_obat' => 'Tablet',
            'harga_obat' => 25000,
            'stok' => 150,
        ]);

        Obat::create([
            'nama_obat' => 'Salep Kulit',
            'jenis_obat' => 'Tube',
            'harga_obat' => 35000,
            'stok' => 50,
        ]);

        Obat::create([
            'nama_obat' => 'Vaksin Rabies',
            'jenis_obat' => 'Ampul',
            'harga_obat' => 75000,
            'stok' => 30,
        ]);

        $this->command->info('Database seeded successfully with Custom IDs!');
        $this->command->info('');
        $this->command->info('Sample IDs generated:');
        $this->command->info('- Pemilik: PMH001, PMH002, PMH003');
        $this->command->info('- Hewan: HWN001, HWN002, HWN003, HWN004');
        $this->command->info('- Pegawai: PGW001');
        $this->command->info('- Dokter: DKT001, DKT002');
        $this->command->info('- Obat: OBT001, OBT002, ...');
        $this->command->info('');
        $this->command->info('Login credentials:');
        $this->command->info('Admin: admin@klinik.com / password');
        $this->command->info('Pegawai: pegawai@klinik.com / password');
        $this->command->info('Dokter: dokter@klinik.com / password');
    }
}
