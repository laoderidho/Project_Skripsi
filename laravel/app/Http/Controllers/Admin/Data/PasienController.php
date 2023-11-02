<?php

namespace App\Http\Controllers\Admin\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Data\Pasien\Pasien;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class PasienController extends Controller
{

    public function getPasien(){
        $pasien = Pasien::all();

        return response()->json([
            'message'=> 'Success',
            'data'=> $pasien,
        ]);
    }

    public function addPasien(Request $request){
        $user = Auth::user()->id;
        // validation
        $validator = Validator::make($request->all(),
        [
            'nama_lengkap' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|boolean',
            'no_telepon' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'status_pernikahan' => 'required|string|max:255',
            'nik' => 'required|string|max:255',
            'nama_asuransi' => 'nullable|string|max:255',
            'no_asuransi' => 'nullable|string|max:255',
            'no_medical_record' => 'required|string|max:255',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $pasien = Pasien::create([
            'nama_lengkap'=> $request->nama_lengkap,
            'tanggal_lahir'=> $request->tanggal_lahir,
            'jenis_kelamin'=> $request->jenis_kelamin,
            'no_telepon'=> $request->no_telepon,
            'alamat'=> $request->alamat,
            'status_pernikahan'=> $request->status_pernikahan,
            'nik'=> $request->nik,
            'nama_asuransi'=> $request->nama_asuransi,
            'no_asuransi'=> $request->no_asuransi,
            'no_medical_record'=> $request->no_medical_record,
        ]);

        $date = date('H:i:s');

        return response()->json([
            'message'=> 'Pasien Added',
            'user'=> $user,
            'data'=> $pasien,
            'date'=> $date,
        ]);
    }

    public function delete($id){
        $pasien = Pasien::find($id);
        $pasien->delete();

        return response()->json([
            'message'=> 'Pasien Deleted',
            'data'=> $pasien,
        ]);
    }

    public function getDetail($id){
        $pasien = Pasien::find($id);

        return response()->json([
            'message'=> 'Pasien Detail',
            'data'=> $pasien,
        ]);
    }

    public function update(Request $request, $id){
        $pasien = Pasien::find($id);

        $validator = Validator::make($request->all(),
        [
            'nama_lengkap' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|boolean',
            'no_telepon' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'status_pernikahan' => 'required|string|max:255',
            'nik' => 'required|string|max:255',
            'nama_asuransi' => 'nullable|string|max:255',
            'no_asuransi' => 'nullable|string|max:255',
            'no_medical_record' => 'required|string|max:255',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }


        $pasien->nama_lengkap = $request->nama_lengkap;
        $pasien->tanggal_lahir = $request->tanggal_lahir;
        $pasien->jenis_kelamin = $request->jenis_kelamin;
        $pasien->no_telepon = $request->no_telepon;
        $pasien->alamat = $request->alamat;
        $pasien->status_pernikahan = $request->status_pernikahan;
        $pasien->nik = $request->nik;
        $pasien->nama_asuransi = $request->nama_asuransi;
        $pasien->no_asuransi = $request->no_asuransi;
        $pasien->no_medical_record = $request->no_medical_record;


        $pasien->update();

        return response()->json([
            'message'=> 'Pasien Updated',
            'data'=> $pasien,
        ]);
    }
}
