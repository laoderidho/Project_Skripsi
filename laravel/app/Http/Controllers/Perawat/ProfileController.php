<?php

namespace App\Http\Controllers\Perawat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function profile(){
        $user = Auth::user();

        $users = "select nama_lengkap, tanggal_lahir,
                case
                    when jenis_kelamin = 1 then 'Laki-Laki'
                    when jenis_kelamin = 2 then 'Perempuan'
                end as jenis_kelamin, alamat
                from users
                where id = $user->id";
        $users = db::select($users);

        return response()->json([
            'message'=> 'success',
            'data'=> $users
        ]);
    }
}
