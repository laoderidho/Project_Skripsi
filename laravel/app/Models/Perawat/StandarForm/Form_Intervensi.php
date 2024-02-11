<?php

namespace App\Models\Perawat;

use Illuminate\Database\Eloquent\Model;

class Form_Intervensi extends Model
{
    protected $table = 'form_intervensi';
    protected $primaryKey = 'id'; // sesuaikan dengan primary key di tabel

    protected $fillable = [
        'id_pemeriksaan',
        'kode_intervensi',
        'nama_intervensi',
        'observasi',
        'terapeutik',
        'edukasi',
        'kolaborasi',
        'catatan_intervensi'
    ];
    public function pemeriksaan()
    {
        return $this->hasMany(Pemeriksaan::class, 'id_pemeriksaan', 'id');
    }
}
