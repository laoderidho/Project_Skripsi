<?php
namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnosa extends Model
{
    use HasFactory;

    protected $table = 'diagnosa'; // Menyesuaikan nama tabel di database

    protected $fillable = [
        'kode_diagnosa',
        'nama_diagnosa',
    ];
}
