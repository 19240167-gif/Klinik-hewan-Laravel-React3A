<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\GeneratesCustomId;

class Pemeriksaan extends Model
{
    use HasFactory, GeneratesCustomId;

    protected $table = 'pemeriksaan';
    protected $primaryKey = 'id_pemeriksaan';
    public $incrementing = false;
    protected $keyType = 'string';
    
    // Custom ID Configuration - PMR001
    protected $idPrefix = 'PMR';
    protected $idLength = 3;

    protected $fillable = [
        'id_pemeriksaan',
        'id_pendaftaran',
        'id_dokter',
        'diagnosa',
        'tindakan',
        'biaya_tindakan',
        'tanggal_periksa'
    ];

    protected $casts = [
        'tanggal_periksa' => 'date'
    ];

    // Relasi ke Pendaftaran (many to one)
    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'id_pendaftaran', 'id_pendaftaran');
    }

    // Relasi ke DokterHewan (many to one)
    public function dokterHewan()
    {
        return $this->belongsTo(DokterHewan::class, 'id_dokter', 'id_dokter');
    }

    // Relasi ke Pembayaran (one to one)
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_pemeriksaan', 'id_pemeriksaan');
    }

    // Relasi ke PemeriksaanObat (one to many)
    public function pemeriksaanObat()
    {
        return $this->hasMany(PemeriksaanObat::class, 'id_pemeriksaan', 'id_pemeriksaan');
    }

    // Relasi ke Obat melalui pivot table
    public function obats()
    {
        return $this->belongsToMany(Obat::class, 'pemeriksaan_obat', 'id_pemeriksaan', 'id_obat')
            ->withPivot('jumlah')
            ->withTimestamps();
    }
}
