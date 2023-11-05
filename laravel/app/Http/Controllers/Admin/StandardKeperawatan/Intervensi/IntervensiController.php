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
        return response()->json(['message' => 'Intervensi telah berhasil ditambahkan']);
    }

    public function show($id)
    {
        $intervensi = Intervensi::find($id);
        if ($intervensi) {
            return response()->json($intervensi);
        } else {
            return response()->json(['message' => 'Intervensi tidak ditemukan'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $intervensi = Intervensi::find($id);
        if ($intervensi) {
            $intervensi->id_intervensi = $request->input('id_intervensi');
            $intervensi->kode_intervensi = $request->input('kode_intervensi');
            $intervensi->nama_intervensi = $request->input('nama_intervensi');
            $intervensi->deskripsi = $request->input('deskripsi');
            // Update field lain sesuai kebutuhan

            $intervensi->save();

            return response()->json(['message' => 'Data intervensi berhasil diperbarui', 'data' => $intervensi]);
        } else {
            return response()->json(['message' => 'Intervensi tidak ditemukan'], 404);
        }
    }

}
