<?php
namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnosa extends Model
{
    use HasFactory;

    protected $table = 'diagnosa'; // Menyesuaikan nama tabel di database

    protected $fillable = [
        'kode_diagnosa',
        'nama_diagnosa',
    ];

    public function faktorResiko()
    {
        return $this->hasMany(FaktorResiko::class, 'id_diagnosa');
    }
    public function gejala()
    {
        return $this->hasMany(Gejala::class, 'id_diagnosa');
    }
    public function penyebab()
    {
        return $this->hasMany(DetailPenyebab::class, 'id_diagnosa');
    }

}
