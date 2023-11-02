<?php
namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TindakanIntervensi extends Model
{
    use HasFactory;

    protected $table = 'tindakan_intervensi';

    protected $fillable = [
        'id_kategori_tindakan',
        'id_intervensi',
        'nama_tindakan_intervensi',
    ];

    public function kategoriTindakan()
    {
        return $this->belongsTo(KategoriTindakan::class, 'id_kategori_tindakan');
    }

    public function intervensi()
    {
        return $this->belongsTo(Intervensi::class, 'id_intervensi');
    }
}
