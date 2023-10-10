<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
     public function Register(Request $request)
     {
        // validation

        $validator = Validator::make($request->all(), [
            'nama'=> 'required|string|max:255',
            'tanggal_lahir'=>'required|date',
            'jenis_kelamin' =>'required|boolean',
            'alamat'=> 'required|string|max:255',
            'no_telepon'=> 'required|string|max:255',
            'email'=> 'required|string|email|max:255|unique:users',
            'password'=> 'required|string|max:20|',
            'no_karyawan'=> 'required|int|min:20|unique:users',
            'role' =>'required|string|max:10',
            'photo_path' => 'required|string|max:255',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $user = User::create([
            'nama'=> $request->nama,
            'tanggal_lahir'=> $request->tanggal_lahir,
            'jenis_kelamin'=> $request->jenis_kelamin,
            'alamat'=> $request->alamat,
            'no_telepon'=> $request->no_telepon,
            'email'=> $request->email,
            'password'=> bcrypt($request->password),
            'no_karyawan'=> $request->no_karyawan,
            'role'=> $request->role,
            'photo_path'=> $request->photo_path,
        ]);


        return response()->json([
            'data'=> $user,
        ]);
     }


     public function Login(Request $request)
     {
        if(!Auth::attempt($request->only('email', 'password'))){
            return response()->json([
                'message'=> 'Invalid login details'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'data'=> $user,
            'access_token'=> $token,
            'token_type'=> 'Bearer',
        ]);
     }


        public function logout(Request $request){

            $user = $request->user();

            if ($user) {
                $user->tokens->each(function ($token, $key){
                    $token->delete();
                });

                return response()->json(['message' => 'Logged out successfully']);

            }
            dd($user);

            return response()->json(['message' => 'User not found'], 404);
        }

}
