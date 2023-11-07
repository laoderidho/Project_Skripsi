<?php
namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intervensi extends Model
{
    use HasFactory;

    protected $table = 'intervensi';
    protected $fillable = [
        'id',
       'kode_intervensi',
       'nama_intervensi',
    ];

    public function tindakanIntervensi()
    {
        return $this->hasMany(TindakanIntervensi::class, 'id_intervensi');
    }

}
