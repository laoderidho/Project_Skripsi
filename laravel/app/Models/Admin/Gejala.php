<?php
namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gejala extends Model
{
    use HasFactory;

    protected $table = 'gejala';

    protected $fillable = [
        'id_diagnosa',
        'id_kategori_gejala',
        'id_jenis_gejala',
        'nama_gejala',
    ];

    // Relasi ke tabel Diagnosa
    public function diagnosa()
    {
        return $this->belongsTo(Diagnosa::class, 'id_diagnosa');
    }

    // Relasi ke tabel JenisGejala
    public function jenisGejala()
    {
        return $this->belongsTo(JenisGejala::class, 'id_jenis_gejala');
    }
}
