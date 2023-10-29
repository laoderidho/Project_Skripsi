<?php
namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnosa extends Model
{
    use HasFactory;

    protected $table = 'diagnosa'; // Menyesuaikan nama tabel di database

    protected $fillable = [
        'id_diagnosa', // Menyertakan kolom id_diagnosa dalam $fillable agar dapat diisi
        'nama_diagnosa',
    ];
}
