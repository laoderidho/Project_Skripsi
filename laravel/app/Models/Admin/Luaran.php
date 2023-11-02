<?php
namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Luaran extends Model
{
    use HasFactory;

    protected $table = 'luaran';

    protected $fillable = [
        'kode_luaran',
        'nama_luaran',
    ];
}
