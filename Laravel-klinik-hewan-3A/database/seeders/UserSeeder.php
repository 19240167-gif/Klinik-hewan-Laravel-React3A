<?php
//tess
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin / Pemilik Klinik
        User::create([
            'name' => 'Admin Klinik',
            'email' => 'admin@klinkhewan.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        // Pegawai
        User::create([
            'name' => 'Pegawai Klinik',
            'email' => 'pegawai@klinkhewan.com',
            'password' => Hash::make('password'),
            'role' => 'pegawai'
        ]);

        // Dokter Hewan
        User::create([
            'name' => 'Dokter Hewan',
            'email' => 'dokter@klinkhewan.com',
            'password' => Hash::make('password'),
            'role' => 'dokter'
        ]);
    }
}
