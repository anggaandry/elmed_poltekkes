<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Major;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Datatables;

class AdminController extends Controller
{
    private $menu_id;
    private $key_;

    public function __construct()
    {
        $this->menu_id = 21;
        $this->key_ = 'Admin';
    }

    public function index()
    {
        $where=[];
        if(can_prodi()){
            $where=["prodi_id"=>can_prodi()];
        }

        $role_data=Role::where($where)->orderBy('name','ASC')->get();
        
        $data = [
            
            "role_data"=>$role_data
        ];
        return view('admin/account_admin', $data);
    }

    public function ajax_table(Request $request)
    {
        if ($request->ajax()) {
            $data=Admin::with('role')->orderBy('created_at','desc')->get();
            if(can_prodi()){
                $data=Admin::with('role')->whereHas('role', function($q){
                    $q->where(['prodi_id'=> can_prodi()]);
                })->orderBy('created_at','desc')->get();
            }
           
                
            return DataTables::of($data)->addIndexColumn()
                ->editColumn('online', function($row){
                    return $row->online?date_id($row->online,4):"-";
                })
                ->addColumn('status_view', function($row){
                    return $row->status?'<span class="badge bg-success">active</span>':'<span class="badge bg-danger">unactive</span>';
                })
                ->addColumn('role_view', function($row){
                    return '<span class="badge bg-primary">'.$row->role->name.'</span>';
                })
                ->addColumn('avatar', function($row){
                    $img=$row->avatar ? asset(AVATAR_PATH . $row->avatar) : 'https://ui-avatars.com/api/?background=89CFF0&name=' . str_replace(' ', '+', $row->name);
                    $ava = '<div class="cropcircle"
                                style="
                                background-image: url(\''.$img.'\');
                            ">';
                    return $ava;
                })
                ->addColumn('action', function($row){
                    $edit="";
                    $avatar="";
                    $respass="";
                    $active="";
                    $delete="";
                    
                    if(can($this->key_,'edit')){
                        $active='<a class="dropdown-item text-success" href="javascript:void()" onclick="show_active('.$row->id.',\''.$row->name.'\')">Aktifkan akun</a>';
                        if($row->status==1){
                            $active='<a class="dropdown-item text-danger" href="javascript:void()" onclick="show_disactive('.$row->id.',\''.$row->name.'\')">Non-aktifkan akun</a>';
                        }
                        $edit='<a class="dropdown-item" href="javascript:void()" onclick="show_edit('.$row->id.')">Edit</a>';
                        $avatar='<a class="dropdown-item" href="javascript:void()" onclick="show_avatar('.$row->id.')">Ganti avatar</a>';
                        $respass='<a class="dropdown-item text-danger" href="javascript:void()" onclick="show_respass('.$row->id.',\''.$row->name.'\')">Reset password</a>';
                    }

                    if(can($this->key_,'delete')){
                        $delete='<a class="dropdown-item text-danger" href="javascript:void()" onclick="show_delete('.$row->id.',\''.$row->name.'\')">Delete</a>';
                    }
                    
                    

                    $action='<div class="btn-group" role="group">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">Aksi</button>
                                <div class="dropdown-menu">
                                    '.$edit.'
                                    '.$avatar.'
                                    '.$respass.'
                                    '.$active.'
                                    '.$delete.'
                                </div>
                            </div>';

                    return $action;
                })
                ->rawColumns(['avatar','role_view','status_view','action'])
                ->make(true);
        }
    }

    public function ajax_id(Request $request)
    {
        $id = $request->input("id");
        $data = Admin::where(["id" => $id])->first();
        $data->avatar=$data->avatar?asset(AVATAR_PATH.$data->avatar):"";

        if (!$data) {
            $out = [
                "message" => "ID not found",
                "result"=>[],
            ];
        }else{
            $out = [
                "message" => "success",
                "result"=>$data
            ];
        }
        
        return response()->json($out, 200,array(),JSON_PRETTY_PRINT);
    }

    public function add(Request $request)
    {
        $name = $request->input("name");
        $role_id = $request->input("role_id");
        $nip = $request->input("nip");
        $email= $request->input("email");
        $phone= $request->input("phone");
        $birthdate= $request->input("birthdate");
        $password= Hash::make(date("dmY",strtotime($birthdate)));

        $data=[
            "name"=>$name,
            "role_id"=>$role_id,
            "nip"=>$nip,
            "birthdate"=>$birthdate,
            "email"=>$email,
            "phone"=>$phone,
            "password"=>$password
        ];

        $avatar=null;
        if ($request->avatar) {
            $avatar = time() . '-avatar.' . $request->avatar->extension();
            $request->avatar->move(public_path(AVATAR_PATH), $avatar);
        }

        if($request->avatar){
            $data['avatar']=$avatar;
        }
       
        $status_data = Admin::create($data);

        if ($status_data) {
            addLog(0,$this->menu_id,'Menambah data '.$name);
            return redirect()->back()->with('success', 'berhasil menambah akun');
        } else {
            return redirect()->back()->with('failed', 'gagal menambah akun');
        }
    }

    public function edit(Request $request)
    {
        $id = $request->input("id");
        $name = $request->input("name");
        $role_id = $request->input("role_id");
        $nip = $request->input("nip");
        $email= $request->input("email");
        $phone= $request->input("phone");
        $birthdate= $request->input("birthdate");
       

        $status_data = Admin::where(['id'=>$id])->update([
            "name"=>$name,
            "role_id"=>$role_id,
            "nip"=>$nip,
            "email"=>$email,
            "phone"=>$phone,
            "birthdate"=>$birthdate
        ]);

        if ($status_data) {
            addLog(0,$this->menu_id,'Mengedit data '.$name);
            return redirect()->back()->with('success', 'sukses mengedit data akun');
        } else {
            return redirect()->back()->with('failed', 'gagal mengedit data akun');
        }
    }

    public function password_reset(Request $request)
    {
        $id = $request->input("id");
        $account_data = Admin::where(["id" => $id])->first();
        
        if ($account_data) {
            $password= Hash::make(date("dmY",strtotime($account_data->birthdate)));
            $status_data = Admin::where(['id'=>$id])->update(['password'=>$password]);
            if ($status_data) {
                addLog(0,$this->menu_id,'Mereset password '.$account_data->name);
                return redirect()->back()->with('success', 'password berhasil di reset');
            } else {
                return redirect()->back()->with('failed', 'password gagal di reset');
            }
        }else{
            return redirect()->back()->with('failed', 'ID akun tidak ditemukan');
        }
    }

    public function status(Request $request)
    {
        $id = $request->input("id");
        $status=$request->input("status");
        $account_data = Admin::where(["id" => $id])->first();
        
        if ($account_data) {
            $status_data = Admin::where(['id'=>$id])->update(['status'=>$status]);
            if ($status_data) {
                addLog(0,$this->menu_id,($status==1?'Mengaktifkan akun ':'Menon-aktifkan akun').$account_data->name);
                return redirect()->back()->with('success', 'Akun berhasil di '.($status==1?' aktifkan':' non-aktifkan'));
            } else {
                return redirect()->back()->with('failed', 'Akun gagal di '.($status==1?' aktifkan':' non-aktifkan'));
            }
        }else{
            return redirect()->back()->with('failed', 'ID akun tidak ditemukan');
        }
    }

    public function avatar_update(Request $request)
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
                
                addLog(0,$this->menu_id,'Mengganti avatar '.$account_data->name);
                return redirect()->back()->with('success', 'Avatar berhasil diganti');
            } else {
                return redirect()->back()->with('failed', 'Avatar gagal di ganti');
            }
        }else{
            return redirect()->back()->with('failed', 'ID akun tidak ditemukan');
        }
    }

    public function delete($id)
    {
        $old_data = Admin::where(["id" => $id])->first();
        $status_data = Admin::where(["id" => $id])->delete();

        if ($status_data) {
            if($old_data->avatar){
                $file_path=AVATAR_PATH.$old_data->avatar;
                unlink($file_path); 
            }

            addLog(0,$this->menu_id,'Menghapus data '.$old_data->name);
            return redirect()->back()->with('success', 'Akun berhasil di hapus');
        } else {
            return redirect()->back()->with('failed', 'Akun gagal di hapus');
        }
    }

}