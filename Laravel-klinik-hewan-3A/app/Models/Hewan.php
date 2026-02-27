<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\GeneratesCustomId;

class Hewan extends Model
{
    use HasFactory, GeneratesCustomId;

    protected $table = 'hewan';
    protected $primaryKey = 'id_hewan';
    public $incrementing = false;
    protected $keyType = 'string';
    
    // Custom ID Configuration - HWN001
    protected $idPrefix = 'HWN';
    protected $idLength = 3;

    protected $fillable = [
        'id_hewan',
        'nama_hewan',
        'jenis_hewan',
        'jenis_kelamin',
        'umur',
        'id_pemilik'
    ];

    // Relasi ke PemilikHewan (many to one)
    public function pemilikHewan()
    {
        return $this->belongsTo(PemilikHewan::class, 'id_pemilik', 'id_pemilik');
    }
}
