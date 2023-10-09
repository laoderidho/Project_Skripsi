<?php

namespace App\Http\Controllers\Perawat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PerawatController extends Controller
{
    public function hello()
    {
        return response()->json(['message' => 'Hello Perawat']);
    }
}
