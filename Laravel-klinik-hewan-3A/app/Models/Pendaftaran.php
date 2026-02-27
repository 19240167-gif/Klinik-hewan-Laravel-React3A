<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\GeneratesCustomId;

class Pendaftaran extends Model
{
    use HasFactory, GeneratesCustomId;

    protected $table = 'pendaftaran';
    protected $primaryKey = 'id_pendaftaran';
    public $incrementing = false;
    protected $keyType = 'string';
    
    // Custom ID Configuration - DFT001
    protected $idPrefix = 'DFT';
    protected $idLength = 3;

    protected $fillable = [
        'id_pendaftaran',
        'id_hewan',
        'id_pegawai',
        'tanggal_daftar',
        'status',
        'keluhan'
    ];

    protected $casts = [
        'tanggal_daftar' => 'date'
    ];

    // Relasi ke PemilikHewan (many to one) - through Hewan
    public function pemilikHewan()
    {
        return $this->hasOneThrough(
            PemilikHewan::class,
            Hewan::class,
            'id_hewan', // FK di tabel hewan
            'id_pemilik', // FK di tabel pemilik_hewan
            'id_hewan', // Local key di pendaftaran
            'id_pemilik' // Local key di hewan
        );
    }

    // Relasi ke Hewan (many to one)
    public function hewan()
    {
        return $this->belongsTo(Hewan::class, 'id_hewan', 'id_hewan');
    }

    // Relasi ke Pegawai (many to one)
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }

    // Relasi ke Pemeriksaan (one to one)
    public function pemeriksaan()
    {
        return $this->hasOne(Pemeriksaan::class, 'id_pendaftaran', 'id_pendaftaran');
    }
}
