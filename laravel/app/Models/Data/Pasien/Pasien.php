<?php

<<<<<<< HEAD:laravel/app/Models/Data/Pasien/Pasien.php
namespace App\Models\Data\Pasien;
=======
namespace App\Models\Admin;
>>>>>>> 73ff3bc34597ed1a4a22d609c381f25eb60395d3:laravel/app/Models/Admin/Pasien.php

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
<<<<<<< HEAD:laravel/app/Models/Data/Pasien/Pasien.php
        'no_telepon',
        'alamat',
=======
        'alamat',
        'no_telp',
>>>>>>> 73ff3bc34597ed1a4a22d609c381f25eb60395d3:laravel/app/Models/Admin/Pasien.php
        'status_pernikahan',
        'nik',
        'alergi',
        'nama_asuransi',
        'no_asuransi',
        'no_medical_record',
<<<<<<< HEAD:laravel/app/Models/Data/Pasien/Pasien.php
        'alergi',
=======
>>>>>>> 73ff3bc34597ed1a4a22d609c381f25eb60395d3:laravel/app/Models/Admin/Pasien.php
    ];
}
