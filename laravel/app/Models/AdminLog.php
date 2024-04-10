<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    use HasFactory;

    protected $table = 'log_admin';

    protected $fillable = [
        'id_admin',
        'admin_name',
        'action',
        'jam'
    ];
}
