<?php

namespace App\Models\Perawat;
namespace App\Models\Perawatan;
namespace App\Models\Admin\Diagnosa;
namespace App\Models\Admin\Implementasi;
namespace App\Models\Admin\TindakanIntervensi;
namespace App\Models\Admin\Evaluasi;



use Illuminate\Database\Eloquent\Model;

class Pemeriksaan extends Model
{
    protected $table = 'pemeriksaan'; // Gantilah 'nama_tabel_pemeriksaan' dengan nama tabel sesuai database Anda

    protected $fillable = [
        'id_perawatan',
        'id_perawat',
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
    public function perawat()
    {
        return $this->belongsTo(Perawatan::class, 'id_perawat');
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
