<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPenyebab extends Model
{
    use HasFactory;

    protected $table = 'jenis_penyebab';

    protected $fillable = [
        'id_jenis_penyebab',
        'nama_jenis_penyebab',
    ];
}
