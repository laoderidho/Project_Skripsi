<?php
namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisGejala extends Model
{
    use HasFactory;

    protected $table = 'jenis_gejala';

    protected $fillable = [
        'nama_jenis_gejala',
    ];

    // Tidak ada relasi pada contoh ini
}
