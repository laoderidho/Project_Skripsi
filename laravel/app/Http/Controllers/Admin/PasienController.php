<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Pasien;
use Illuminate\Support\Facades\Validator;

class PasienController extends Controller
{

    public function index()
    {
        $pasien = Pasien::all();
        return response()->json($pasien);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
        'nama'=> 'required|string|max:255',
        'tanggal_lahir'=>'required|date',
        'jenis_kelamin' =>'required|boolean',
        'alamat'=> 'required|string|max:255',
        'no_telepon'=> 'required|string|max:255',
        'status_pernikahan' => 'required|boolean',
        'nik' => 'string|max:255|nullable',
        'penyedia_asuransi' => 'string|max:255|nullable',
        'no_asuransi' => 'string|max:255|nullable',
        'no_medical_record' => 'require|string|max:9|unique',
        'alergi' =>'string|max:255',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $no_medical_record = $this->generateRandomCharacter(8);
        $request->merge(['no_medical_record' => $no_medical_record]); // Menambahkan no_medical_record ke data request

        $pasien = new Pasien();
        $pasien->fill($request->all());
        $pasien->save();

        return response()->json(['message' => 'Pasien telah berhasil ditambahkan']);
    }

function generateRandomCharacter($length)
{
    $characters ='0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $randomString;
}

public function show($id)
    {
        $pasien = Pasien::find($id);
        if ($pasien) {
            return response()->json($pasien);
        } else {
            return response()->json(['message' => 'Pasien tidak ditemukan'], 404);
        }
    }


public function update(Request $request, $id)
    {
        $pasien = Pasien::find($id);
        if ($pasien) {
            $pasien->nama = $request->input('nama');
            $pasien->tanggal_lahir = $request->input('tanggal_lahir');
            $pasien->jenis_kelamin = $request->input('jenis_kelamin');
            $pasien->alamat = $request->input('alamat');
            $pasien->no_telepon = $request->input('no_telepon');
            $pasien->status_pernikahan = $request->input('status_pernikahan');
            $pasien->no_karyawan = $request->input('no_karyawan');
            $pasien->nik = $request->input('nik');
            $pasien->penyedia_asuransi = $request->input('penyedia_asuransi');
            $pasien->no_asuransi = $request->input('no_asuransi');
            $pasien->no_status = $request->input('status');
            $pasien->no_medical_record = $request->input('no_medical_record');

            $pasien->save();

            return response()->json(['message' => 'Data pasien berhasil diperbarui', 'data' => $pasien]);
        } else {
            return response()->json(['message' => 'Pasien tidak ditemukan'], 404);
        }
    }

    /**
     * Menghapus pasien berdasarkan ID dan mengembalikan response dalam format JSON.
     */
    public function destroy($id)
    {
        $pasien = Pasien::find($id);
        if ($pasien) {
            $pasien->delete();
            return response()->json(['message' => 'Pasien telah berhasil dihapus']);
        } else {
            return response()->json(['message' => 'Pasien tidak ditemukan'], 404);
        }
    }
}
