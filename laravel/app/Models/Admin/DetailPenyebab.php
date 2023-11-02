<?php
namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenyebab extends Model
{
    use HasFactory;

    protected $table = 'detail_penyebab';

    protected $fillable = [
        'id_diagnosa',
        'id_jenis_penyebab',
        'nama_penyebab',
    ];

    // Relasi ke tabel Diagnosa
    public function diagnosa()
    {
        return $this->belongsTo(Diagnosa::class, 'id_diagnosa');
    }

    // Relasi ke tabel JenisPenyebab
    public function jenisPenyebab()
    {
        return $this->belongsTo(JenisPenyebab::class, 'id_jenis_penyebab');
    }
}
