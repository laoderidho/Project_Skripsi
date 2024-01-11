<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnostic extends Model
{
    use HasFactory;

    protected $table = 'data_diagnostik';

    protected $fillable = [
        'id_pasien',
        'keluhan_utama',
        'riwayat_penyakit',
        'riwayat_alergi',
        'risiko_jatuh',
        'risiko_nyeri',
        'suhu',
        'tekanan_darah',
        'nadi',
        'laju_respirasi',
        'kesadaran',
        'eye',
        'motor',
        'visual',
        'pemeriksaan_fisik',
    ];

    public function Pasien()
    {
        return $this->hasMany(Pasien::class, 'id_pasien');
    }
}
