<?php

namespace App\Models\Perawat\StandarForm;

use Illuminate\Database\Eloquent\Model;

class Form_Intervensi extends Model
{
    protected $table = 'form_intervensi';

    protected $fillable = [
        'id_pemeriksaan',
        'nama_intervensi',
        'tindakan_intervensi'
    ];
    public function pemeriksaan()
    {
        return $this->hasMany(Pemeriksaan::class, 'id_pemeriksaan', 'id');
    }
}
