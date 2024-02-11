<?php

namespace App\Models\Perawat\StandarForm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Perawat\Pemeriksaan;

class Form_Diagnosa extends Model
{
    use HasFactory;

    protected $table = 'form_diagnosa';

    protected $fillable = [
        'id_pemeriksaan',
        'nama_diagnosa',
        'gejala_tanda_mayor_objektif',
        'gejala_tanda_mayor_subjektif',
        'gejala_tanda_minor_objektif',
        'gejala_tanda_minor_subjektif',
        'penyebab_psikologis',
        'penyebab_situasional',
        'penyebab_fisiologis',
        'penyebab_umum',
        'faktor_risiko',
        'catatan_diagnosa',
    ];

    public function pemeriksaan()
    {
        return $this->hasMany(Pemeriksaan::class, 'id_pemeriksaan', 'id');
    }
}
