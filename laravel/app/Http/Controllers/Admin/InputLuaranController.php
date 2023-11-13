<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\Luaran;
use App\Models\Admin\KriteriaLuaran;

class InputLuaranController extends Controller
{
    public function getLuaran()
    {
        $luaran = Luaran::all();

        return response()->json([
            'message' => 'Success',
            'data' => $luaran,
        ]);
    }

    public function createLuaran(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_luaran' => 'required|string|max:255|unique:luaran',
            'nama_luaran' => 'required|string|max:255',
            'nama_kriteria_luaran' => 'required|array',
            // 'nama_kriteria_luaran.*' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        DB::beginTransaction();
        try {
            $luaran = new Luaran([
                'kode_luaran' => $request->input('kode_luaran'),
                'nama_luaran' => $request->input('nama_luaran'),
            ]);

            $luaran->save();

            foreach ($request->input('nama_kriteria_luaran') as $kriteria) {
                $this->luaranAction($luaran->id, $kriteria);
            }

            DB::commit();
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
            return response()->json(['message' => 'Failed to create Luaran'], 500);
        }

        return response()->json(['message' => 'Luaran successfully added', 'luaran' => $luaran], 201);
    }

    private function luaranAction($id_luaran, $nama_kriteria)
    {
        $kriteria_luaran = new KriteriaLuaran([
            'id_luaran' => $id_luaran,
            'nama_kriteria_luaran' => $nama_kriteria,
        ]);

        $kriteria_luaran->save();
    }

    public function detailLuaran($id)
    {
        \Log::info('createLuaran method called.');
        // \Log::info($request->all());

        $luaran = Luaran::find($id);

        if (!$luaran) {
            return response()->json(['message' => 'Luaran data not found'], 404);
        }

        // Check if $luaran is not null before accessing its properties
        if (!is_null($luaran)) {
            $kriteriaLuaran = KriteriaLuaran::where('id_luaran', $luaran->id)->pluck('nama_kriteria_luaran')->toArray();
        } else {
            $kriteriaLuaran = [];
        }

        $result = [
            'kode_luaran' => $luaran->kode_luaran,
            'nama_luaran' => $luaran->nama_luaran,
            'nama_kriteria_luaran' => $kriteriaLuaran,
        ];

        return response()->json(['data' => $result]);
    }


    public function update(Request $request, $id_luaran)
    {
        $validator = Validator::make($request->all(), [
            'nama_luaran' => 'required|string|max:255',
            'nama_kriteria_luaran' => 'required|array',
            'nama_kriteria_luaran.*' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $luaran = Luaran::find($id_luaran);

        // if (!$luaran) {
        //     return response()->json(['message' => 'Luaran data not found'], 404);
        // }

        DB::beginTransaction();
        try {
            $luaran->update([
                'nama_luaran' => $request->input('nama_luaran'),
            ]);

            KriteriaLuaran::where('id_luaran', $luaran->id)->delete();

            foreach ($request->input('nama_kriteria_luaran') as $kriteria) {
                $this->luaranAction($luaran->id, $kriteria);
            }

            DB::commit();
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
            return response()->json(['message' => 'Failed to update Luaran'], 500);
        }

        return response()->json(['message' => 'Luaran data successfully updated']);
    }

    public function delete($id_luaran)
    {
        $luaran = Luaran::where('id', $id_luaran)->first();

        // if (!$luaran) {
        //     return response()->json(['message' => 'Luaran data not found'], 404);
        // }

        DB::beginTransaction();
        try {
            KriteriaLuaran::where('id_luaran', $luaran->id)->delete();
            $luaran->delete();
            DB::commit();
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
            return response()->json(['message' => 'Failed to delete Luaran'], 500);
        }

        return response()->json(['message' => 'Luaran data successfully deleted']);
    }
}
