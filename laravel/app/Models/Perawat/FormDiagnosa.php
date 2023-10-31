<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormDiagnosa extends Model
{
    use HasFactory;

    protected $table = 'form_diagnosa';

    protected $fillable = [
        'id_diagnosa',
        'id_gejala',
        'id_detail_penyebab',
        'id_faktor_resiko',
        'catatan_diagnosa',
    ];

    public function diagnosa()
    {
        return $this->belongsTo(Diagnosa::class, 'id_diagnosa');
    }

    public function gejala()
    {
        return $this->belongsTo(Gejala::class, 'id_gejala');
    }

    public function detailPenyebab()
    {
        return $this->belongsTo(DetailPenyebab::class, 'id_detail_penyebab');
    }

    public function faktorResiko()
    {
        return $this->belongsTo(FaktorResiko::class, 'id_faktor_resiko');
    }
}
