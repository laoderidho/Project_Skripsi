<?php

namespace App\Http\Controllers\Perawat\StandarForm;

use App\Http\Controllers\Controller;
use App\Models\Perawat\Perawatan;
use App\Models\Admin\Pasien;

class ManajemenListController extends Controller
{
    public function setNameWithPerawatan($perawatan_id){
        $perawatan = Perawatan::where('id', $perawatan_id)->first();
        $pasien = $perawatan->pasien;

        $name = $pasien->nama_lengkap;

        return response()->json([
            'message'=> 'success',
            'name' => $name,
        ]);
    }
}
