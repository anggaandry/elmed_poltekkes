<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Colleger;
use App\Models\Lecturer;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{   
    private $menu_id;

    public function __construct()
    {
        $this->menu_id = 23;
    }
    
    public function index()
    {
        if (Auth::guard('mahasiswa')->check()) {
            return redirect('/mahasiswa/dashboard');
        }else{
            $university_data=University::where('id',UNIVERSITY_ID)->first();
            $data=['university_data'=>$university_data];
            return view('mahasiswa/login',$data);
        }
    }

    public function login(Request $request)
    {
        $nim = $request->input("nim");
        $password = $request->input("password");

        
        if (Auth::guard('mahasiswa')->attempt([
            'nim' => $nim, 
            'password' => $password, 
            'university_id'=>UNIVERSITY_ID,
            'status' => 1
        ])) {
            $dt=Colleger::where(['nim'=>$nim,'university_id'=>UNIVERSITY_ID])->first();
            App::setLocale($dt->lang);
            session()->put('locale',$dt->lang);

            addLog(2,$this->menu_id,'Login');
            Colleger::where(['nim'=>$nim,'university_id'=>UNIVERSITY_ID])->update(['online'=>date('Y-m-d H:i:s')]);
            return redirect()->intended('/mahasiswa/dashboard');
            
        } else {
            return redirect()->back()->with('error_login', tr('username atau password salah'))->withInput($request->except('password'));
        }
       
    }

   

    public function online(Request $request)
    {
        $id = $request->input("id");
        $account_data = Colleger::where(["id" => $id])->first();


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

    public function password_change(Request $request)
    {
        $id = $request->input("id");
        $old_pass=$request->input("old_password");
        $new_pass=$request->input("new_password");
        $account_data = Colleger::where(["id" => $id])->first();
        
        if (Hash::check($old_pass, $account_data->password)) {
            $password=Hash::make($new_pass);
            $status_data =Colleger::where(['id'=>$id])->update(['password'=>$password]);
            if ($status_data) {
                return redirect()->back()->with('success', tr('password berhasil di ganti'));
            } else {
                return redirect()->back()->with('failed', tr('password gagal di ganti'));
            }
        }else{
            return redirect()->back()->with('failed', tr('password gagal di ganti, password lama salah'));
        }
    }

    public function lang_change(Request $request)
    {
        $id = $request->input("id");
        $lang=$request->input("lang");
         
        $status_data =Colleger::where(['id'=>$id])->update(['lang'=>$lang]);
        if ($status_data) {
            App::setLocale($lang);
            session()->put('locale',$lang);
            return redirect()->back()->with('success', tr('bahasa berhasil di ganti'));
        } else {
            return redirect()->back()->with('failed', tr('bahasa gagal di ganti'));
        }
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        Auth::guard('mahasiswa')->logout();
        return redirect('/mahasiswa');
    }
}