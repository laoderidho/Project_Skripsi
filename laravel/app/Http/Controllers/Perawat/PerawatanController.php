<?php

namespace App\Http\Controllers;

use App\Models\Perawatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PerawatanController extends Controller
{


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id_pasien'=>'required|bigInt|max:255',
            'id_data_diagnostik'=>'required|bigInt|max:255',
            'bed'=>'required|string|max:255',
            'waktu_pencatatan'=>'required|dateTime'
        ]);

        if($validator->fails()){
            return response() -> json(['message' => 'Data Failed']);
        }

        DB::beginTransaction();
        try{
            $perawatan = new Perawatan();
            $perawatan->id_pasien = $request->input('id_pasien');
            $perawatan->id_data_diagnostik = $request->input('id_data_diagnostik');
            $perawatan->bed = $request->input('bed');
            $perawatan->waktu_pencatatan = $request->input('waktu_pencatatan');
            $perawatan ->save();

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }
        return response()->json(['message' => 'Data berhasil ditambahkan', 'data' => $perawatan]);

    }
    public function show($id)
    {
        $perawatan = Perawatan::find($id);
        return response()->json($perawatan);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'id_pasien'=>'required|bigInt|max:255',
            'id_data_diagnostik'=>'required|bigInt|max:255',
            'bed'=>'required|string|max:255',
            'waktu_pencatatan'=>'required|dateTime'
        ]);

        if($validator->fails()){
            return response() -> json(['message' => 'Data Failed']);
        }

        DB::beginTransaction();
        try{
            $perawatan = Perawatan::find($id);
            $perawatan->id_pasien = $request->input('id_pasien');
            $perawatan->id_data_diagnostik = $request->input('id_data_diagnostik');
            $perawatan->bed = $request->input('bed');
            $perawatan->waktu_pencatatan = $request->input('waktu_pencatatan');
            $perawatan ->save();

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }
        return response()->json(['message' => 'Data berhasil diupdate', 'data' => $perawatan]);
    }

    public function destroy($id)
    {
        $perawatan = Perawatan::find($id);
        $perawatan->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
