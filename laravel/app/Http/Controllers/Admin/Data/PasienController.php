<?php

namespace App\Http\Controllers\Admin\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Pasien;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Perawat\RawatInap;
use App\Models\Perawat\Perawatan;
use App\Models\Admin\Bed;
use App\Models\AdminLog;
use Illuminate\Support\Facades\DB;

class PasienController extends Controller
{

    // pasien Crud
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


        AdminLog::create([
            'user_id' => $user,
            'action' => 'Menambahkan Pasien',
        ]);

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

        AdminLog::create([
            'user_id' => Auth::user()->id,
            'action' => 'Menghapus Pasien',
        ]);

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

        AdminLog::create([
            'user_id' => Auth::user()->id,
            'action' => 'Mengupdate Pasien',
        ]);

        return response()->json([
            'message' => 'Pasien Updated',
            'data' => $pasien,
        ]);
    }



    // rawat inap
    public function addRawatInap(Request $request, $id)
    {
        $pasien = Pasien::find($id);

        if (!$pasien) {
            return response()->json(['message' => 'Pasien tidak ditemukan']);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'no_bed' => 'required|string|max:255',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        db::beginTransaction();
        try {
            $bed = Bed::find($request->input('no_bed'));

            if (!$bed) {
                return response()->json(['message' => 'kamar tidak ditemukan']);
            } else {
                if ($bed->status == 1) {
                    return response()->json(['message' => 'kamar sudah terisi']);
                } else {
                    $bed->status = 1;
                    $bed->update();
                }
            }

            $perawatan = new Perawatan();
            $perawatan->id_pasien = $pasien->id;
            $perawatan->bed = $request->input('no_bed');
            $perawatan->tanggal_masuk = now();
            $perawatan->waktu_pencatatan = now();
            $perawatan->status_pasien = 'sakit';
            $perawatan->save();

            AdminLog::create([
                'user_id' => Auth::user()->id,
                'action' => 'Menambah Rawat Inap Pasien',
            ]);


            db::commit();
            return response()->json([
                'message' => 'Pasien berhasil di tambah di rawat inap'
            ], 201);
        } catch (\Exception $e) {
            db::rollback();
            return response()->json([
                'message' => 'gagal ditambahkan',
                'error' => $e->getMessage()
            ]);
        }


        return response()->json([
            'message' => 'Pasien berhasil di tambah di rawat inap'
        ]);
    }

    public function filterStatusRawatInap()
    {
        $result = "SELECT
                    pr.id AS perawatan_id,
                    p.id,
                    p.nama_lengkap,
                    p.no_medical_record,
                    pr.status_pasien,
                    p.tanggal_lahir,
                    p.nik,
                    p.no_telepon,
                    p.alamat,
                    p.alergi,
                    p.nama_asuransi,
                    p.no_asuransi,
                    p.jenis_kelamin,
                    p.status_pernikahan
                FROM
                    pasien AS p
                JOIN
                    perawatan AS pr ON p.id = pr.id_pasien
                WHERE
                    pr.status_pasien = 'sakit'
                ";

        $results = DB::select($result);

        return response()->json([
            'message' => 'Success',
            'data' => $results,
        ]);
    }

    public function getDateRawatInapPasien($id)
    {
        $dataRawatInap = "SELECT
                            pr.id,
                            pr.id_pasien,
                            p.nama_lengkap,
                            DATE_FORMAT(pr.tanggal_masuk, '%d-%m-%Y') AS tanggal_masuk,
                            DATE_FORMAT(pr.tanggal_keluar, '%d-%m-%Y') AS tanggal_keluar,
                            DATE_FORMAT(pr.waktu_pencatatan, '%H:%i') AS jam_masuk,
                            date_format(pr.waktu_keluar, '%H:%i') as jam_keluar,
                            pr.status_pasien as status
                            FROM
                                perawatan pr
							join
								pasien p
							on  pr.id_pasien = p.id
                            WHERE
                                id_pasien = $id";

        $dataRawatInap = DB::select($dataRawatInap);
        return response()->json([
            'message' => 'Success',
            'data' => $dataRawatInap,
        ]);
    }

    // public function getdataPasienRawatInap()
    // {
    //     $results = DB::table('pasien as p')
    //         ->select('r.id_pasien', 'p.nama_lengkap', 'r.triase', 'p.no_medical_record', 'r.tanggal_masuk')
    //         ->join('rawat_inap as r', 'p.id', '=', 'r.id_pasien')
    //         ->where('r.status', '=', 0)
    //         ->orderByRaw("CASE
    //         WHEN r.triase = 'merah' THEN 1
    //         WHEN r.triase = 'kuning' THEN 2
    //         WHEN r.triase = 'hijau' THEN 3
    //         WHEN r.triase = 'hitam' THEN 4
    //         ELSE 5 END")
    //         ->get();

    //     return response()->json([
    //         'message' => 'Success',
    //         'data' => $results,
    //     ]);
    // }


    public function pasienRawatInap()
    {
        $pasien = "select p.id, ps.nama_lengkap, b.no_bed, p.tanggal_masuk, b.no_kamar
                    from pasien ps
                    join perawatan p on p.id_pasien = ps.id
                    join beds b on p.bed = b.id
                    where p.status_pasien = 'sakit' ";

        $data = DB::select($pasien);

        return response()->json([
            'message' => 'Success',
            'data' => $data,
        ]);
    }



    public function getDetailRawatInap($id_rawat_inap)
    {
        $perawatan = Perawatan::find($id_rawat_inap);

        $beds = bed::find($perawatan->bed);

        return response()->json([
            'message' => 'Success',
            'bed' => $beds,
        ]);
    }

    public function UpdateRawatInap(Request $request, $id_rawat_inap)
    {
        $validator = Validator::make($request->all(), [
            'no_bed' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }


        db::beginTransaction();

        try {

            $perawatan = Perawatan::find($id_rawat_inap);
            $bed = Bed::find($perawatan->bed);
            $bed->status = 0;
            $bed->update();

            $no_bed = intval($request->no_bed);
            $newBed = Bed::find($no_bed);
            $newBed->status = 1;
            $newBed->update();

            $perawatan->bed = $request->no_bed;
            $perawatan->update();

            AdminLog::create([
                'user_id' => Auth::user()->id,
                'action' => 'Mengupdate Rawat Inap Pasien',
            ]);


            db::commit();

            return response()->json([
                'message' => 'Rawat Inap Updated',
            ]);
        } catch (\Exception $e) {
            db::rollback();
            return response()->json([
                'message' => 'gagal diupdate',
                'error' => $e->getMessage()
            ]);
        }
    }

    public  function deleteRawatInap($id)
    {
        $perawatan = Perawatan::find($id);

        if (!$perawatan) {
            return response()->json([
                'message' => 'Rawat Inap tidak ditemukan',
            ], 404);
        }

        if ($perawatan->status_pasien == 'sembuh' && $perawatan->tanggal_keluar != null) {
            return response()->json([
                'message' => 'data tidak bisa dihapus',
            ], 400);
        }

        db::beginTransaction();
        try {
            $perawatan->delete();


            $bed = Bed::find($perawatan->bed);
            $bed->status = 0;
            $bed->update();

            AdminLog::create([
                'user_id' => Auth::user()->id,
                'action' => 'Menghapus Rawat Inap Pasien',
            ]);

            db::commit();

            return response()->json([
                'message' => 'Rawat Inap Deleted',
            ]);
        } catch (\Exception $e) {
            db::rollback();
            return response()->json([
                'message' => 'gagal dihapus',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function updatePasienRecover($id)
    {
        $perawatan = Perawatan::find($id);

        if (!$perawatan) {
            return response()->json([
                'message' => 'data tidak ada',
            ], 400);
        }

        db::beginTransaction();

        try {

            $perawatan->tanggal_keluar = now();
            $perawatan->waktu_keluar = now();
            $perawatan->status_pasien = 'sembuh';
            $perawatan->update();

            $bed = Bed::find($perawatan->bed);
            $bed->status = 0;
            $bed->update();

            AdminLog::create([
                'user_id' => Auth::user()->id,
                'action' => 'Menambahkan Pasien Sembuh',
            ]);
            db::commit();

            return response()->json([
                'message' => 'Pasien telah sembuh',
            ]);
        } catch (\Exception $e) {
            db::rollback();
            return response()->json([
                'message' => 'gagal diupdate',
                'error' => $e->getMessage()
            ]);
        }
    }
}
