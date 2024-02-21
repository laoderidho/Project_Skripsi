<?php

namespace App\Models\Perawat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Perawat;

class Pemeriksaan extends Model
{

    use HasFactory;

    protected $table = 'pemeriksaan'; // Gantilah 'nama_tabel_pemeriksaan' dengan nama tabel sesuai database Anda

    protected $fillable = [
        'id_perawat',
        'id_perawatan',
        'nama_intervensi',
        'nama_luaran',
        'tindakan_intervensi',
        'catatan_intervensi',
        'catatan_evaluasi',
        'catatan_luaran',
        'catatan_implementasi',
        'jam_pemberian_diagnosa',
        'jam_pemberian_intervensi',
        'jam_pemberian_implementasi',
        'jam_penilaian_luaran',
        'jam_pemberian_evaluasi',
        'jam_pemberian_diagnosa',
        'jam_pemberian_intervensi',
        'jam_pemberian_implementasi',
        'jam_penilaian_luaran',
        'jam_pemberian_evaluasi'
    ];

    public function perawat()
    {
        return $this->hasMany(Perawat::class, 'id_perawat', 'id');
    }

    public function perawatan()
    {
        return $this->hasMany(Perawatan::class, 'id_perawatan', 'id');
    }
}
