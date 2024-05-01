<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Amnanessa;
use Illuminate\Support\Facades\Validator;

// admin log model
use App\Models\AdminLog;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Pasien;

class AmananessaController extends Controller
{
    public function add(Request $request, $id){
        $validator = Validator::make($request->all(),[
            'keluhan_utama'=> 'nullable|string|max:255',
            'riwayat_penyakit'=> 'nullable|string|max:255',
            'riwayat_alergi'=> 'nullable|string|max:255',
            'risiko_jatuh'=> 'nullable|string|max:255',
            'risiko_nyeri'=> 'nullable|string|max:255',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $amnanessa = new Amnanessa();
        $amnanessa->id_pasien = $id;
        $amnanessa->keluhan_utama = $request->keluhan_utama;
        $amnanessa->riwayat_penyakit = $request->riwayat_penyakit;
        $amnanessa->riwayat_alergi = $request->riwayat_alergi;
        $amnanessa->risiko_jatuh = $request->risiko_jatuh;
        $amnanessa->risiko_nyeri = $request->risiko_nyeri;
        $amnanessa->save();

        // admin log
        $adminLog = new AdminLog();
        $adminLog->user_id = Auth::user()->id;
        $adminLog->action = 'Menambahkan data amnanessa';
        $adminLog->save();

        return response()->json(['message' => 'Data berhasil disimpan', 'data' => $amnanessa], 200);
    }


    // edit
    public function edit(Request $request, $id){
        $amnanessa = Amnanessa::find($id);
        if($amnanessa){
            $validator = Validator::make($request->all(),[
                'keluhan_utama'=> 'nullable|string|max:255',
                'riwayat_penyakit'=> 'nullable|string|max:255',
                'riwayat_alergi'=> 'nullable|string|max:255',
                'risiko_jatuh'=> 'nullable|string|max:255',
                'risiko_nyeri'=> 'nullable|string|max:255',
            ]);

            if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
            }

            $amnanessa->keluhan_utama = $request->keluhan_utama;
            $amnanessa->riwayat_penyakit = $request->riwayat_penyakit;
            $amnanessa->riwayat_alergi = $request->riwayat_alergi;
            $amnanessa->risiko_jatuh = $request->risiko_jatuh;
            $amnanessa->risiko_nyeri = $request->risiko_nyeri;
            $amnanessa->save();

            // admin log
            $adminLog = new AdminLog();
            $adminLog->user_id = Auth::user()->id;
            $adminLog->action = 'Mengubah data amnanessa';
            $adminLog->save();

            return response()->json(['message' => 'Data berhasil diubah', 'data' => $amnanessa], 200);
        }
        return response()->json(['message' => 'Data tidak ditemukan', 'data' => null], 404);
    }

    // detail
    public function detail($id){
        $data = Pasien::select('nama_lengkap', 'keluhan_utama', 'riwayat_penyakit', 'riwayat_alergi', 'risiko_jatuh', 'risiko_nyeri')
        ->join('amnanessa', 'pasien.id', '=', 'amnanessa.id_pasien')
        ->where('pasien.id', $id)
        ->first();

        if($data){
            return response()->json(['message' => 'Data berhasil diambil', 'data' => $data], 200);
        }

        return response()->json(['message' => 'Data tidak ditemukan', 'data' => null], 404);
    }
}
