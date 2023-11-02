<?php
namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriGejala extends Model
{
    use HasFactory;

    protected $table = 'kategori_gejala';

    protected $fillable = [
        'nama_kategori_gejala',
    ];

    // Tidak ada relasi pada contoh ini
}
