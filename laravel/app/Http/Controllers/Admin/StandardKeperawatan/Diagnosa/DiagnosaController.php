<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Diagnosa;
use Illuminate\Support\Facades\Validator;

class DiagnosaController extends Controller
{
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id_diagnosa' => 'required|string|max:255',
            'nama_diagnosa' => 'required|string|max:255',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $diagnosa = new Diagnosa();
        $diagnosa->fill($request->all());
        $diagnosa->save();

        return response()->json(['message' => 'Diagnosa berhasil ditambahkan']);
    }

    public function show(string $id)
    {
        $diagnosa = Diagnosa::find($id);
        if ($diagnosa) {
            return response()->json($pasien);
        } else {
            return response()->json(['message' => 'Diagnosa tidak ditemukan'], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $diagnosa = Diagnosa::find($id);
        if ($diagnosa) {
            return response()->json($diagnosa);
        } else {
            return response()->json(['message' => 'Diagnosa tidak ditemukan'], 404);
        }
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(),[
            'id_diagnosa' => 'required|string|max:255',
            'nama_diagnosa' => 'required|string|max:255',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $diagnosa = Diagnosa::find($id);
        if (!$diagnosa) {
            return response()->json(['message' => 'Diagnosa tidak ditemukan'], 404);
        }

        $diagnosa->fill($request->all());
        $diagnosa->save();

        return response()->json(['message' => 'Diagnosa berhasil diperbarui']);
    }

    public function destroy(string $id)
    {
        $diagnosa = Diagnosa::find($id);
        if ($diagnosa) {
            $diagnosa->delete();
            return response()->json(['message' => 'Diagnosa berhasil dihapus']);
        } else {
            return response()->json(['message' => 'Diagnosa tidak ditemukan'], 404);
        }
    }

}
