<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\GeneratesCustomId;

class DokterHewan extends Model
{
    use HasFactory, GeneratesCustomId;

    protected $table = 'dokter_hewan';
    protected $primaryKey = 'id_dokter';
    public $incrementing = false;
    protected $keyType = 'string';
    
    // Custom ID Configuration - DKT001
    protected $idPrefix = 'DKT';
    protected $idLength = 3;

    protected $fillable = [
        'id_dokter',
        'nama_dokter',
        'no_sip'
    ];

    // Relasi ke Pemeriksaan (one to many)
    public function pemeriksaan()
    {
        return $this->hasMany(Pemeriksaan::class, 'id_dokter', 'id_dokter');
    }

    // Relasi ke User (one to one)
    public function user()
    {
        return $this->belongsTo(User::class, 'nama_dokter', 'name');
    }
}
