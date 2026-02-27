<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\GeneratesCustomId;

class Pembayaran extends Model
{
    use HasFactory, GeneratesCustomId;

    protected $table = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';
    public $incrementing = false;
    protected $keyType = 'string';
    
    // Custom ID Configuration - PBY001
    protected $idPrefix = 'PBY';
    protected $idLength = 3;

    protected $fillable = [
        'id_pembayaran',
        'id_pemeriksaan',
        'tanggal_bayar',
        'metode_bayar',
        'total_bayar'
    ];

    protected $casts = [
        'tanggal_bayar' => 'date'
    ];

    // Relasi ke Pemeriksaan (many to one)
    public function pemeriksaan()
    {
        return $this->belongsTo(Pemeriksaan::class, 'id_pemeriksaan', 'id_pemeriksaan');
    }

    // Relasi ke DetailPembayaranObat (one to many)
    public function detailPembayaranObat()
    {
        return $this->hasMany(DetailPembayaranObat::class, 'id_pembayaran', 'id_pembayaran');
    }
}
