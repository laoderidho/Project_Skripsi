<?php

namespace App\Http\Controllers\Admin\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Pasien;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Perawat\RawatInap;
use Illuminate\Support\Facades\DB;

class PasienController extends Controller
{

    public function getPasien()
    {
        $pasien = Pasien::all();

        return response()->json([
            'message' => 'Success',
            'data' => $pasien,
        ]);
    }

    public function addPasien(Request $request)
    {
        $user = Auth::user()->id;
        // validation
        $validator = Validator::make(
            $request->all(),
            [
                'nama_lengkap' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date',
                'jenis_kelamin' => 'required|boolean',
                'no_telepon' => 'required|string|max:255',
                'alamat' => 'required|string|max:255',
                'status_pernikahan' => 'required|boolean',
                'nik' => 'required|string|max:255',
                'alergi' => 'nullable|string|max:255',
                'nama_asuransi' => 'nullable|string|max:255',
                'no_asuransi' => 'nullable|string|max:255',
                'no_medical_record' => 'required|string|max:255',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $pasien = Pasien::create([
            'nama_lengkap' => $request->nama_lengkap,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
            'status_pernikahan' => $request->status_pernikahan,
            'nik' => $request->nik,
            'alergi' => $request->alergi,
            'nama_asuransi' => $request->nama_asuransi,
            'no_asuransi' => $request->no_asuransi,
            'no_medical_record' => $request->no_medical_record,
        ]);

        $date = date('H:i:s');

        return response()->json([
            'message' => 'Pasien Added',
            'user' => $user,
            'data' => $pasien,
            'date' => $date,
        ]);
    }

    public function delete($id)
    {
        $pasien = Pasien::find($id);
        $pasien->delete();

        return response()->json([
            'message' => 'Pasien Deleted',
            'data' => $pasien,
        ]);
    }

    public function getDetail($id)
    {
        $pasien = Pasien::find($id);

        return response()->json([
            'message' => 'Pasien Detail',
            'data' => $pasien,
        ]);
    }

    public function update(Request $request, $id)
    {
        $pasien = Pasien::find($id);

        $validator = Validator::make(
            $request->all(),
            [
                'nama_lengkap' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date',
                'jenis_kelamin' => 'required|boolean',
                'no_telepon' => 'required|string|max:255',
                'alamat' => 'required|string|max:255',
                'status_pernikahan' => 'required|boolean',
                'nik' => 'required|string|max:255',
                'alergi' => 'nullable|string|max:255',
                'nama_asuransi' => 'nullable|string|max:255',
                'no_asuransi' => 'nullable|string|max:255',
                'no_medical_record' => 'required|string|max:255',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }


        $pasien->nama_lengkap = $request->nama_lengkap;
        $pasien->tanggal_lahir = $request->tanggal_lahir;
        $pasien->jenis_kelamin = $request->jenis_kelamin;
        $pasien->no_telepon = $request->no_telepon;
        $pasien->alamat = $request->alamat;
        $pasien->status_pernikahan = $request->status_pernikahan;
        $pasien->nik = $request->nik;
        $pasien->alergi = $request->alergi;
        $pasien->nama_asuransi = $request->nama_asuransi;
        $pasien->no_asuransi = $request->no_asuransi;
        $pasien->no_medical_record = $request->no_medical_record;


        $pasien->update();

        return response()->json([
            'message' => 'Pasien Updated',
            'data' => $pasien,
        ]);
    }

    // add rawat inap function
    public function addRawatInap(Request $request, $id)
    {
        $pasien = Pasien::find($id);

        // dd($pasien->id);

        if ($pasien) {
            $findIdByTriase = RawatInap::where('id_pasien', $pasien->id)->first();
            if ($findIdByTriase) {
                $findIdByTriase->status = $request->status;
                $findIdByTriase->triase = $request->triase;
                $findIdByTriase->update();
                return response()->json(['message' => 'status Pasien Telah diubah']);
            } else {
                $rawatInap = new RawatInap();
                $rawatInap->id_pasien = $pasien->id;
                $rawatInap->status = $request->input('status');
                $rawatInap->triase = $request->input('triase');
                $rawatInap->save();

                return response()->json(['message' => 'Pasien telah berhasil ditambahkan ke rawat inap']);
            }
        }
    }

    public function getStatusRawatInap($id)
    {
        $pasien = Pasien::find($id);
        $rawatInap = RawatInap::where('id_pasien', $pasien->id)->first();

        if ($rawatInap) {
            if ($rawatInap->status == 1) {
                $triase = $rawatInap->triase;
                $rawatInap = $triase;
                return response()->json(['message' => $rawatInap]);
            } else {
                $rawatInap = 'tidak dirawat inap';
                return response()->json(['message' => $rawatInap]);
            }
        } else {
            $rawatInap = 'tidak dirawat inap';
            return response()->json(['message' => $rawatInap]);
        }
    }

    public function filterStatusRawatInap()
    {
        $results = DB::table('pasien as p')
            ->select('pr.id as perawatan_id', 'p.id', 'p.nama_lengkap', 'r.triase', 'p.no_medical_record')
            ->join('perawatan as pr', 'p.id', '=', 'pr.id_pasien')
            ->join('rawat_inap as r', 'p.id', '=', 'r.id_pasien')
            ->where('pr.status_pasien', '=', 'sakit')
            ->orderByRaw("CASE
                WHEN r.triase = 'merah' THEN 1
                WHEN r.triase = 'kuning' THEN 2
                WHEN r.triase = 'hijau' THEN 3
                WHEN r.triase = 'hitam' THEN 4
                ELSE 5 END")
            ->get();

        return response()->json([
            'message' => 'Success',
            'data' => $results,
        ]);
    }
}
