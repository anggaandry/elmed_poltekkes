<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudyProgramCategory;
use Illuminate\Http\Request;


class SPCController extends Controller
{
    private $menu_id;

    public function __construct()
    {
        $this->menu_id = 3;
    }

    public function index()
    {
        $spc_data=StudyProgramCategory::orderBy('name','ASC')->get();
        
        $data = [
            "spc_data" => $spc_data
        ];
        return view('admin/spc', $data);
    }

    public function add(Request $request)
    {
        $name = $request->input("name");
       
        
        $status_data = StudyProgramCategory::create([
            "name" => $name,
           
        ]);

        if ($status_data) {
            addLog(0,$this->menu_id,'Menambah data '.$name);
            return redirect()->back()->with('success', tr('berhasil menambah').' '.tr('kategori prodi'));
        } else {
            return redirect()->back()->with('failed', tr('gagal menambah').' '.tr('kategori prodi'));
        }
    }

    public function edit(Request $request)
    {
        $id = $request->input("id");
        $name = $request->input("name");
       

        $update=[
            "name" => $name,
           
        ];

        $status_data = StudyProgramCategory::where(['id'=>$id])->update($update);

        if ($status_data) {
            addLog(0,$this->menu_id,'Mengedit data '.$name);
            return redirect()->back()->with('success', tr('sukses mengedit').' '.tr('kategori prodi'));
        } else {
            return redirect()->back()->with('failed', tr('gagal mengedit').' '.tr('kategori prodi'));
        }
    }

    public function delete($id)
    {
        $old_data = StudyProgramCategory::where(["id" => $id])->first();
        $status_data = StudyProgramCategory::where(["id" => $id])->delete();

        if ($status_data) {
            addLog(0,$this->menu_id,'Menghapus data '.$old_data->name);
            return redirect()->back()->with('success', tr('kategori prodi').' '.tr('berhasil di hapus'));
        } else {
            return redirect()->back()->with('failed', tr('kategori prodi').' '.tr('gagal di hapus'));
        }
    }

}