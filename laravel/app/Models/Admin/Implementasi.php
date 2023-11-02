<?php
namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Implementasi extends Model
{
    use HasFactory;

    protected $table = 'implementasi';

    protected $fillable = [
        'id_tindakan_intervensi',
        'id_tindakan_implementasi',
        'status_implementasi',
    ];

    // Relasi ke tabel TindakanIntervensi
    public function tindakanIntervensi()
    {
        return $this->belongsTo(TindakanIntervensi::class, 'id_tindakan_intervensi');
    }

    // Relasi ke tabel TindakanImplementasi
    public function tindakanImplementasi()
    {
        return $this->belongsTo(TindakanImplementasi::class, 'id_tindakan_implementasi');
    }
}
