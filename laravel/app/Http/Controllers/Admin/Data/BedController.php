<?php

namespace App\Http\Controllers\Admin\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Bed;
use Illuminate\Support\Facades\Validator;

class BedController extends Controller
{

    public function getBed(){
        $bed = Bed::all();

        return response()->json(['data' => $bed]);
    }

    public function addBed(Request $request){
        $validator = Validator::make($request->all(),[
            'no_bed'=>'required|string|max:255',
            'lantai'=>'required|string|max:255',
            'nama_fasilitas'=>'required|string|max:255',
            'nama_ruangan'=>'required|string|max:255'
        ]);

        if($validator->fails()){
            return response() -> json($validator->errors()->toJson(), 400);
        }

        $bed = new Bed();
        $bed->no_bed = $request->input('no_bed');
        $bed->lantai = $request->input('lantai');
        $bed->nama_fasilitas = $request->input('nama_fasilitas');
        $bed->nama_ruangan = $request->input('nama_ruangan');
        $bed->status = 0;
        $bed->save();

        return response()->json(['message' => 'Data berhasil ditambahkan']);
    }

    public function filterBed(){
        $bed = Bed::where('status', 0)->get();

        return response()->json(['data' => $bed]);
    }
}
