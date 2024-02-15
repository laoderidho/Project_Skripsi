<?php

namespace App\Models\Perawat\StandarForm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Perawat\Pemeriksaan;

class Form_Evaluasi extends Model
{
    use HasFactory;

    protected $table = 'form_evaluasi';

    protected $fillable = [
        'id_pemeriksaan',
        'nama_luaran',
        'hasil_luaran',
    ];

    public function pemeriksaan()
    {
        return $this->belongsTo(Pemeriksaan::class, 'id_pemeriksaan', 'id');
    }
}
