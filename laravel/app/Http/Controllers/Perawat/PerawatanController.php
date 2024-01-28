<?php

namespace App\Http\Controllers\Perawat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Perawat;

class PerawatanController extends Controller
{
    public function AddPerawatan(Request $request){
        $user = Auth::user()->id;

        
    }
}
