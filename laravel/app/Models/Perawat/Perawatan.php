<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Perawat;;

class Perawatan extends Model
{
    use HasFactory;

    protected $table = 'perawatan';

    protected $fillable = [
        'id_pasien',
        'id_data_diagnostik',
        'bed',
        'waktu_pencatatan',
        'status_pasien'
    ];

    public function dataDiagnostik()
    {
        return $this->belongsTo(DataDiagnostik::class, 'id_data_diagnostik');
    }
    public function Pasien()
    {
        return $this->belongsTo(Pasien::class, 'id_pasien');
    }
}
