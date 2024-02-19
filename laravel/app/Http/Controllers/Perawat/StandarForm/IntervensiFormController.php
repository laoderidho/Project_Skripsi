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

class IntervensiFormController extends Controller
{
    public function getIntervensi(){
        $intervensi = Intervensi::all();

        return response()->json([
            'message' => 'Success',
            'data' => $intervensi,
        ]);
    }

    protected $declensionController;

    public function __construct(DeclensionController $declensionController)
    {
        $this->declensionController = $declensionController;
    }

    public function Peluruhan(Request $request, $kalimat){
        return $this->DeclensionController->peluruhan($request, $kalimat);
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
            'nama_intervensi' =>'required|string|max:255',
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
                $pemeriksaan = new Pemeriksaan();
                $pemeriksaan->id_perawat = $perawat;
                $pemeriksaan->id_pemeriksaan = $id_pemeriksaan;
            }

            // Menambahkan atau memperbarui waktu pemberian intervensi
            $pemeriksaan->jam_pemberian_intervensi = date('H:i:s');

            // Menyimpan perubahan
            $pemeriksaan->update();

            // Membuat atau memperbarui data pada tabel form_intervensi
            $form_intervensi = new Form_Intervensi();
            $form_implementasi = new Form_Implementasi();

            $form_intervensi->id_pemeriksaan = $pemeriksaan->id; //
            $form_intervensi->nama_intervensi = $request->input('nama_intervensi');
            $tindakan_intervensi = explode(',', $request->input('tindakan_intervensi'));

            // Insert or update form_intervensi for each tindakan_intervensi
            foreach ($tindakan_intervensi as $tindakan) {
                $new_intervensi = Form_Intervensi::where('id_pemeriksaan', $pemeriksaan->id)
                    ->where('nama_intervensi', $request->input('nama_intervensi'))
                    ->where('tindakan_intervensi', trim($tindakan))
                    ->first();

                if (!$new_intervensi) {
                    $new_intervensi = new Form_Intervensi();
                    $new_intervensi->id_pemeriksaan = $pemeriksaan->id;
                }
                $new_intervensi->nama_intervensi = $request->input('nama_intervensi');
                $new_intervensi->tindakan_intervensi = trim($tindakan);
                $new_intervensi->save();
            }

            // Insert or update form_implementasi for each tindakan_intervensi
            foreach ($tindakan_intervensi as $tindakan) {
                $tindakan = trim($tindakan);
                $nama_implementasi = $this->declensionController->peluruhan($tindakan);
                $new_implementasi = Form_Implementasi::where('id_pemeriksaan', $pemeriksaan->id)->where('nama_implementasi', $nama_implementasi)->where('tindakan_implementasi', 0) ->first();

                if (!$new_implementasi) { $new_implementasi = new Form_Implementasi(); $new_implementasi->id_pemeriksaan = $pemeriksaan->id; }
                $new_implementasi->nama_implementasi = $nama_implementasi;
                $new_implementasi->tindakan_implementasi = 0;
                $new_implementasi->jam_ceklis = null;
                $new_implementasi->save(); }
                DB::commit();
                return response()->json([
                    'message' => 'Success',
                    'data' => $new_implementasi ]);

                } catch (\Exception $e)
                  {
                    DB::rollback();
                    return response()->json([
                        'message' => 'Failed',
                        'error' => $e->getMessage(), ],
                        500); }
                    }
}
