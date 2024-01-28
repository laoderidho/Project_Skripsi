<?php

namespace App\Models\Perawat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Pasien;

class RawatInap extends Model
{
    use HasFactory;

    protected $table = 'rawat_inap';

    protected $fillable = [
        'id_pasien',
        'triase',
        'status'
    ];

    // Relasi ke tabel Diagnosa
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'id_pasien');
    }
}
