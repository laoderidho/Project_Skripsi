<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
<<<<<<< HEAD
use Illuminate\Support\Facades\Hash;
=======
>>>>>>> 73ff3bc34597ed1a4a22d609c381f25eb60395d3

class AuthController extends Controller
{
    public function Register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'nama'=> 'required|string|max:255',
            'tanggal_lahir'=>'required|date',
            'jenis_kelamin' =>'required|boolean',
            'alamat'=> 'required|string|max:255',
            'no_telepon'=> 'required|string|max:255',
            'email'=> 'required|string|email|max:255|unique:users',
            'password'=> 'required|string|max:20|',
<<<<<<< HEAD
            'no_karyawan'=> 'required|int|min:20|unique:users',
=======
            'no_karyawan'=> 'required|string|max:20|unique:users',
>>>>>>> 73ff3bc34597ed1a4a22d609c381f25eb60395d3
            'role' =>'required|string|max:10',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }


        // Update data user dengan alamat foto
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
        ]);

        

        return response()->json([
            'message'=> 'Registration Success',
            'data'=> $user,
        ]);
    }

    public function Login(Request $request)
     {
        if(!Auth::attempt($request->only('email', 'password'))){
            return response()->json([
                'message'=> 'Your Password or Email is wrong'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        $role = $user->role;

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token'=> $token,
            'token_type'=> 'Bearer',
            'role'=> $role
        ]);
     }


    public function logout(){

        $user = Auth::user();

        if ($user) {
            $user->tokens->each(function ($token, $key){
                $token->delete();
            });

            return response()->json(['message' => 'Logged out successfully']);

        }

        return response()->json(['message' => 'User not found'], 404);
    }

    public function viewLogin(){
        return redirect('/login');
    }

    public function profile(){
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message'=> 'User not found'
            ], 404);
        }

        return response()->json([
            'message'=> 'success',
            'data'=> $user
        ]);
    }

    public function changePassword(Request $request)
    {

        $user = Auth::user();
        $actualPassword = $user->password;
        $currentPassword = $request->currentpassword;
        $password = $request->password;
        $confirmPassword = $request->confirmpassword;

        if (!$user) {
            return response()->json([
                'message'=> 'User not found'
            ], 404);
        }

        if(!Hash::check($currentPassword, $actualPassword)){
            return response()->json([
                'message'=> 'Current password not match'
            ], 400);
        }

        if ($password != $confirmPassword) {
            return response()->json([
                'message'=> 'Password not match'
            ], 400);
        }

        $user->password = bcrypt($password);

        $user->update();

        return response()->json([
            'message'=> 'success change password',
            'data'=> $user
        ]);
    }

}
