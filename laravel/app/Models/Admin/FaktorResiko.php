<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaktorResiko extends Model
{
    use HasFactory;

    protected $table = 'faktor_resiko';

    protected $fillable = [
        'id_diagnosa',
        'nama',
    ];

    // Relasi ke tabel Diagnosa
    public function diagnosa()
    {
        return $this->belongsTo(Diagnosa::class, 'id_diagnosa');
    }
}
