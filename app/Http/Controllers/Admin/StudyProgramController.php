<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Major;
use App\Models\StudyProgram;
use Illuminate\Http\Request;


class StudyProgramController extends Controller
{
    private $menu_id;

    public function __construct()
    {
        $this->menu_id = 4;
    }

    public function index()
    {
        $prodi_data=StudyProgram::orderBy('name','ASC')->get();
        $major_data=Major::orderBy('name','ASC')->get();
        
        $data = [
            "prodi_data" => $prodi_data,
            "major_data" => $major_data
        ];
        return view('admin/prodi', $data);
    }

    public function add(Request $request)
    {
        $name = $request->input("name");
        $major_id = $request->input("major_id");
       
        $status_data = StudyProgram::create([
            "name" => $name,
            "major_id"=>$major_id
        ]);

        if ($status_data) {
            addLog(0,$this->menu_id,'Menambah data '.$name);
            return redirect()->back()->with('success', tr('berhasil menambah').' '.tr('program studi'));
        } else {
            return redirect()->back()->with('failed', tr('gagal menambah').' '.tr('program studi'));
        }
    }

    public function edit(Request $request)
    {
        $id = $request->input("id");
        $name = $request->input("name");
        $major_id = $request->input("major_id");

        $update=[
            "name" => $name,
            "major_id"=>$major_id,
        ];

        $status_data = StudyProgram::where(['id'=>$id])->update($update);

        if ($status_data) {
            addLog(0,$this->menu_id,'Mengedit data '.$name);
            return redirect()->back()->with('success', tr('sukses mengedit').' '.tr('program studi'));
        } else {
            return redirect()->back()->with('failed', tr('gagal mengedit').' '.tr('program studi'));
        }
    }

    public function delete($id)
    {
        $old_data = StudyProgram::where(["id" => $id])->first();
        $status_data = StudyProgram::where(["id" => $id])->delete();

        if ($status_data) {
            addLog(0,$this->menu_id,'Menghapus data '.$old_data->name);
            return redirect()->back()->with('success', tr('program studi').' '.tr('berhasil di hapus'));
        } else {
            return redirect()->back()->with('failed', tr('program studi').' '.tr('gagal di hapus'));
        }
    }

}