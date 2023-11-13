<?php

namespace App\Models\Perawat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnostic extends Model
{
    protected $table = 'data_diagnostik';

    protected $fillable = [
        'id_pasien',
        'keluhan_utama',
        'riwayat_penyakit',
        'riwayat_alergi',
        'resiko_jatuh',
        'resiko_nyeri',
        'suhu',
        'tekanan_darah',
        'nadi',
        'laju_respirasi',
        'kesadaran',
        'gcs_eyes',
        'gcs_motoric',
        'gcs_visual',
        'pemeriksaan_fisik',
    ];
    use HasFactory;
}
