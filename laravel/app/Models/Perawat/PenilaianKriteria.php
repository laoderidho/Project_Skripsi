<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianKriteria extends Model
{
    use HasFactory;

    protected $table = 'penilaian_kriteria';

    protected $fillable = [
        'id_kriteria_luaran',
        'nilai',
    ];

    public function kriteriaLuaran()
    {
        return $this->belongsTo(KriteriaLuaran::class, 'id_kriteria_luaran');
    }
}
