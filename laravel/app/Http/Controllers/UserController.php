<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Admin\Perawat;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
// auth
use Illuminate\Support\Facades\Auth;
use App\Models\AdminLog;


class UserController extends Controller
{

    public function getUsers(){
        $users = User::all();

        foreach($users as $user){
            if($user->role == 'perawat'){
              $perawatData =   $this->getPerawat($user->id);
              $user->perawat = $perawatData;
            }
        }

        return response()->json([
            'message' => 'Success',
            'data' => $users,
        ]);
    }

    private function getPerawat($id_user){

        $perawat = Perawat::where('id_user', $id_user)->first();

        return $perawat;
    }

    public function detailUser($id){
        $user = User::find($id);

        $perawat = Perawat::where('id_user', $user->id)->first();


        $user->photo = Storage::url('app/'.$user->photo);
        $user->perawat_id = $perawat->id;
        $user->perawat_shift = $perawat->id_waktu_shift;
        $user->perawat_status = $perawat->status;

        return response()->json([
            'message' => 'Success',
            'data' => $user,
        ]);
    }
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
            'role' => 'required|string|max:10',
            'photo' => 'nullable|file|image|mimes:png,jpg,jpeg,svg|max:2048',
            'shift' => 'required_if:role,perawat|nullable',
            'status' => 'required_if:role,perawat|nullable',
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if ($request->hasFile('photo')) {
            $request->file('photo');
            $photo = $request->file('photo');
            $photoPath = $photo->store('post-images');
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
            'role' => $request->role,
            'photo' => $photoPath,
            'shift' =>$request->shift,
            'status'=>$request->status,
        ]);

        if($request->role == 'perawat'){
            $this->perawat($user->id, $request->shift, $request->status);
        }

        // admin log
        AdminLog::create([
            'user_id' => Auth::user()->id,
            'action' => 'Menambah user',
        ]);

        return response()->json([
            'message' => 'Registration Success',
            'data' => $user,
        ]);

    }

    public function updateUser(Request $request, $id){
        $user = User::find($id);

        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|boolean',
            'alamat' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:255',
            'email' => [
                        'required',
                        'string',
                        'email',
                        'max:255',
                        Rule::unique('users')->ignore($user->id)],
            'role' => 'required|string|max:10',
            'shift' => 'required_if:role,perawat',
            'status' => 'required_if:role,perawat',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('local')->delete($user->photo);
            }
            $request->file('photo');
            $photo = $request->file('photo');
            $photoPath = $photo->store('post-images');
        }else{
            $photoPath = null;
        }

        // Update data user dengan alamat foto
        $user->update([
            'nama_lengkap' => $request->nama_lengkap,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'no_telepon' => $request->no_telepon,
            'email' => $request->email,
            'role' => $request->role,
            'photo' => $photoPath,
            'status' => $request->status,
        ]);

        if($request->role == 'perawat'){
            $this->updatePerawat($user->id, $request->shift, $request->status);
        }


        // admin log
        AdminLog::create([
            'user_id' => Auth::user()->id,
            'action' => 'Mengupdate user',
        ]);

        return response()->json([
            'message' => 'Success',
            'data' => $user,
        ]);
    }

    public function delete($id){
        $user = User::find($id);

        // delete image user
        if($user->photo != null){
           $photoName = storage_path('/app/'. $user->photo);

           if(file_exists($photoName)){
               try{
                   unlink($photoName);
               }catch(\Exception $e){
                     return response()->json([
                          'message' => 'Delete Photo Failed',
                          'data' => $user,
                          'error'=> $e->getMessage(),
                     ]);
               }
           }
        }

        if($user->role == 'perawat'){
            $this->deletePerawat($user->id);
        }

        $user->delete();

        // admin log
        AdminLog::create([
            'user_id' => Auth::user()->id,
            'action' => 'Menghapus user',
        ]);

        return response()->json([
            'message' => 'Delete Success'
        ]);
    }


    private function perawat($id, $shift, $status){
        $user = User::find($id);
        $perawat = new Perawat();

        $perawat->id_user = $user->id;
        $perawat->id_waktu_shift = $shift;
        $perawat->status = $status;

        $perawat->save();

        return response()->json([
            'message' => 'Update Success',
            'data' => $user,
        ]);
    }

    private function deletePerawat($id_user){

        $user = User::find($id_user);


        $perawat = Perawat::where('id_user', $user->id)->first();

        $perawat->delete();

        return response()->json([
            'message' => 'Delete Success'
        ]);
    }

    private function updatePerawat($id_user, $shift, $status){
        $user = User::find($id_user);

        $perawat = Perawat::where('id_user', $user->id)->first();

        $perawat->id_waktu_shift = $shift;
        $perawat->status = $status;

        $perawat->update();
    }
}
