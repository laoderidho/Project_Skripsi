<?php

namespace App\Http\Controllers\Admin\StandardKeperawatan\Intervensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Intervensi;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\TindakanIntervensi;
use App\Models\Admin\KategoriTindakan;
use Illuminate\Support\Facades\DB;

class IntervensiController extends Controller
{

    public function getIntervensi(){
        $intervensi = Intervensi::all();

        return response()->json([
            'message' => 'Success',
            'data' => $intervensi,
        ]);
    }

    public function AddIntervensi(Request $request){
            $validator = Validator::make($request->all(),[
                'kode_intervensi'=> 'required|string|max:10|unique:intervensi',
                'nama_intervensi'=> 'required|string|max:255',
            ]);

            $observasi = $request->input('observasi');
            $terapeutik = $request->input('terapeutik');
            $edukasi = $request->input('edukasi');


            if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
            }

            DB::beginTransaction();
            try{
                $intervensi = new Intervensi([
                    'kode_intervensi' => $request->input('kode_intervensi'),
                    'nama_intervensi' => $request->input('nama_intervensi'),
                ]);

                $intervensi->save();

                if($observasi!=null || $observasi!=''){
                    foreach($observasi as $obs){
                        $this->intervensiAction($intervensi->id, $obs, 'Observasi');
                    }
                }

                if($terapeutik!=null || $terapeutik!=''){
                    foreach($terapeutik as $ter){
                        $this->intervensiAction($intervensi->id, $ter, 'Terapeutik');
                    }
                }

                if($edukasi!=null || $edukasi!=''){
                    foreach($edukasi as $edu){
                        $this->intervensiAction($intervensi->id, $edu, 'Edukasi');
                    }
                }

                DB::commit();
            }catch(\Exception $e){
                dd($e);
                DB::rollback();
            }




            return response()->json([
                'message' => 'Intervensi successfully added',
                'intervensi' => $intervensi
            ], 201);
    }

    private function intervensiAction($id_intervensi, $data_tindakan, $jenis_tindakan){
            $intervensi = Intervensi::find($id_intervensi);
            $getIdIntervensi = $intervensi->id;
            $tindakan = KategoriTindakan::where('nama_kategori_tindakan', $jenis_tindakan)->first();
            $id_tindakan = $tindakan->id;

            $tindakanIntervensi = new TindakanIntervensi([
                'id_kategori_tindakan' => $id_tindakan,
                'id_intervensi' => $getIdIntervensi,
                'nama_tindakan_intervensi' => $data_tindakan,
            ]);

            $tindakanIntervensi->save();
    }


    public function detailIntervensi($id){

            $intervensi = Intervensi::find($id);

            $tindakan_intervensi = DB::table('tindakan_intervensi as t')
            ->select('t.id_kategori_tindakan', 't.nama_tindakan_intervensi')
            ->join('intervensi as i', 't.id_intervensi', '=', 'i.id')
            ->where('i.id', '=', $intervensi->id)
            ->get();

            return response()->json([
                'message' => 'Success',
                'tindakan' => $tindakan_intervensi,
                'data'=> $intervensi,
            ]);
    }

    public function updateIntervensi(Request $request, $id){

        $intervensi = Intervensi::find($id);

        $validator = Validator::make($request->all(),[
            'kode_intervensi'=> 'required|string|max:10',
            'nama_intervensi'=> 'required|string|max:255',
        ]);

        $observasi = $request->input('observasi');
        $terapeutik = $request->input('terapeutik');
        $edukasi = $request->input('edukasi');

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        DB::beginTransaction();

        try{

            $intervensi->kode_intervensi = $request->input('kode_intervensi');
            $intervensi->nama_intervensi = $request->input('nama_intervensi');

            $intervensi->update();

            if($observasi!=null || $observasi!=''){
                $i = 0;
                foreach($observasi as $obs){
                    $this->updateIntervensiAction($intervensi->id, $obs, 'Observasi', $i);
                    $i++;
                }
            }

            if($terapeutik!=null || $terapeutik!=''){
                $i = 0;
                foreach($terapeutik as $ter){
                    $this->updateIntervensiAction($intervensi->id, $ter, 'Terapeutik', $i);
                    $i++;
                }
            }

            if($edukasi!=null || $edukasi!=''){
                $i = 0;
                foreach($edukasi as $edu){
                    $this->updateIntervensiAction($intervensi->id, $edu, 'Edukasi', $i);
                    $i++;
                }
            }


            DB::commit();

        }catch(\Exception $e){
            dd($e);
            DB::rollback();
        }

        return response()->json([
            'message' => 'Intervensi successfully updated',
            'intervensi' => $intervensi
        ], 201);

    }

    private function updateIntervensiAction($id_intervensi, $data_tindakan, $jenis_tindakan, $tindakan){

        $kategori_tindakan_id = KategoriTindakan::where('nama_kategori_tindakan', $jenis_tindakan)->first()->id;

        $id_tindakan_intervensi = DB::table('tindakan_intervensi')
        ->select('id')
        ->where('id_intervensi', $id_intervensi)
        ->where('id_kategori_tindakan', $kategori_tindakan_id)
        ->offset($tindakan)
        ->limit(1)
        ->get();

        if($id_tindakan_intervensi->isEmpty()){
            $this->intervensiAction($id_intervensi, $data_tindakan, $jenis_tindakan);
            return;
        }

        $id_tindakan_intervensi = $id_tindakan_intervensi[0]->id;

        $tindakan_intervensi = TindakanIntervensi::where('id', $id_tindakan_intervensi)->first();

        $tindakan_intervensi->nama_tindakan_intervensi = $data_tindakan;

        $tindakan_intervensi->update();
    }

    public function deleteIntervensi($id){

       $intervensi = Intervensi::find($id);


        DB::beginTransaction();

        try{
            DB::table('tindakan_intervensi')
            ->where('id_intervensi', $id)
            ->delete();

            $intervensi->delete();

            DB::commit();
        }catch(\Exception $e){
            dd($e);
            DB::rollback();
        }


        return response()->json([
            'message' => 'Intervensi successfully deleted',
        ], 201);
    }
}
