<?php

namespace App\Models;

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
        'no_telepon',
        'status_pernikahan',
        'nik',
        'penyedia_asuransi',
        'no_asuransi',
        'no_medical_record',
        'bed',
        'alergi',
    ];
}
