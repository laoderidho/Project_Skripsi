<?php

namespace App\Http\Controllers\Admin\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Bed;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\AdminLog;
use Illuminate\Support\Facades\Auth;

class BedController extends Controller
{

    public function getBed()
    {
        $bed = Bed::all();

        return response()->json(['data' => $bed]);
    }

    public function filterBedWithAll($nama_fasilitas, $jenisRuangan, $lantai){
        $bed =Bed::select('id','no_bed')
        ->where('nama_fasilitas', $nama_fasilitas)
        ->where('jenis_ruangan', $jenisRuangan)
        ->where('lantai', $lantai)
        ->where('status', 0)
        ->get();

        return response()->json(['data' => $bed]);
    }

    public function addBed(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'no_bed' => 'required|string|max:255',
            'lantai' => 'required|string|max:255',
            'nama_fasilitas' => 'required|string|max:255',
            'jenis_ruangan' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $bed = new Bed();
        $bed->no_bed = $request->input('no_bed');
        $bed->lantai = $request->input('lantai');
        $bed->nama_fasilitas = $request->input('nama_fasilitas');
        $bed->jenis_ruangan = $request->input('jenis_ruangan');
        $bed->status = 0;
        $bed->save();

        AdminLog::create([
            'user_id' => Auth::user()->id,
            'action' => 'Menambah kamar',
        ]);


        return response()->json(['message' => 'Data berhasil ditambahkan']);
    }

    public function filterBed()
    {
        $bed = Bed::where('status', 0)->get();

        return response()->json(['data' => $bed]);
    }

    public function editBed(Request $request, $id)
    {
        $bed = Bed::find($id);

        if ($bed == null) {
            return response()->json([
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'no_bed' => 'required|string|max:255',
            'lantai' => 'required|string|max:255',
            'no_kamar' => 'required|string|max:255',
            'jenis_ruangan' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $bed->no_bed = $request->input('no_bed');
        $bed->lantai = $request->input('lantai');
        $bed->no_kamar = $request->input('no_kamar');
        $bed->jenis_ruangan = $request->input('jenis_ruangan');
        $bed->update();

        AdminLog::create([
            'user_id' => Auth::user()->id,
            'action' => 'Mengupdate kamar',
        ]);


        return response()->json(['message' => 'Data berhasil diubah']);
    }


    public function deleteBed($id)
    {
        $bed = Bed::find($id);

        if ($bed == null) {
            return response()->json([
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        $bed->delete();

        AdminLog::create([
            'user_id' => Auth::user()->id,
            'action' => 'Menghapus kamar',
        ]);

        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
