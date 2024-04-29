<?php

namespace App\Http\Controllers\Admin\StandardKeperawatan\Diagnosa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\Diagnosa;
use App\Models\Admin\FaktorResiko;
use App\Models\Admin\DetailPenyebab;
use App\Models\Admin\JenisPenyebab;
use App\Models\Admin\JenisGejala;
use App\Models\Admin\KategoriGejala;
use App\Models\Admin\Gejala;
// modal admin log
use App\Models\AdminLog;
// auth
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InputDiagnosaController extends Controller
{
    public function getDiagnosa()
    {
        $diagnosa = Diagnosa::all();

        return response()->json([
            'message' => 'Sukses',
            'data' => $diagnosa,
        ]);
    }

    public function AddDiagnosa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_diagnosa' => 'required|string|max:255|unique:diagnosa,kode_diagnosa',
            'nama_diagnosa' => 'required|string|max:255|unique:diagnosa,nama_diagnosa',
            'faktor_risiko' => 'array|nullable',
            'penyebab_psikologis' => 'array|nullable',
            'penyebab_umum' => 'array|nullable',
            'penyebab_situasional' => 'array|nullable',
            'penyebab_fisiologis' => 'array|nullable',
            'gejala_mayor_subjektif' => 'array|nullable',
            'gejala_mayor_objektif' => 'array|nullable',
            'gejala_minor_subjektif' => 'array|nullable',
            'gejala_minor_objektif' => 'array|nullable',

        ]);

        // dd($request->all());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        DB::beginTransaction();
        try {
            $diagnosa = new Diagnosa();
            $diagnosa->kode_diagnosa = $request->input('kode_diagnosa');
            $diagnosa->nama_diagnosa = $request->input('nama_diagnosa');
            $diagnosa->save();
            $diagnosaId = $diagnosa->id;

            $faktor_resiko = $request->input('faktor_risiko');

            if ($faktor_resiko != null) {
                $faktorResikoArray = $faktor_resiko;

                foreach ($faktorResikoArray as $faktor_item) {
                    $this->saveFaktorResiko($diagnosaId, $faktor_item);
                }
            } else {

            }

            // Gejala Mayor Subjektif

            $gejala_mayor_subjektif = $request->input('gejala_mayor_subjektif');
            if ($gejala_mayor_subjektif != null) {
                foreach ($gejala_mayor_subjektif as $gejala_mayor_subjektif_item) {
                    $this->saveGejala($gejala_mayor_subjektif_item, 'Mayor', 'Subjektif', $diagnosaId);
                }
            } else {

            }
            // Untuk gejala_mayor_objektif
            $gejala_mayor_objektif = $request->input('gejala_mayor_objektif');
            if ($gejala_mayor_objektif != null) {
                foreach ($gejala_mayor_objektif as $gejala_mayor_objektif_item) {
                    $this->saveGejala($gejala_mayor_objektif_item, 'Mayor', 'Objektif', $diagnosaId);
                }
            } else {

            }

            // Untuk gejala_minor_subjektif
            $gejala_minor_subjektif = $request->input('gejala_minor_subjektif');
            if ($gejala_minor_subjektif != null) {
                foreach ($gejala_minor_subjektif as $gejala_minor_subjektif_item) {
                    $this->saveGejala($gejala_minor_subjektif_item, 'Minor', 'Subjektif', $diagnosaId);
                }
            } else {

            }

            // Untuk gejala_minor_objektif
            $gejala_minor_objektif = $request->input('gejala_minor_objektif');
            if ($gejala_minor_objektif != null) {
                foreach ($gejala_minor_objektif as $gejala_minor_objektif_item) {
                    $this->saveGejala($gejala_minor_objektif_item, 'Minor', 'Objektif', $diagnosaId);
                }
            } else {

            }

            // Penyebab Psikologis
            // Array of penyebab types
            $penyebabTypes = ['psikologis', 'situasional', 'fisiologis','umum'];

            foreach ($penyebabTypes as $penyebabType) {
                $inputName = 'penyebab_' . $penyebabType;
                $penyebabData = $request->input($inputName);
                if ($penyebabData != null) {
                    foreach ($penyebabData as $penyebabItem) {
                        $this->savePenyebab($penyebabItem, ucfirst($penyebabType), $diagnosaId);
                    }
                } else {
                    continue;
                }
            }

            AdminLog::create([
                'user_id' => Auth::user()->id,
                'action' => 'Menambah standar diagnosa',
            ]);

            DB::commit();
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
        }
        return response()->json(['message' => 'Diagnosa berhasil ditambahkan', 'data' => $diagnosa]);
    }

    public function detailDiagnosa($id)
    {
        $diagnosa = Diagnosa::where('id', $id)->first();

        if (!$diagnosa) {
            return response()->json(['message' => 'Diagnosa tidak ditemukan'], 404);
        }
        // Ubah data gejala menjadi array
        $gejalaMayorSubjektif = Gejala::where('id_diagnosa', $id)
            ->where('id_jenis_gejala', 1) // Sesuaikan dengan ID jenis gejala mayor
            ->where('id_kategori_gejala', 1) // Sesuaikan dengan ID kategori gejala subjektif
            ->pluck('nama_gejala')
            ->toArray();

        $gejalaMayorObjektif = Gejala::where('id_diagnosa', $id)
            ->where('id_jenis_gejala', 1) // Sesuaikan dengan ID jenis gejala mayor
            ->where('id_kategori_gejala', 2) // Sesuaikan dengan ID kategori gejala objektif
            ->pluck('nama_gejala')
            ->toArray();

        $gejalaMinorSubjektif = Gejala::where('id_diagnosa', $id)
            ->where('id_jenis_gejala', 2) // Sesuaikan dengan ID jenis gejala minor
            ->where('id_kategori_gejala', 1) // Sesuaikan dengan ID kategori gejala subjektif
            ->pluck('nama_gejala')
            ->toArray();

        $gejalaMinorObjektif = Gejala::where('id_diagnosa', $id)
            ->where('id_jenis_gejala', 2) // Sesuaikan dengan ID jenis gejala minor
            ->where('id_kategori_gejala', 2) // Sesuaikan dengan ID kategori gejala objektif
            ->pluck('nama_gejala')
            ->toArray();

        // Ubah data penyebab menjadi array
        $penyebabPsikologis = DetailPenyebab::where('id_diagnosa', $id)
            ->where('id_jenis_penyebab', 1) // Sesuaikan dengan ID jenis penyebab psikologis
            ->pluck('nama_penyebab')
            ->toArray();

        $penyebabSituasional = DetailPenyebab::where('id_diagnosa', $id)
            ->where('id_jenis_penyebab', 2) // Sesuaikan dengan ID jenis penyebab situasional
            ->pluck('nama_penyebab')
            ->toArray();

        $penyebabFisiologis = DetailPenyebab::where('id_diagnosa', $id)
            ->where('id_jenis_penyebab', 3) // Sesuaikan dengan ID jenis penyebab fisiologis
            ->pluck('nama_penyebab')
            ->toArray();

        $penyebabUmum = DetailPenyebab::where('id_diagnosa', $id)
            ->where('id_jenis_penyebab', 4) // Sesuaikan dengan ID jenis penyebab fisiologis
            ->pluck('nama_penyebab')
            ->toArray();

        $faktorResiko = FaktorResiko::where('id_diagnosa', $id)
            ->pluck('nama')
            ->toArray();
        // Mengganti nilai gejala dan penyebab dalam objek diagnosa
        $diagnosa->gejala_mayor_subjektif = $gejalaMayorSubjektif;
        $diagnosa->gejala_mayor_objektif = $gejalaMayorObjektif;
        $diagnosa->gejala_minor_subjektif = $gejalaMinorSubjektif;
        $diagnosa->gejala_minor_objektif = $gejalaMinorObjektif;
        $diagnosa->penyebab_psikologis = $penyebabPsikologis;
        $diagnosa->penyebab_situasional = $penyebabSituasional;
        $diagnosa->penyebab_fisiologis = $penyebabFisiologis;
        $diagnosa->penyebab_umum = $penyebabUmum;
        $diagnosa->faktor_risiko = $faktorResiko;
        return response()->json(['data' => $diagnosa]);
    }

    //Edit
    public function updateDiagnosa(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode_diagnosa' => 'required|string|max:255|unique:diagnosa,kode_diagnosa,' . $id,
            'nama_diagnosa' => 'required|string|max:255|unique:diagnosa,nama_diagnosa,' . $id,
            'faktor_risiko' => 'array|nullable',
            'gejala_mayor_subjektif' => 'array|nullable',
            'gejala_mayor_objektif' => 'array|nullable',
            'gejala_minor_subjektif' => 'array|nullable',
            'gejala_minor_objektif' => 'array|nullable',
            'penyebab_psikologis' => 'array|nullable',
            'penyebab_situasional' => 'array|nullable',
            'penyebab_fisiologis' => 'array|nullable',
            'penyebab_umum' => 'array|nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        DB::beginTransaction();

        try {
            $diagnosa = Diagnosa::findOrFail($id);


            if (!$diagnosa) {
                return response()->json(['message' => 'Diagnosa tidak ditemukan'], 404);
            }

            // Perbarui data diagnosa
            $diagnosa->update([
                'kode_diagnosa' => $request->input('kode_diagnosa', $diagnosa->kode_diagnosa),
                'nama_diagnosa' => $request->input('nama_diagnosa', $diagnosa->nama_diagnosa),
            ]);
            $diagnosa->update($request->all());
            $diagnosaId = $diagnosa -> id;
            // Faktor Resiko

            // dd($diagnosa->all());
                // Process Faktor Resiko
            $faktorResiko = $request->input('faktor_risiko');
            $faktorResikoArray = is_array($faktorResiko) ? $faktorResiko : explode(', ', $faktorResiko);

            $this->updateFaktorResiko($faktorResikoArray, $diagnosaId);

            // Process Gejala Mayor Subjektif
            $gejalaMayorSubjektif = $request->input('gejala_mayor_subjektif');
            $gejalaMayorSubjektifArray = is_array($gejalaMayorSubjektif) ? $gejalaMayorSubjektif : explode(', ', $gejalaMayorSubjektif);

            $this->updateGejala($gejalaMayorSubjektifArray, 'Mayor', 'Subjektif', $diagnosaId);
            // Process Gejala Mayor Objektif
            $gejalaMayorObjektif = $request->input('gejala_mayor_objektif');
            $gejalaMayorObjektifArray = is_array($gejalaMayorObjektif) ? $gejalaMayorObjektif : explode(', ', $gejalaMayorObjektif);

            $this->updateGejala($gejalaMayorObjektifArray, 'Mayor', 'Objektif', $diagnosaId);
            // Process Gejala Minor Subjektif
            $gejalaMinorSubjektif = $request->input('gejala_minor_subjektif');
            $gejalaMinorSubjektifArray = is_array($gejalaMinorSubjektif) ? $gejalaMinorSubjektif : explode(', ', $gejalaMinorSubjektif);

            $this->updateGejala($gejalaMinorSubjektifArray, 'Minor', 'Subjektif', $diagnosaId);
            // Process Gejala Minor Objektif
            $gejalaMinorObjektif = $request->input('gejala_minor_objektif');
            $gejalaMinorObjektifArray = is_array($gejalaMinorObjektif) ? $gejalaMinorObjektif : explode(', ', $gejalaMinorObjektif);

            $this->updateGejala($gejalaMinorObjektifArray, 'Minor', 'Objektif', $diagnosaId);
            // Process Penyebab
            $penyebabTypes = ['psikologis', 'situasional', 'fisiologis','umum'];

            foreach ($penyebabTypes as $penyebabType) {
                $inputName = 'penyebab_' . $penyebabType;
                $penyebabData = $request->input($inputName);
                $penyebabDataArray = is_array($penyebabData) ? $penyebabData : explode(', ', $penyebabData);

                $this->updatePenyebab($penyebabDataArray, ucfirst($penyebabType), $diagnosaId);
            }

            // update diagnosa logadmin
            AdminLog::create([
                'user_id' => Auth::user()->id,
                'action' => 'Mengubah standar diagnosa',
            ]);

            DB::commit();
        } catch (\Exception $e) {
            dd($e);
        }
        return response()->json(['message' => 'Diagnosa berhasil diperbarui']);
    }

    public function hapusDiagnosa($id)
    {
        // Temukan diagnosa berdasarkan ID
        $diagnosa = Diagnosa::find($id);

        DB::beginTransaction();
        try {
            if (!$diagnosa) {
                return response()->json(['message' => 'Diagnosa tidak ditemukan'], 404);
            }

            // Hapus faktor risiko yang terkait dengan diagnosa
            FaktorResiko::where('id_diagnosa', $id)->delete();


            Gejala::where('id_diagnosa', $id)->delete();

            // Hapus penyebab yang terkait dengan diagnosa
            DetailPenyebab::where('id_diagnosa', $id)->delete();

            // Hapus diagnosa
            $diagnosa->delete();

            // Log aktivitas
            AdminLog::create([
                'user_id' => Auth::user()->id,
                'action' => 'Menghapus standar diagnosa',
            ]);
            DB::commit();
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
        }

        return response()->json(['message' => 'Diagnosa berhasil dihapus']);
    }

    private function updateFaktorResiko($faktorResikoInputs, $diagnosaId)
    {
        FaktorResiko::where('id_diagnosa', $diagnosaId)->delete();

        foreach ($faktorResikoInputs as $faktorResikoInput) {
            $diagnosa = Diagnosa::where('id', $diagnosaId)->pluck('id')->first();
            $faktorResiko = new FaktorResiko([
                'id_diagnosa' => $diagnosa,
                'nama' => $faktorResikoInput,
            ]);

            $faktorResiko->save();
        }
    }

    private function updateGejala($gejalaInputs, $jenisGejala, $kategoriGejala, $diagnosaId)
    {
        Gejala::where('id_diagnosa', $diagnosaId)
            ->where('id_jenis_gejala', JenisGejala::where('nama_jenis_gejala', $jenisGejala)->first()->id)
            ->where('id_kategori_gejala', KategoriGejala::where('nama_kategori_gejala', $kategoriGejala)->first()->id)
            ->delete();

        foreach ($gejalaInputs as $gejalaInput) {
            $idJenisGejala = JenisGejala::where('nama_jenis_gejala', $jenisGejala)->pluck('id')->first();
            $idKategoriGejala = KategoriGejala::where('nama_kategori_gejala', $kategoriGejala)->pluck('id')->first();

            $gejala = new Gejala([
                'id_diagnosa' => $diagnosaId,
                'id_kategori_gejala' => $idKategoriGejala,
                'id_jenis_gejala' => $idJenisGejala,
                'nama_gejala' => $gejalaInput,
            ]);
            $gejala->save();
        }
    }

    private function updatePenyebab($penyebabInputs, $jenisPenyebab, $diagnosaId)
    {
        DetailPenyebab::where('id_diagnosa', $diagnosaId)
            ->where('id_jenis_penyebab', JenisPenyebab::where('nama_jenis_penyebab', $jenisPenyebab)->first()->id)
            ->delete();

        foreach ($penyebabInputs as $penyebabInput) {
            $penyebabModel = new DetailPenyebab();
            $penyebabModel->id_diagnosa = $diagnosaId;
            $penyebabModel->id_jenis_penyebab = JenisPenyebab::where('nama_jenis_penyebab', $jenisPenyebab)->first()->id;
            $penyebabModel->nama_penyebab = $penyebabInput;
            $penyebabModel->save();
        }
    }


    private function saveFaktorResiko($diagnosaId, $faktorResiko)
    {
        $diagnosa = Diagnosa::where('id', $diagnosaId)->pluck('id')->first();
        $faktorResiko = new FaktorResiko([
            'id_diagnosa' => $diagnosa,
            'nama' => $faktorResiko,
        ]);

        $faktorResiko->save();
    }

    private function saveGejala($gejalaInput, $jenisGejala, $kategoriGejala, $diagnosaId)
    {
        $idJenisGejala = JenisGejala::where('nama_jenis_gejala', $jenisGejala)->pluck('id')->first();

        $idKategoriGejala = KategoriGejala::where('nama_kategori_gejala', $kategoriGejala)->pluck('id')->first();

        $gejala = new Gejala([
            'id_diagnosa' => $diagnosaId,
            'id_kategori_gejala' => $idKategoriGejala,
            'id_jenis_gejala' => $idJenisGejala,
            'nama_gejala' => $gejalaInput,
        ]);
        $gejala->save();
    }
    private function savePenyebab($penyebabInput, $jenisPenyebab, $diagnosaId)
    {
        $idJenisPenyebab = JenisPenyebab::where('nama_jenis_penyebab', $jenisPenyebab)->pluck('id')->first();
        $penyebab = new DetailPenyebab([
            'id_diagnosa' => $diagnosaId,
            'id_jenis_penyebab' => $idJenisPenyebab,
            'nama_penyebab' => $penyebabInput,
        ]);

        $penyebab->save();
    }
}
