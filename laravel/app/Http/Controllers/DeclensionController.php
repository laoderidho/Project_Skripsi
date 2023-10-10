<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Implementasi;
use App\Models\Intervensi;

class DeclensionController extends Controller
{
    public function index()
    {

    }
    public function peluruhan(Request $request)
    {

        $kataKata = explode(' ', $kalimat);
        $hasilPeluruhan = [];

        // Melakukan peluruhan hanya untuk kata pertama dalam kalimat
        $kataPertama = $kataKata[0];
        foreach ($kataDasarData as $data) {
            if ($kataPertama == $data->kata) {
                // Memanggil fungsi peluruhanKata untuk mendapatkan hasil peluruhan
                $hasilPeluruhan[] = $this->peluruhanKata($data->kata, $data->awalan);
            }
        }

        // Mengirim hasil peluruhan dalam bentuk array ke view atau mengembalikannya sebagai response JSON
        return response()->json(['hasilPeluruhan' => $hasilPeluruhan]);
    }

    private function peluruhanKata($kataDasar, $awalan)
    {
        // Daftar bunyi nasal dan huruf pertama dari kata dasar
        $bunyiNasal = ['m', 'n', 'ng', 'ny'];
        $hurufPertama = substr($kataDasar, 0, 1);
        $akhirKataDasar = substr($kataDasar, -1);

        // Cek apakah huruf pertama adalah bunyi nasal
        if (in_array($hurufPertama, $bunyiNasal)) {
            if ($awalan == 'me-') {
                return 'me' . $kataDasar;
            } elseif ($awalan == 'pe-') {
                return 'pe' . $kataDasar;
            }
        } else {
            // Cek apakah kata dasar berakhir dengan huruf vokal atau konsonan
            $isVokal = in_array($akhirKataDasar, ['a', 'i', 'u', 'e', 'o']);
            $awalanMempak = $isVokal ? 'mem' : 'memp';

            if ($awalan == 'me-') {
                if (in_array($hurufPertama, ['r', 'l', 'n', 'm', 'w', 'y'])) {
                    return 'me' . $kataDasar;
                } elseif (in_array($hurufPertama, ['b', 'f', 'v', 'p'])) {
                    return 'mem' . $kataDasar;
                } elseif (in_array($hurufPertama, ['c', 'd', 'j', 'z', 't'])) {
                    return 'men' . $kataDasar;
                } elseif (in_array($hurufPertama, ['a', 'i', 'u', 'e', 'o', 'g', 'h', 'q', 'k'])) {
                    return 'meng' . $kataDasar;
                } elseif ($hurufPertama == 's') {
                    return 'meny' . $kataDasar;
                } elseif (strlen($kataDasar) == 1) {
                    return 'menge' . $kataDasar;
                }
            } elseif ($awalan == 'pe-') {
                // Tambahkan logika untuk awalan pe- sesuai aturan yang diberikan.
                // Jika tidak ada aturan khusus, Anda dapat mengembalikan kata dasar tanpa perubahan.
                if (in_array($hurufPertama, ['a', 'i', 'u', 'e', 'o'])) {
                    return 'pem' . $kataDasar;
                } else {
                    return $awalanMempak . $kataDasar;
                }
            }
        }

        // Jika tidak ada aturan yang cocok, kembalikan kata dasar saja
        return $kataDasar;
    }

}
