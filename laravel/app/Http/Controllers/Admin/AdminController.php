<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\User;

class AdminController extends Controller
{
    public function getAll(){
        $users = User::all();
        return response()->json([
            'data'=> $users,
        ]);
    }

    public function Hello(){
        return response()->json([
            'message'=> 'Hello World',
        ]);
    }
}
