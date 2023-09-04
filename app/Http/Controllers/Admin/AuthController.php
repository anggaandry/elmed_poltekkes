<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private $menu_id;

    public function __construct()
    {
        $this->menu_id = 21;
    }
    
    public function index()
    {
        if (Auth::guard('admin')->check()) {
            return redirect('/4dm1n/dashboard');
        }else{
            $university_data=University::where('id',UNIVERSITY_ID)->first();
            $data=['university_data'=>$university_data];
            return view('admin/login',$data);
        }
        
        
    }

    public function login(Request $request)
    {
        $nip = $request->input("nip");
        $password = $request->input("password");

        
        if (Auth::guard('admin')->attempt([
            'nip' => $nip, 
            'password' => $password, 
            'university_id'=>UNIVERSITY_ID,
            'status' => 1
        ])) {
            addLog(0,$this->menu_id,'Login');
            Admin::where(['nip'=>$nip,'university_id'=>UNIVERSITY_ID])->update(['online'=>date('Y-m-d H:i:s')]);
            return redirect()->intended('/4dm1n/dashboard');
            
        } else {
            return redirect()->back()->with('error_login', 'Username atau password salah')->withInput($request->except('password'));
        }
       
    }

    public function profile_change(Request $request)
    {
        $id = $request->input("id");
        $name = $request->input("name");
        $email= $request->input("email");
        $phone= $request->input("phone");
        $birthdate= $request->input("birthdate");
       

        $status_data = Admin::where(['id'=>$id])->update([
            "name"=>$name,
            "email"=>$email,
            "phone"=>$phone,
            "birthdate"=>$birthdate
        ]);

        if ($status_data) {
            addLog(0,$this->menu_id,'Mengupdate profil sendiri');
            return redirect()->back()->with('success', 'sukses mengupdate profil');
        } else {
            return redirect()->back()->with('failed', 'gagal mengupdate profil');
        }
    }

    public function avatar_change(Request $request)
    {
        $id = $request->input("id");
        $avatar=null;
        if ($request->avatar) {
            $avatar = time() . '-avatar.' . $request->avatar->extension();
            $request->avatar->move(public_path(AVATAR_PATH), $avatar);
        }else{
            return redirect()->back()->with('failed', 'Avatar tidak ditemukan');
        }

        $account_data = Admin::where(["id" => $id])->first();
        
        if ($account_data) {
            
            $status_data = Admin::where(['id'=>$id])->update(['avatar'=>$avatar]);
            if ($status_data) {
                if($account_data->avatar){
                    $file_path=AVATAR_PATH.$account_data->avatar;
                    unlink($file_path); 
                }
                
                addLog(0,$this->menu_id,'Mengupdate avatar sendiri');
                return redirect()->back()->with('success', 'Avatar berhasil diganti');
            } else {
                return redirect()->back()->with('failed', 'Avatar gagal di ganti');
            }
        }else{
            return redirect()->back()->with('failed', 'ID akun tidak ditemukan');
        }
    }

    public function password_change(Request $request)
    {
        $id = $request->input("id");
        $old_pass=$request->input("old_password");
        $new_pass=$request->input("new_password");
        $account_data = Admin::where(["id" => $id])->first();
        
        if (Hash::check($old_pass, $account_data->password)) {
            $password=Hash::make($new_pass);
            $status_data = Admin::where(['id'=>$id])->update(['password'=>$password]);
            if ($status_data) {
                return redirect()->back()->with('success', 'password berhasil di ganti');
            } else {
                return redirect()->back()->with('failed', 'password gagal di ganti');
            }
        }else{
            return redirect()->back()->with('failed', 'password gagal di ganti, password lama salah');
        }
    }

    public function online(Request $request)
    {
        $id = $request->input("id");
        $account_data = Admin::where(["id" => $id])->first();


        if (!$account_data) {
            $out = [
                "message" => "Account not found",
            ];
        
        }else{
            $account_data->update(['online'=>date('Y-m-d H:i:s')]);
            $out = [
                "message" => "success",
            ];
        }

        
        return response()->json($out, 200,array(),JSON_PRETTY_PRINT);
       
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        Auth::guard('admin')->logout();
        return redirect('/4dm1n');
    }
}