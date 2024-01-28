<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Perawat\Bed;
use Illuminate\Support\Facades\Validator;

class RawatInapController extends Controller
{

    public function index()
    {
        $beds = Bed::all();
        return response()->json($beds);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'no_bed'=>'required|string|max:255',
            'status'=>'required|boolean'
        ]);

        if($validator->fails()){
            return response() -> json(['message' => 'Data Failed']);
        }

        DB::beginTransaction();
        try{
            $bed = new Bed();
            $bed->no_bed = $request->input('no_bed');
            $bed->status = $request->input('status');
            $bed ->save();

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }
        return response()->json(['message' => 'Data berhasil ditambahkan', 'data' => $bed]);

    }
    public function show($id)
    {
        $bed = Bed::find($id);
        return response()->json($bed);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'no_bed'=>'required|string|max:255',
            'status'=>'required|boolean'
        ]);

        if($validator->fails()){
            return response() -> json(['message' => 'Data Failed']);
        }

        DB::beginTransaction();
        try{
            $bed = Bed::find($id);
            $bed->no_bed = $request->input('no_bed');
            $bed->status = $request->input('status');
            $bed ->save();

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }
        return response()->json(['message' => 'Data berhasil diupdate', 'data' => $bed]);
    }

    public function destroy($id)
    {
        $bed = Bed::find($id);
        $bed->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
