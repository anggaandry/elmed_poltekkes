<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Major;
use App\Models\Menu;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\StudyProgram;
use App\Models\StudyProgramFull;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    private $menu_id;

    public function __construct()
    {
        $this->menu_id = 20;
    }

    public function index()
    {
        $role=Role::orderBy('name','ASC')->get();
        $role_data=[];
        foreach ($role as $item) {
            $item->total_user=Admin::where('role_id',$item->id)->count();
            $item->total_allow=RolePermission::where(['role_id'=>$item->id,'view_access'=>1])->count().'/'.Menu::count();
            array_push($role_data,$item);
        }
        $data = [
            "role_data" => $role_data
        ];
        return view('admin/role', $data);
    }

    public function add_view()
    {
        $spc_data=StudyProgramFull::orderBy('program_id','ASC')->get();
        $menu=Menu::orderBy('sort','ASC')->get();

        $menu_data=[];
        foreach ($menu as $item) {
            array_push($menu_data,$item);
        }

        $data = [
            "spc_data"=>$spc_data,
            "menu_data"=>$menu_data
        ];

        return view('admin/role_add', $data);
    }


    public function add(Request $request)
    {
        $name = $request->input("name");
        $prodi_id = $request->input("prodi_id");
        
        $status_data = Role::create([
            "name" => $name,
            "prodi_id"=>$prodi_id
        ]);

        if ($status_data) {
            $menu=Menu::orderBy('sort','ASC')->get();
            foreach ($menu as $item) {
                $data=[
                    "role_id"=>$status_data->id,
                    "menu_id"=>$item->id,
                    "view_access"=>0,
                    "add_access"=>0,
                    "edit_access"=>0,
                    "delete_access"=>0,
                ];

                if($item->has_view){
                    if($request->input("cbView".$item->id)){
                        $data["view_access"]=1;
                    }
                }

                if($item->has_add){
                    if($request->input("cbAdd".$item->id)){
                        $data["add_access"]=1;
                    }
                }

                if($item->has_edit){
                    if($request->input("cbEdit".$item->id)){
                        $data["edit_access"]=1;
                    }
                }

                if($item->has_delete){
                    if($request->input("cbDelete".$item->id)){
                        $data["delete_access"]=1;
                    }
                }

                RolePermission::create($data);
            }
            
            addLog(0,$this->menu_id,'Menambah data '.$name);
            return redirect('4dm1n/role')->with('success', 'berhasil menambah role');
        } else {
            return redirect()->back()->with('failed', 'gagal menambah role');
        }
    }

    public function edit_view(Request $request)
    {
        $id = $request->input("id");
        $role_data = Role::where(["id" => $id])->first();
        $spc_data=StudyProgramFull::orderBy('program_id','ASC')->get();

        $menu=Menu::orderBy('sort','ASC')->get();

        $menu_data=[];
        foreach ($menu as $item) {
            $item->access=RolePermission::where(['role_id'=>$role_data->id,'menu_id'=>$item->id])->first();
            array_push($menu_data,$item);
        }
        
        $data = [
            "role_data"=>$role_data,
            "menu_data"=>$menu_data,
            "spc_data"=>$spc_data
        ];
        return view('admin/role_edit', $data);
    }

    public function edit(Request $request)
    {
        $id = $request->input("id");
        $name = $request->input("name");
        $prodi_id = $request->input("prodi_id");

        $update=[
            "name" => $name,
            "prodi_id"=>$prodi_id
        ];

        $status_data = Role::where(['id'=>$id])->update($update);

        if ($status_data) {
            $menu=Menu::orderBy('sort','ASC')->get();
            foreach ($menu as $item) {
                $data=[
                    "role_id"=>$id,
                    "menu_id"=>$item->id,
                    "view_access"=>0,
                    "add_access"=>0,
                    "edit_access"=>0,
                    "delete_access"=>0,
                ];

                if($item->has_view){
                    if($request->input("cbView".$item->id)){
                        $data["view_access"]=1;
                    }
                }

                if($item->has_add){
                    if($request->input("cbAdd".$item->id)){
                        $data["add_access"]=1;
                    }
                }

                if($item->has_edit){
                    if($request->input("cbEdit".$item->id)){
                        $data["edit_access"]=1;
                    }
                }

                if($item->has_delete){
                    if($request->input("cbDelete".$item->id)){
                        $data["delete_access"]=1;
                    }
                }

                $check=RolePermission::where(['role_id'=>$id,'menu_id'=>$item->id])->first();
                if($check){
                    RolePermission::where(['role_id'=>$id,'menu_id'=>$item->id])->update($data);
                }else{
                    RolePermission::create($data);
                }
            }
            
            addLog(0,$this->menu_id,'Mengedit data '.$name);
            return redirect('4dm1n/role')->with('success', 'sukses mengedit role');
        } else {
            return redirect()->back()->with('failed', 'gagal mengedit role');
        }
    }

  

    public function delete($id)
    {
        $old_data = Role::where(["id" => $id])->first();
        $status_data = Role::where(["id" => $id])->delete();

        if ($status_data) {
            addLog(0,$this->menu_id,'Menghapus data '.$old_data->name);
            return redirect()->back()->with('success', 'Role berhasil di hapus');
        } else {
            return redirect()->back()->with('failed', 'Role gagal di hapus');
        }
    }

    

}