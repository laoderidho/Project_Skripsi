<?php
namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TindakanImplementasi extends Model
{
    use HasFactory;

    protected $table = 'tindakan_implementasi';

    protected $fillable = [
        'id_tindakan_intervensi',
        'kalimat_implementasi',
    ];

    public function tindakanIntervensi()
    {
        return $this->belongsTo(TindakanIntervensi::class, 'id_tindakan_intervensi');
    }
}
