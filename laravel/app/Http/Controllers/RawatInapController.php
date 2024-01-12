<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Perawat\RawatInap;
use Illuminate\Support\Facades\Validator;

class RawatInapController extends Controller
{

    public function index()
    {
        $rawat_inap = RawatInap::all();
        return response()->json($rawat_inap);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id_pasien'=>'required|bigInt|max:255',
            'triase'=>'required|string|max:255',
            'status'=>'required|boolean'
        ]);

        if($validator->fails()){
            return response() -> json(['message' => 'Data Failed']);
        }

        DB::beginTransaction();
        try{
            $rawat_inap = new RawatInap();
            $rawat_inap->id_pasien = $request->input('id_pasien');
            $rawat_inap->triase = $request->input('triase');
            $rawat_inap->status = $request->input('status');
            $rawat_inap ->save();

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }
        return response()->json(['message' => 'Data berhasil ditambahkan', 'data' => $rawat_inap]);

    }
    public function show($id)
    {
        $rawat_inap = RawatInap::find($id);
        return response()->json($rawat_inap);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'id_pasien'=>'required|bigInt|max:255',
            'triase'=>'required|string|max:255',
            'status'=>'required|boolean'
        ]);

        if($validator->fails()){
            return response() -> json(['message' => 'Data Failed']);
        }

        DB::beginTransaction();
        try{
            $rawat_inap = RawatInap::find($id);
            $rawat_inap->id_pasien = $request->input('id_pasien');
            $rawat_inap->triase = $request->input('triase');
            $rawat_inap->status = $request->input('status');
            $rawat_inap ->save();

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }
        return response()->json(['message' => 'Data berhasil diupdate', 'data' => $rawat_inap]);
    }

    public function destroy($id)
    {
        $rawat_inap = RawatInap::find($id);
        $rawat_inap->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
