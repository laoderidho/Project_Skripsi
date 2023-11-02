<?php
namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KriteriaLuaran extends Model
{
    use HasFactory;

    protected $table = 'kriteria_luaran';

    protected $fillable = [
        'id_luaran',
        'nama_kriteria_luaran',
    ];

    // Relasi ke tabel Luaran
    public function luaran()
    {
        return $this->belongsTo(Luaran::class, 'id_luaran');
    }
}
