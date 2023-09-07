<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AbsenceStart;
use App\Models\Calendar;
use App\Models\Semester;
use Illuminate\Http\Request;


class CalendarController extends Controller
{
    private $menu_id;

    public function __construct()
    {
        $this->menu_id = 14;
    }

    public function index(Request $request)
    {
        $semester_id= $request->input("semester")? $request->input("semester"):semester_now()->id;
       
        $semester=Semester::where("id",$semester_id)->first();

        $calendar_data=Calendar::orderBy('date','ASC')->get();
        if($semester){
            $calendar_data=Calendar::whereBetween('date',[$semester->start,$semester->end])->orderBy('date','ASC')->get();
        }

        $semester_data=Semester::orderBy("year",'ASC')->get();
        
        $data = [
            "calendar_data" => $calendar_data,
            "semester_data"=>$semester_data,
            "semester_id"=>$semester_id
        ];
        
        return view('admin/calendar', $data);
    }

    public function add(Request $request)
    {
        $name = $request->input("name");
        $date = $request->input("date");
        $off = $request->input("off");
       
        $status_data = Calendar::create([
            "name" => $name,
            "date"=>$date,
            "off"=>$off
        ]);

        if ($status_data) {
            addLog(0,$this->menu_id,'Menambah data kalender '.$name);
            AbsenceStart::where(['date'=>$date,'active'=>0])->delete();
            return redirect()->back()->with('success', tr('berhasil menambah').' '.tr('kalender akademik'));
        } else {
            return redirect()->back()->with('failed', tr('gagal menambah').' '.tr('kalender akademik'));
        }
    }

    public function edit(Request $request)
    {
        $id = $request->input("id");
        $name = $request->input("name");
        $date = $request->input("date");
        $off = $request->input("off");
       
        $update=[
            "name" => $name,
            "date"=>$date,
            "off"=>$off
        ];

        $status_data = Calendar::where(['id'=>$id])->update($update);

        if ($status_data) {
            addLog(0,$this->menu_id,'Mengedit data kalender '.$name);
            AbsenceStart::where(['date'=>$date,'active'=>0])->delete();
            return redirect()->back()->with('success', tr('sukses mengedit').' '.tr('kalender akademik'));
        } else {
            return redirect()->back()->with('failed', tr('gagal mengedit').' '.tr('kalender akademik'));
        }
    }

    public function delete($id)
    {
        $old_data = Calendar::where(["id" => $id])->first();
        $status_data = Calendar::where(["id" => $id])->delete();

        if ($status_data) {
            addLog(0,$this->menu_id,'Menghapus data kalender '.$old_data->name);
            return redirect()->back()->with('success', tr('kalender akademik').' '.tr('berhasil di hapus'));
        } else {
            return redirect()->back()->with('failed', tr('kalender akademik').' '.tr('gagal di hapus'));
        }
    }

}