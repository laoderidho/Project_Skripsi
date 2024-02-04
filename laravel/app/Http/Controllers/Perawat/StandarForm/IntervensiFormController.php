<?php

namespace App\Http\Controllers\Perawat\StandarForm;

use App\Http\Controllers\Controller;
use App\Models\Admin\Intervensi;
use App\Models\Admin\TindakanIntervensi;
use Illuminate\Http\Request;

class IntervensiFormController extends Controller
{
    public function getIntervensi(){
        $intervensi = Intervensi::all();

        return response()->json([
            'message' => 'Success',
            'data' => $intervensi,
        ]);
    }

    public function validationIntervensiAttribute($id){
        $intervensi = Intervensi::find($id);
        
        if($intervensi == null){
            return response()->json([
                'message' => 'Intervensi tidak ditemukan',
            ], 404);
        }

        $tindakan_observasi = TindakanIntervensi::where('id_intervensi', $intervensi->id)->where('id_kategori_tindakan', 1)->get();
        $tindakan_teraupetik = TindakanIntervensi::where('id_intervensi', $intervensi->id)->where('id_kategori_tindakan', 2)->get();
        $tindakan_edukasi = TindakanIntervensi::where('id_intervensi', $intervensi->id)->where('id_kategori_tindakan', 3)->get();

        return response()->json([
            'message' => 'Success',
            'data' => $intervensi,
            'tindakan_observasi' => $tindakan_observasi,
            'tindakan_teraupetik' => $tindakan_teraupetik,
            'tindakan_edukasi' => $tindakan_edukasi,
        ]);
    }
}
