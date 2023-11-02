<?php

namespace App\Models\Data\Pasien;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;
    protected $table = 'pasien';
    protected $fillable = [
        'nama_lengkap',
        'tanggal_lahir',
        'jenis_kelamin',
        'no_telepon',
        'alamat',
        'status_pernikahan',
        'nik',
        'alergi',
        'nama_asuransi',
        'no_asuransi',
        'no_medical_record',
        'alergi',
    ];
}
