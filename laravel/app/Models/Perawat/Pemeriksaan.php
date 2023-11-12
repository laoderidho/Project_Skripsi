<?php

namespace App\Models\Perawat;

use Illuminate\Database\Eloquent\Model;

class Pemeriksaan extends Model
{
    protected $table = 'pemeriksaan'; // Gantilah 'nama_tabel_pemeriksaan' dengan nama tabel sesuai database Anda

    protected $fillable = [
        'id_perawatan', // Diubah dari 'id_perawatanan'
        'id_form_diagnosa',
        'id_tindakan_intervensi',
        'id_implementasi',
        'id_evaluasi',
        'jam_pemberian_diagnosa',
        'jam_pemberian_gejala',
        'jam_pemberian_implementasi',
        'jam_penilaian_evaluasi',
    ];

    // Keterangan foreign key
    public function perawatan()
    {
        return $this->belongsTo(Perawatan::class, 'id_perawatan');
    }

    public function formDiagnosa()
    {
        return $this->belongsTo(FormDiagnosa::class, 'id_form_diagnosa');
    }

    public function tindakanIntervensi()
    {
        return $this->belongsTo(TindakanIntervensi::class, 'id_tindakan_intervensi');
    }

    public function implementasi()
    {
        return $this->belongsTo(Implementasi::class, 'id_implementasi');
    }

    public function evaluasi()
    {
        return $this->belongsTo(Evaluasi::class, 'id_evaluasi');
    }

    // Jika ada kolom timestamp (created_at, updated_at), tambahkan properti ini
    public $timestamps = false;

    // Metode atau properti lain sesuai kebutuhan
}
