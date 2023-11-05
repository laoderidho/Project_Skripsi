<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Intervensi;
use Illuminate\Support\Facades\Validator;

class IntervensiController extends Controller
{

   public function AddIntervensi(Request $request){
        $validator = Validator::make($request->all(),[
            'kode_intervensi'=> 'required|string|max:10|unique:intervensi',
            'nama_intervensi'=> 'required|string|max:255',
            'definisi_intervensi' => 'required|string|max:255',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $intervensi = Intervensi::create([
            'kode_intervensi' => $request->get('kode_intervensi'),
            'nama_intervensi' => $request->get('nama_intervensi'),
            'definisi_intervensi' => $request->get('definisi_intervensi'),
        ]);
   }

}
