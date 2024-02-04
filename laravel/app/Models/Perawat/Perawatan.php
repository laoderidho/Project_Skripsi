<?php
namespace App\Models\Perawat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Perawat;
use App\Models\Admin\Pasien;
use App\Models\Perawat\Diagnostic;
class Perawatan extends Model
{
    use HasFactory;

    protected $table = 'perawatan';

    protected $fillable = [
        'id_pasien',
        'bed',
        'waktu_pencatatan',
        'status_pasien'
    ];

    public function Pasien()
    {
        return $this->belongsTo(Pasien::class, 'id_pasien');
    }
}
