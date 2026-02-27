<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\GeneratesCustomId;

class PemilikHewan extends Model
{
    use HasFactory, GeneratesCustomId;

    protected $table = 'pemilik_hewan';
    protected $primaryKey = 'id_pemilik';
    public $incrementing = false;
    protected $keyType = 'string';
    
    // Custom ID Configuration
    protected $idPrefix = 'PMH';
    protected $idLength = 3;

    protected $fillable = [
        'id_pemilik',
        'user_id',
        'nama_pemilik',
        'no_tlp',
        'alamat',
        'jenis_pendaftaran'
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Hewan (one to many)
    public function hewan()
    {
        return $this->hasMany(Hewan::class, 'id_pemilik', 'id_pemilik');
    }

    // Relasi ke Pendaftaran (through Hewan)
    public function pendaftaran()
    {
        return $this->hasManyThrough(
            Pendaftaran::class,
            Hewan::class,
            'id_pemilik',      // Foreign key on hewan table
            'id_hewan',        // Foreign key on pendaftaran table
            'id_pemilik',      // Local key on pemilik_hewan table
            'id_hewan'         // Local key on hewan table
        );
    }
}
