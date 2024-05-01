<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
// db suport
use Illuminate\Support\Facades\DB;

class AdminLogController extends Controller
{
    public function getDatalogAdmin(){
        $query = "select u.nama_lengkap, l.action, date_format(l.created_at , '%d-%m-%Y') as tanggal, date_format(l.created_at, '%H:%i') as jam
                    from users u  join log_admin l on u.id = user_id";


        $data = DB::select($query);

        return response()->json(['message' => 'Data berhasil diambil', 'data' => $data], 200);
    }
}
