<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluasi extends Model
{
    use HasFactory;

    protected $table = 'evaluasi';

    protected $fillable = [
        'id_luaran',
        'id_penilaian_kriteria',
        'subjektif',
        'objektif',
        'pencapaian',
        'perencanaan',
        'catatan_evaluasi',
    ];

    public function luaran()
    {
        return $this->belongsTo(Luaran::class, 'id_luaran');
    }

    public function penilaianKriteria()
    {
        return $this->belongsTo(PenilaianKriteria::class, 'id_penilaian_kriteria');
    }
}
