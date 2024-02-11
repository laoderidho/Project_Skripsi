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

    public function handlePemeriksaanRequest(Request $request) {
        $validator = Validator::make($request->all(), [
            'id_pemeriksaan' => 'required|string',
            'id_perawat' => 'required|string',
            'nama_intervensi' => 'required|string|max:255',
            'tindakan_intervensi' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        DB::beginTransaction();

        try {
            $id_pemeriksaan = $request->input('id_pemeriksaan');
            $id_perawat = $request->input('id_perawat');
            $nama_intervensi = $request->input('nama_intervensi');
            $tindakan_intervensi = $request->input('tindakan_intervensi');

            $intervensi = new Intervensi([
                'id_pemeriksaan' => $id_pemeriksaan,
                'id_perawat' => $id_perawat,
                'nama_intervensi' => $nama_intervensi,
            ]);

            $intervensi->save();

            foreach ($tindakan_intervensi as $tindakan) {
                $this->intervensiAction($intervensi->id, $tindakan);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Failed to handle pemeriksaan: ' . $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Pemeriksaan handled successfully'], 201);
    }

    private function intervensiAction($id_intervensi, $data_tindakan) {
        try {
            $tindakanIntervensi = new TindakanIntervensi([
                'id_intervensi' => $id_intervensi,
                'nama_tindakan_intervensi' => $data_tindakan,
            ]);

            $tindakanIntervensi->save();
        } catch (\Exception $e) {
            throw new \Exception('Failed to perform intervensi action: ' . $e->getMessage());
        }
    }


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
            $kolaborasi = $request->input('kolaborasi');


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

                if($observasi!=null){

                    foreach($observasi as $obs){
                        $this->intervensiAction($intervensi->id, $obs, 'Observasi');
                    }
                }
                if($terapeutik!=null){
                    foreach($terapeutik as $ter){
                        $this->intervensiAction($intervensi->id, $ter, 'Terapeutik');
                    }
                }

                if($edukasi!=null){
                    foreach($edukasi as $edu){
                        $this->intervensiAction($intervensi->id, $edu, 'Edukasi');
                    }
                }

                if($kolaborasi!=null){
                    foreach($kolaborasi as $kol){
                        $this->intervensiAction($intervensi->id, $kol, 'Kolaborasi');
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


    public function detailIntervensi($id){

            $intervensi = Intervensi::find($id);

            $tindakan_intervensi = DB::table('tindakan_intervensi as t')
            ->select('t.id_kategori_tindakan', 't.nama_tindakan_intervensi')
            ->join('intervensi as i', 't.id_intervensi', '=', 'i.id')
            ->where('i.id', '=', $intervensi->id)
            ->get();

            $tindakan_observasi = $tindakan_intervensi->where('id_kategori_tindakan', 1);
            $tindakan_terapeutik = $tindakan_intervensi->where('id_kategori_tindakan', 2);
            $tindakan_edukasi = $tindakan_intervensi->where('id_kategori_tindakan', 3);
            $tindakan_kolaborasi = $tindakan_intervensi->where('id_kategori_tindakan', 4);

            $tindakan_observasi = $tindakan_observasi->pluck('nama_tindakan_intervensi');
            $tindakan_terapeutik = $tindakan_terapeutik->pluck('nama_tindakan_intervensi');
            $tindakan_edukasi = $tindakan_edukasi->pluck('nama_tindakan_intervensi');
            $tindakan_kolaborasi = $tindakan_kolaborasi->pluck('nama_tindakan_intervensi');

            return response()->json([
                'message' => 'Success',
                'data'=> $intervensi,
                'observasi'=> $tindakan_observasi,
                'terapeutik'=> $tindakan_terapeutik,
                'edukasi'=> $tindakan_edukasi,
                'kolaborasi'=> $tindakan_kolaborasi,
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
        $kolaborasi = $request->input('kolaborasi');

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        DB::beginTransaction();

        try{

            $intervensi->kode_intervensi = $request->input('kode_intervensi');
            $intervensi->nama_intervensi = $request->input('nama_intervensi');

            $intervensi->update();

            if($observasi!=null){
                $i = 0;
                foreach($observasi as $obs){
                    $this->updateIntervensiAction($intervensi->id, $obs, 'Observasi', $i);
                    $i++;
                }
            }

            if($terapeutik!=null){
                $i = 0;
                foreach($terapeutik as $ter){
                    $this->updateIntervensiAction($intervensi->id, $ter, 'Terapeutik', $i);
                    $i++;
                }
            }

            if($edukasi!=null){
                $i = 0;
                foreach($edukasi as $edu){
                    $this->updateIntervensiAction($intervensi->id, $edu, 'Edukasi', $i);
                    $i++;
                }
            }

            if($kolaborasi!=null){
                $i = 0;
                foreach($kolaborasi as $kol){
                    $this->updateIntervensiAction($intervensi->id, $kol, 'Kolaborasi', $i);
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

    private function intervensi($id_intervensi, $data_tindakan, $jenis_tindakan){
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
}
