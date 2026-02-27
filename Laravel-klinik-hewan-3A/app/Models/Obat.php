<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\GeneratesCustomId;

class Obat extends Model
{
    use HasFactory, GeneratesCustomId;

    protected $table = 'obat';
    protected $primaryKey = 'id_obat';
    public $incrementing = false;
    protected $keyType = 'string';
    
    // Custom ID Configuration - OBT001
    protected $idPrefix = 'OBT';
    protected $idLength = 3;

    protected $fillable = [
        'id_obat',
        'nama_obat',
        'jenis_obat',
        'harga_obat',
        'stok',
        'tanggal_kadaluarsa'
    ];

    protected $casts = [
        'tanggal_kadaluarsa' => 'date'
    ];

    // Relasi ke DetailPembayaranObat (one to many)
    public function detailPembayaranObat()
    {
        return $this->hasMany(DetailPembayaranObat::class, 'id_obat', 'id_obat');
    }
}
