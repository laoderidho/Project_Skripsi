<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perawatan extends Model
{
    use HasFactory;

    protected $table = 'perawatan';

    protected $fillable = [
        'id_perawat',
        'id_data_diagnostik',
        'bed',
        'waktu_pencatatan',
    ];

    public function perawat()
    {
        return $this->belongsTo(Perawat::class, 'id_perawat');
    }

    public function dataDiagnostik()
    {
        return $this->belongsTo(DataDiagnostik::class, 'id_data_diagnostik');
    }
}
