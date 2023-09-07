<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Major;
use Illuminate\Http\Request;


class MajorController extends Controller
{
    private $menu_id;

    public function __construct()
    {
        $this->menu_id = 2;
    }

    public function index()
    {
        $major_data=Major::orderBy('name','ASC')->get();
        
        $data = [
            "major_data" => $major_data
        ];
        return view('admin/major', $data);
    }

    public function add(Request $request)
    {
        $name = $request->input("name");
       
        
        $status_data = Major::create([
            "name" => $name,
           
        ]);

        if ($status_data) {
            addLog(0,$this->menu_id,'Menambah data '.$name);
            return redirect()->back()->with('success', tr('berhasil menambah').' '.tr('jurusan'));
        } else {
            return redirect()->back()->with('failed', tr('gagal menambah').' '.tr('jurusan'));
        }
    }

    public function edit(Request $request)
    {
        $id = $request->input("id");
        $name = $request->input("name");
       

        $update=[
            "name" => $name,
           
        ];

        $status_data = Major::where(['id'=>$id])->update($update);

        if ($status_data) {
            addLog(0,$this->menu_id,'Mengedit data '.$name);
            return redirect()->back()->with('success', tr('sukses mengedit').' '.tr('jurusan'));
        } else {
            return redirect()->back()->with('failed', tr('gagal mengedit').' '.tr('jurusan'));
        }
    }

    public function delete($id)
    {
        $old_data = Major::where(["id" => $id])->first();
        $status_data = Major::where(["id" => $id])->delete();

        if ($status_data) {
            addLog(0,$this->menu_id,'Menghapus data '.$old_data->name);
            return redirect()->back()->with('success', tr('jurusan').' '.tr('berhasil di hapus'));
        } else {
            return redirect()->back()->with('failed', tr('jurusan').' '.tr('gagal di hapus'));
        }
    }

}