<?php

namespace App\Http\Controllers\Perawat\StandarForm;

use App\Http\Controllers\Controller;
use App\Models\Admin\DetailPenyebab;
use App\Models\Admin\Diagnosa;
use App\Models\Admin\FaktorResiko;
use App\Models\Admin\Gejala;
use Illuminate\Http\Request;
use App\Models\Perawat\Pemeriksaan;
use App\Models\Perawat\StandarForm\Form_Diagnosa;
use App\Models\Admin\Perawat;
// auth suport
use Illuminate\Support\Facades\Auth;
// validator suport
use Illuminate\Support\Facades\Validator;
// db suport
use Illuminate\Support\Facades\DB;
// carbon suport
use Carbon\Carbon;

class DiagnosaController extends Controller
{
    public function getDiagnosa()
    {
        $diagnosa = Diagnosa::all();

        return response()->json([
            'message' => 'Success',
            'data' => $diagnosa,
        ]);
    }

    public function validationDiagnosaAttribute($id)
    {
        $diagnosa = Diagnosa::find($id);

        if ($diagnosa == null) {
            return response()->json([
                'message' => 'Diagnosa tidak ditemukan',
            ], 404);
        }

        $gejala_tanda_mayor_subjektif = Gejala::where('id_diagnosa', $diagnosa->id)->where('id_jenis_gejala', 1)->where('id_kategori_gejala', 1)->get();
        $gejala_tanda_mayor_objektif = Gejala::where('id_diagnosa', $diagnosa->id)->where('id_jenis_gejala', 1)->where('id_kategori_gejala', 2)->get();

        $gejala_tanda_minor_subjektif = Gejala::where('id_diagnosa', $diagnosa->id)->where('id_jenis_gejala', 2)->where('id_kategori_gejala', 1)->get();
        $gejala_tanda_minor_objektif = Gejala::where('id_diagnosa', $diagnosa->id)->where('id_jenis_gejala', 2)->where('id_kategori_gejala', 2)->get();

        $faktor_risiko = FaktorResiko::where('id_diagnosa', $diagnosa->id)->get();

        $penyebab_psikologis = DetailPenyebab::where('id_diagnosa', $diagnosa->id)->where('id_jenis_penyebab', 1)->get();
        $penyebab_situasional = DetailPenyebab::where('id_diagnosa', $diagnosa->id)->where('id_jenis_penyebab', 2)->get();
        $penyebab_fisiologis = DetailPenyebab::where('id_diagnosa', $diagnosa->id)->where('id_jenis_penyebab', 3)->get();
        $penyebab_umum = DetailPenyebab::where('id_diagnosa', $diagnosa->id)->where('id_jenis_penyebab', 4)->get();


        return response()->json([
            'message' => 'Success',
            'diagnosa' => $diagnosa,
            'gejala_tanda_mayor_subjektif' => $gejala_tanda_mayor_subjektif,
            'gejala_tanda_mayor_objektif' => $gejala_tanda_mayor_objektif,
            'gejala_tanda_minor_subjektif' => $gejala_tanda_minor_subjektif,
            'gejala_tanda_minor_objektif' => $gejala_tanda_minor_objektif,
            'faktor_risiko' => $faktor_risiko,
            'penyebab_psikologis' => $penyebab_psikologis,
            'penyebab_situasional' => $penyebab_situasional,
            'penyebab_fisiologis' => $penyebab_fisiologis,
            'penyebab_umum' => $penyebab_umum,
        ]);
    }

    public function addPasienDiagnosa(Request $request, $id_perawatan)
    {
        $users = Auth::user()->id;
        $perawat = Perawat::where('id_user', $users)->first();
        $perawat = $perawat->id;
        $validator = Validator::make($request->all(), [
            'nama_diagnosa' => 'required|int',
            'gejala_tanda_mayor_objektif' => 'nullable|string|max:5000',
            'gejala_tanda_mayor_subjektif' => 'nullable|string|max:5000',
            'gejala_tanda_minor_objektif' => 'nullable|string|max:5000',
            'gejala_tanda_minor_subjektif' => 'nullable|string|max:5000',
            'penyebab_situasional' => 'nullable|string|max:5000',
            'penyebab_psikologis' => 'nullable|string|max:5000',
            'penyebab_fisiologis' => 'nullable|string|max:5000',
            'penyebab_umum' => 'nullable|string|max:5000',
            'faktor_risiko' => 'nullable|string|max:5000',
            'catatan_diagnosa' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }


        // transaction db
        DB::beginTransaction();

        try {
            $pemeriksaan = new Pemeriksaan();

            $pemeriksaan->id_perawat = $perawat;
            $pemeriksaan->id_perawatan = $id_perawatan;
            $pemeriksaan->jam_pemberian_diagnosa = Carbon::now();
            $time = $pemeriksaan->jam_pemberian_diagnosa->hour;

            if ($time >= 7 && $time < 14) {
                $pemeriksaan->shift = '1';
            } else if ($time >= 14 && $time < 21) {
                $pemeriksaan->shift = '2';
            } else {
                $pemeriksaan->shift = '3';
            }

            $pemeriksaan->save();

            $form_diagnosa = new Form_Diagnosa();

            $form_diagnosa->id_pemeriksaan = $pemeriksaan->id;
            $form_diagnosa->nama_diagnosa = $request->nama_diagnosa;
            $form_diagnosa->gejala_tanda_mayor_objektif = $request->gejala_tanda_mayor_objektif;
            $form_diagnosa->gejala_tanda_mayor_subjektif = $request->gejala_tanda_mayor_subjektif;
            $form_diagnosa->gejala_tanda_minor_objektif = $request->gejala_tanda_minor_objektif;
            $form_diagnosa->gejala_tanda_minor_subjektif = $request->gejala_tanda_minor_subjektif;
            $form_diagnosa->penyebab_psikologis = $request->penyebab_psikologis;
            $form_diagnosa->penyebab_situasional = $request->penyebab_situasional;
            $form_diagnosa->penyebab_fisiologis = $request->penyebab_fisiologis;
            $form_diagnosa->penyebab_umum = $request->penyebab_umum;
            $form_diagnosa->faktor_risiko = $request->faktor_risiko;
            $form_diagnosa->catatan_diagnosa = $request->catatan_diagnosa;
            $form_diagnosa->save();

            DB::commit();

            return response()->json([
                'message' => 'Success',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function getDetailDiagnosaPasien($id_pemeriksaan)
    {

        $pemeriksaan = Pemeriksaan::find($id_pemeriksaan);

        if ($pemeriksaan == null) {
            return response()->json([
                'message' => 'Pemeriksaan tidak ditemukan',
            ], 404);
        }

        $form_diagnosa = Form_Diagnosa::where('id_pemeriksaan', $id_pemeriksaan)->first();


        if ($form_diagnosa == null) {
            return response()->json([
                'message' => 'Form Diagnosa tidak ditemukan',
            ], 404);
        }

        $nama_diagnosa = Diagnosa::find($form_diagnosa->nama_diagnosa)->nama_diagnosa;

        $faktor_risiko = $this->proCessData($form_diagnosa->faktor_risiko, FaktorResiko::class, 'nama');

        $penyebab_psikologis = $this->proCessData($form_diagnosa->penyebab_psikologis, DetailPenyebab::class, 'nama_penyebab');
        $penyebab_situasional = $this->proCessData($form_diagnosa->penyebab_situasional, DetailPenyebab::class, 'nama_penyebab');
        $penyebab_fisiologis = $this->proCessData($form_diagnosa->penyebab_fisiologis, DetailPenyebab::class, 'nama_penyebab');
        $penyebab_umum = $this->proCessData($form_diagnosa->penyebab_umum, DetailPenyebab::class, 'nama_penyebab');

        $gejala_tanda_mayor_objektif = $this->proCessData($form_diagnosa->gejala_tanda_mayor_objektif, Gejala::class, 'nama_gejala');
        $gejala_tanda_mayor_subjektif = $this->proCessData($form_diagnosa->gejala_tanda_mayor_subjektif, Gejala::class, 'nama_gejala');

        $gejala_tanda_minor_objektif = $this->proCessData($form_diagnosa->gejala_tanda_minor_objektif, Gejala::class, 'nama_gejala');
        $gejala_tanda_minor_subjektif = $this->proCessData($form_diagnosa->gejala_tanda_minor_subjektif, Gejala::class, 'nama_gejala');



        $result = $form_diagnosa;

        return response()->json([
            'message' => 'Success',
            'data' => $result,
            'nama_diagnosa' => $nama_diagnosa,
            'penyebab_situasional' => $penyebab_situasional,
            'penyebab_psikologis' => $penyebab_psikologis,
            'penyebab_fisiologis' => $penyebab_fisiologis,
            'penyebab_umum' => $penyebab_umum,
            'gejala_tanda_mayor_objektif' => $gejala_tanda_mayor_objektif,
            'gejala_tanda_mayor_subjektif' => $gejala_tanda_mayor_subjektif,
            'gejala_tanda_minor_objektif' => $gejala_tanda_minor_objektif,
            'gejala_tanda_minor_subjektif' => $gejala_tanda_minor_subjektif,
            'faktor_risiko' => $faktor_risiko,
        ]);
    }

    private function proCessData($data, $model, $optionLabel)
    {
        $data = explode(',', $data);
        $result = [];

        foreach ($data as $value) {
            $id = intval($value);
            $getModel = $model::find($id);

            if ($getModel) {
                $result[] = $getModel->$optionLabel;
            }
        }
        return $result;
    }
}
