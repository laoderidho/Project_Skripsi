<?php
namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intervensi extends Model
{
    use HasFactory;

    protected $table = 'intervensi';
    protected $fillable = [
       'nama_intervensi'
    ];

}
