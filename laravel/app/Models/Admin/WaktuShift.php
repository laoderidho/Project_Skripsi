<?php
namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaktuShift extends Model
{
    use HasFactory;

    protected $table = 'waktu_shift';

    protected $fillable = [
        'shift',
    ];
}
