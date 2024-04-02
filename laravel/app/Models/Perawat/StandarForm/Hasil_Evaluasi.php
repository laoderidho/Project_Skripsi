<?php

namespace App\Models\Perawat\StandarForm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Perawat\Pemeriksaan;

class Hasil_Evaluasi extends Model
{
    use HasFactory;

    protected $table = 'hasil_evaluasi';

    protected $fillable = [
        'id_pemeriksaan',
        'subjektif',
        'objektif',
        'perencanaan',
        'pencapaian',
        'catatan_lainnya'
    ];

    public function pemeriksaan()
    {
        return $this->belongsTo(Pemeriksaan::class, 'id_pemeriksaan', 'id');
    }
}
