<?php

namespace App\Http\Controllers\Perawat\StandarForm;

use App\Http\Controllers\Controller;
use App\Models\Admin\Intervensi;
use App\Models\Admin\Perawat;
use App\Models\Perawat\Form_Intervensi;
use App\Models\Admin\TindakanIntervensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// validator suport
use Illuminate\Support\Facades\Validator;
// db suport
use Illuminate\Support\Facades\DB;
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
        $tindakan_kolaborasi = TindakanIntervensi::where('id_intervensi', $intervensi->id)->where('id_kategori_tindakan', 4)->get();

        return response()->json([
            'message' => 'Success',
            'data' => $intervensi,
            'tindakan_observasi' => $tindakan_observasi,
            'tindakan_teraupetik' => $tindakan_teraupetik,
            'tindakan_edukasi' => $tindakan_edukasi,
            'tindakan_kolaborasi' =>$tindakan_kolaborasi,
        ]);
    }
    public function updateIntervensi(Request $request, $id_pemeriksaan){
        $users = Auth::user()->id;
        $perawat = Perawat::where('id_user', $users)->first();
        $perawat = $perawat->id;

        $validator = Validator::make($request->all(), [
            'nama_intervensi' =>'required|string|max:255',
            'tindakan_observasi' => 'required|string|max:5000',
            'tindakan_terapeutik' => 'required|string|max:5000',
            'tindakan_edukasi' => 'required|string|max:5000',
            'tindakan_kolaborasi' => 'required|string|max:5000',
            'catatan_intervensi' => 'nullable|string|max:255',
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        // transaction db
        DB::beginTransaction();
        try{
            $pemeriksaan->id_perawat = $perawat;
            $pemeriksaan->id_perawatan = $id_perawatan;
            $pemeriksaan->jam_pemberian_intervensi = date('H:i:s');
            $pemeriksaan->shift = $request->shift;
            $pemeriksaan->update();

            $form_intervensi = new Form_Intervensi();

            $form_intervensi->kode_intervensi = $request->input('kode_intervensi');
            $form_intervensi->nama_intervensi = $request->input('nama_intervensi');
            $form_intervensi->observasi = $request->input('tindakan_observasi');
            $form_intervensi->terapeutik = $request->input('tindakan_terapeutik');
            $form_intervensi->edukasi = $request->input('tindakan_edukasi');
            $form_intervensi->kolaborasi = $request->input('tindakan_kolaborasi');
            $form_intervensi->catatan_intervensi = $request->input('catatan_intervensi');
            $form_intervensi->update();

            DB::commit();
            return response()->json([
            'message' => 'Success']);

        }catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Failed',
                'error' => $e->getMessage(),
            ], 500);
        }

    }
}
