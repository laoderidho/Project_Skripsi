<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amnanessa extends Model
{
    use HasFactory;

    protected $table = 'amnanessa';

    protected $fillable = [
        'keluhan_utama',
        'riwayat_penyakit',
        'riwayat_alergi',
        'risiko_jatuh',
        'risiko_nyeri',
    ];
}
