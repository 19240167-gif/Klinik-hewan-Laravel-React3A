<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\GeneratesCustomId;

class DetailPembayaranObat extends Model
{
    use HasFactory, GeneratesCustomId;

    protected $table = 'detail_pembayaran_obat';
    protected $primaryKey = 'id_detail';
    public $incrementing = false;
    protected $keyType = 'string';
    
    // Custom ID Configuration - DPO001
    protected $idPrefix = 'DPO';
    protected $idLength = 3;

    protected $fillable = [
        'id_detail',
        'id_pembayaran',
        'id_obat',
        'jumlah',
        'subtotal'
    ];

    // Relasi ke Pembayaran (many to one)
    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class, 'id_pembayaran', 'id_pembayaran');
    }

    // Relasi ke Obat (many to one)
    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat', 'id_obat');
    }
}
