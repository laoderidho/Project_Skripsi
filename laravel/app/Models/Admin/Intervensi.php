<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intervensi extends Model
{
    use HasFactory;

    protected $table = 'intervensi';
    protected $fillable = [
       'id_intervensi',
       'nama_intervensi'
    ];

}
