<?php

namespace App\Models\Perawat\StandarForm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form_Implementasi extends Model
{
    use HasFactory;

    protected $table = 'form_implementasi';

    protected $fillable = [
        'id_pemeriksaan',
        'nama_implementasi',
        'tindakan_implementasi',
        'jam_ceklis',
    ];

    /**
     * Get the pemeriksaan that owns the form implementasi.
     */
    public function pemeriksaan()
    {
        return $this->belongsTo(Pemeriksaan::class, 'id_pemeriksaan');
    }
}
