<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bed extends Model
{
    use HasFactory;

    protected $table = 'beds';

    protected $fillable = [
        'lantai',
        'nama_fasilitas',
        'jenis_ruangan',
        'no_bed',
        'status'
    ];
}
