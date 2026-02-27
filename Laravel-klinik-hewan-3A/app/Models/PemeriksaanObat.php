<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemeriksaanObat extends Model
{
    use HasFactory;

    protected $table = 'pemeriksaan_obat';
    public $incrementing = false;

    protected $fillable = [
        'id_pemeriksaan',
        'id_obat',
        'jumlah'
    ];

    // Relasi ke Pemeriksaan
    public function pemeriksaan()
    {
        return $this->belongsTo(Pemeriksaan::class, 'id_pemeriksaan', 'id_pemeriksaan');
    }

    // Relasi ke Obat
    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat', 'id_obat');
    }
}
