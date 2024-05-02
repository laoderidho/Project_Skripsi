<?php

namespace App\Models\Admin;
namespace App\Models\Perawat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Pasien;
use App\Models\Admin\Perawat;

class Diagnostic extends Model
{
    use HasFactory;

    protected $table = 'data_diagnostik';

    protected $fillable = [
        'id_pasien',
        'id_perawat',
        'keluhan_tambahan',
        'suhu',
        'tekanan_darah',
        'sistolik',
        'diastolik',
        'nadi',
        'laju_respirasi',
        'kesadaran',
        'eye',
        'motor',
        'verbal',
        'pemeriksaan_fisik',
    ];

    public function Pasien()
    {
        return $this->belongsTo(Pasien::class, 'id_pasien');
    }
    public function Perawat()
    {
        return $this->belongsTo(Perawat::class, 'id_perawat');
    }
}
