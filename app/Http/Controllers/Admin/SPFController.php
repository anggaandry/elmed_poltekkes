<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\StudyProgram;
use App\Models\StudyProgramCategory;
use App\Models\StudyProgramFull;
use Illuminate\Http\Request;


class SPFController extends Controller
{
    private $menu_id;

    public function __construct()
    {
        $this->menu_id = 5;
    }

    public function index()
    {
        $spf_data=StudyProgramFull::orderBy('study_program_id','ASC')->get();
        $prodi_data=StudyProgram::orderBy('name','ASC')->get();
        $spc_data=StudyProgramCategory::orderBy('name','ASC')->get();
        $program_data=Program::orderBy('id','ASC')->get();
        
        $data = [
            "spf_data" => $spf_data,
            "spc_data" => $spc_data,
            "prodi_data" => $prodi_data,
            "program_data"=>$program_data
        ];
        return view('admin/spf', $data);
    }

    public function add(Request $request)
    {
        $study_program_id = $request->input("study_program_id");
        $program_id = $request->input("program_id");
        $category_id = $request->input("category_id");
        $prodi_data=StudyProgram::where('id',$study_program_id)->first();
         $lang = $request->input("lang");
        
       
        $status_data = StudyProgramFull::create([
            "lang"=>$lang,
            "study_program_id"=>$study_program_id,
            "program_id"=>$program_id,
            "category_id"=>$category_id
        ]);

        if ($status_data) {
            addLog(0,$this->menu_id,'Menambah data '.$prodi_data->name);
            return redirect()->back()->with('success', tr('berhasil menambah').' '.tr('prodi lengkap'));
        } else {
            return redirect()->back()->with('failed', tr('gagal menambah').' '.tr('prodi lengkap'));
        }
    }

    public function edit(Request $request)
    {
        $id = $request->input("id");
        $study_program_id = $request->input("study_program_id");
        $program_id = $request->input("program_id");
        $category_id = $request->input("category_id");
        $prodi_data=StudyProgram::where('id',$study_program_id)->first();
        $lang = $request->input("lang");

        $update=[
            "lang"=>$lang,
            "study_program_id"=>$study_program_id,
            "program_id"=>$program_id,
            "category_id"=>$category_id
        ];

        $status_data = StudyProgramFull::where(['id'=>$id])->update($update);

        if ($status_data) {
            addLog(0,$this->menu_id,'Mengedit data '.$prodi_data->name);
            return redirect()->back()->with('success', tr('sukses mengedit').' '.tr('prodi lengkap'));
        } else {
            return redirect()->back()->with('failed', tr('gagal mengedit').' '.tr('prodi lengkap'));
        }
    }

    public function delete($id)
    {
        $old_data = StudyProgramFull::where(["id" => $id])->first();
        $status_data = StudyProgramFull::where(["id" => $id])->delete();

        if ($status_data) {
            addLog(0,$this->menu_id,'Menghapus data '.$old_data->study_program->name);
            return redirect()->back()->with('success', tr('prodi lengkap').' '.tr('berhasil di hapus'));
        } else {
            return redirect()->back()->with('failed', tr('prodi lengkap').' '.tr('gagal di hapus'));
        }
    }

}