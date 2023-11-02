<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;

    protected $table = 'pasien';
    protected $fillable = [
        'nama',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'no_telp',
        'status_pernikahan',
        'nik',
        'alergi',
        'nama_asuransi',
        'no_asuransi',
        'no_medical_record',
    ];
}
