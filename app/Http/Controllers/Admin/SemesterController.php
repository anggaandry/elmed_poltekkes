<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use Illuminate\Http\Request;


class SemesterController extends Controller
{
    private $menu_id;

    public function __construct()
    {
        $this->menu_id = 7;
    }

    public function index()
    {
        $semester_data=Semester::orderBy('start','ASC')->orderBy('year','ASC')->get();
        
        $data = [
            "semester_data" => $semester_data
        ];
        
        return view('admin/semester', $data);
    }

    public function add(Request $request)
    {
        $odd = $request->input("odd");
        $start = $request->input("start");
        $end = $request->input("end");
        $year = $request->input("year");
        
        $check_semester=Semester::where(['odd'=>$odd,"year"=>$year])->first();
        if(!$check_semester){
            $status_data = Semester::create([
                "odd" => $odd,
                "start"=>$start,
                "end"=>$end,
                "year"=>$year,
            ]);
    
            if ($status_data) {
                addLog(0,$this->menu_id,'Menambah data semester '.($odd==1?"ganjil":"genap")." ".$year);
                return redirect()->back()->with('success', tr('berhasil menambah').' '.tr('data semester'));
            } else {
                return redirect()->back()->with('failed', tr('gagal menambah').' '.tr('data semester'));
            }
        }else{
            return redirect()->back()->with('failed', 'semester '.($odd==1?tr("ganjil"):tr("genap"))." ".tr("sudah ada di tahun")." ".$year);
        }
        
    }

    public function edit(Request $request)
    {
        $id = $request->input("id");
        $start = $request->input("start");
        $end = $request->input("end");
       
        $semester=Semester::where(['id'=>$id])->first();
        $status_data = Semester::where(['id'=>$id])->update([
            "start"=>$start,
            "end"=>$end,
        ]);

        if ($status_data) {
            addLog(0,$this->menu_id,'Mengedit data semester '.($semester->odd==1?"ganjil":"genap")." ".$semester->year);
            return redirect()->back()->with('success', tr('sukses mengedit').' '.tr('data semester'));
        } else {
            return redirect()->back()->with('failed', tr('gagal mengedit').' '.tr('data semester'));
        }
    }

    public function delete($id)
    {
        $old_data = Semester::where(["id" => $id])->first();
        $status_data = Semester::where(["id" => $id])->delete();

        if ($status_data) {
            addLog(0,$this->menu_id,'Menghapus data semester '.($old_data->odd==1?"ganjil":"genap")." ".$old_data->year);
            return redirect()->back()->with('success', tr('data semester').' '.tr('berhasil di hapus'));
        } else {
            return redirect()->back()->with('failed', tr('data semester').' '.tr('gagal di hapus'));
        }
    }

}