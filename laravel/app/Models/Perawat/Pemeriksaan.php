<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemeriksaan extends Model
{
    use HasFactory;

    protected $table = 'pemeriksaan';

    protected $fillable = [
        'id_perawat',
        'id_form_diagnosa',
        'id_tindakan_intervensi',
        'id_implementasi',
        'id_evaluasi',
        'jam_pemberian_diagnosa',
        'jam_pemberian_gejala',
        'jam_pemberian_implementasi',
        'jam_penilaian_evaluasi',
    ];

    public function perawat()
    {
        return $this->belongsTo(Perawat::class, 'id_perawat');
    }

    public function formDiagnosa()
    {
        return $this->belongsTo(FormDiagnosa::class, 'id_form_diagnosa');
    }

    public function tindakanIntervensi()
    {
        return $this->belongsTo(TindakanIntervensi::class, 'id_tindakan_intervensi');
    }

    public function implementasi()
    {
        return $this->belongsTo(Implementasi::class, 'id_implementasi');
    }

    public function evaluasi()
    {
        return $this->belongsTo(Evaluasi::class, 'id_evaluasi');
    }
}
