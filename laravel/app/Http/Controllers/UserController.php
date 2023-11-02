<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Admin\Perawat;


class UserController extends Controller
{

    public function addUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|boolean',
            'alamat' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|max:20|',
            'no_karyawan' => 'required|string|max:20|unique:users',
            'role' => 'required|string|max:10',
            'photo' => 'nullable|image|file|max:2048',
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if ($request->hasFile('photo')) {
            $request->file('photo')->store('post-images');
            $photo = $request->file('photo');
            $photoPath = $photo->store('profile_image');
        }else{
            $photoPath = null;
        }


        // Update data user dengan alamat foto
        $user = User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'no_telepon' => $request->no_telepon,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'no_karyawan' => $request->no_karyawan,
            'role' => $request->role,
            'photo' => $photoPath,
        ]);

        if($request->role == 'perawat'){
            $this->perawat($user->id, $request->shift, $request->status);
        }

        return response()->json([
            'message' => 'Registration Success',
            'data' => $user,
        ]);

    }

    public function delete($id){
        $user = User::find($id);
        $user->delete();

        return response()->json([
            'message' => 'Delete Success',
            'data' => $user,
        ]);
    }


    private function perawat($id, $hift, $status){
        $user = User::find($id);
        $perawat = new Perawat();

        $perawat->id_user = $user->id;
        $perawat->shift = $hift;
        $perawat->status = $status;

        $perawat->save();


        return response()->json([
            'message' => 'Update Success',
            'data' => $user,
        ]);
    }
}
