<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{


    public function Login(Request $request)
     {
        if(!Auth::attempt($request->only('email', 'password'))){
            return response()->json([
                'message'=> 'Your Password or Email is wrong'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        $role = $user->role;
        $username = $user->nama_lengkap;
        $status = $user->status;

        if($status == 0){
            return response()->json([
                'message'=> 'kamu telah di cekal, hubungi admin untuk informasi lebih lanjut'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token'=> $token,
            'token_type'=> 'Bearer',
            'role'=> $role,
            'username'=> $username
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
