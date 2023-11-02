<?php
namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriTindakan extends Model
{
    use HasFactory;

    protected $table = 'kategori_tindakan';

    protected $fillable = [
        'nama_kategori_tindakan',
    ];

    // Tidak ada relasi pada contoh ini
}
