<?php
namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPenyebab extends Model
{
    use HasFactory;

    protected $table = 'jenis_penyebab';

    protected $fillable = [
        'nama_jenis_penyebab',
    ];
}
