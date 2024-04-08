<?php

namespace App\Http\Controllers\Perawat\StandarForm;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\DeclensionController;
use Illuminate\Http\Request;
use App\Models\Perawat\Pemeriksaan;
use App\Models\Perawat\StandarForm\Form_Intervensi;
use App\Models\Perawat\StandarForm\Form_Implementasi;
use App\Models\Admin\Declension;
use App\Models\Admin\Intervensi;
use App\Models\Admin\TindakanIntervensi;
use App\Models\Admin\Perawat;
// auth suport
use Illuminate\Support\Facades\Auth;
// validator suport
use Illuminate\Support\Facades\Validator;
// db suport
use Illuminate\Support\Facades\DB;
// carbon suport
use Carbon\Carbon;

class IntervensiFormController extends Controller
{
    public function getIntervensi(){
        $intervensi = Intervensi::all();

        return response()->json([
            'message' => 'Success',
            'data' => $intervensi,
        ]);
    }

    public function validationIntervensiAttribute($id){
        $intervensi = Intervensi::find($id);

        if($intervensi == null){
            return response()->json([
                'message' => 'Intervensi tidak ditemukan',
            ], 404);
        }

        $tindakan_observasi = TindakanIntervensi::where('id_intervensi', $intervensi->id)->where('id_kategori_tindakan', 1)->get();
        $tindakan_teraupetik = TindakanIntervensi::where('id_intervensi', $intervensi->id)->where('id_kategori_tindakan', 2)->get();
        $tindakan_edukasi = TindakanIntervensi::where('id_intervensi', $intervensi->id)->where('id_kategori_tindakan', 3)->get();


        return response()->json([
            'message' => 'Success',
            'data' => $intervensi,
            'tindakan_observasi' => $tindakan_observasi,
            'tindakan_teraupetik' => $tindakan_teraupetik,
            'tindakan_edukasi' => $tindakan_edukasi,
        ]);
    }
    public function updateIntervensi(Request $request, $id_pemeriksaan)
    {
        $users = Auth::user()->id;
        $perawat = Perawat::where('id_user', $users)->first();
        $perawat = $perawat->id;

        $validator = Validator::make($request->all(), [
            'nama_intervensi' =>'required|int',
            'tindakan_intervensi' => 'required|string|max:5000',
            'catatan_intervensi' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        // transaction db
        DB::beginTransaction();
        try{
            // Mencari pemeriksaan terkait
            $pemeriksaan = Pemeriksaan::where('id', $id_pemeriksaan)->first();

            // Jika pemeriksaan tidak ditemukan, buat yang baru
            if(!$pemeriksaan) {
                return response()->json([
                    'message' => 'Pemeriksaan tidak ditemukan',
                ], 404);
            }

            // Menambahkan atau memperbarui waktu pemberian intervensi
            $pemeriksaan->jam_pemberian_intervensi = Carbon::now();

            // Menyimpan perubahan
            $pemeriksaan->update();

            // Membuat atau memperbarui data pada tabel form_intervensi
            $form_intervensi = new Form_Intervensi();

            $form_intervensi->id_pemeriksaan = $pemeriksaan->id; //
            $form_intervensi->nama_intervensi = $request->input('nama_intervensi');
            $tindakan_intervensi = explode(',', $request->input('tindakan_intervensi'));
            $form_intervensi->tindakan_intervensi = $request->input('tindakan_intervensi');
            $form_intervensi->catatan_intervensi = $request->input('catatan_intervensi');

            $form_intervensi->save();

            // Insert or update form_implementasi for each tindakan_intervensi
            foreach ($tindakan_intervensi as $tindakan) {
                $tindakan = intval($tindakan);
                if($tindakan != null){
                    $new_implementasi = new Form_Implementasi();
                    $new_implementasi->id_pemeriksaan = $pemeriksaan->id;
                    $new_implementasi->nama_implementasi = $tindakan;
                    $new_implementasi->tindakan_implementasi = 0;
                    $new_implementasi->save();
                }
            }
                DB::commit();
                return response()->json([
                    'message' => 'Success',
                    'data' => $new_implementasi
                ]);

                } catch (\Exception $e)
            {
                DB::rollback();
                return response()->json([
                    'message' => 'Failed',
                    'error' => $e->getMessage(), ],
                    500);
                }
            }


        public function getDetailIntervensi($id_pemeriksaan){
            $form_intervensi = Form_Intervensi::where('id_pemeriksaan', $id_pemeriksaan)->first();

            if(!$form_intervensi){
                return response()->json([
                    'message' => 'Pemeriksaan tidak ditemukan',
                ], 404);
            }

            $intervensi = Intervensi::find($form_intervensi->nama_intervensi);
            $nama_intervensi = $intervensi->nama_intervensi;
            $kode_intervensi = $intervensi->kode_intervensi;


            $tindakan_intervensi = $this->devideIntervensi($form_intervensi->tindakan_intervensi);

            return response()->json([
                'message' => 'Success',
                'tindakan_intervensi' => $tindakan_intervensi,
                'nama_intervensi' => $nama_intervensi,
                'kode_intervensi' => $kode_intervensi,
            ]);
        }

    private function devideIntervensi($intervensi)
    {
        $tindakan_intervensi = explode(',', $intervensi);

        $temp_result = array();

        foreach ($tindakan_intervensi as $tindakan) {
            $tindakan = intval($tindakan);
            $tindakan_query = "select i.id, i.nama_tindakan_intervensi, kt.nama_kategori_tindakan
                    from tindakan_intervensi i
                    join kategori_tindakan kt
                    on i.id_kategori_tindakan = kt.id
                    where i.id = $tindakan";

            $tindakan_data = DB::select($tindakan_query);

            if (!empty($tindakan_data)) {
                $temp_result[] = $tindakan_data[0]; // Mengambil hanya elemen pertama dari hasil query
            }
        }

        return $temp_result;
    }

}
