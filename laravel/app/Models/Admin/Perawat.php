<?php
namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Perawat extends Model
{
    use HasFactory;

    protected $table = 'perawat'; // Nama tabel di database

    // Kolom-kolom yang dapat diisi
    protected $fillable = [
        'id_user',
        'id_waktu_shift',
        'status',
        // Kolom-kolom lainnya sesuai dengan struktur tabel 'perawat'
    ];

    // Relasi ke tabel User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Relasi ke tabel WaktuShift
    public function waktuShift()
    {
        return $this->belongsTo(WaktuShift::class, 'id_waktu_shift');
    }
}
