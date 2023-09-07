<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\Admin;
use App\Models\Colleger;
use App\Models\CollegerClass;
use App\Models\ElearningClass;
use App\Models\ExamClass;
use App\Models\Log;
use App\Models\QuizClass;
use App\Models\Schedule;
use App\Models\ScheduleLecturer;
use App\Models\Semester;
use App\Models\University;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;

class ScheduleController extends Controller
{

    public function index()
    {
        $data=[
            "colleger_data"=>akun('mahasiswa'),
        ];
        return view('mahasiswa/schedule',$data);
    }


    public function ajax_table(Request $request)
    {
        if ($request->ajax()) {
            $class_id = $request->input("class_id");
        

            $data=Schedule::with('sks','sks.subject','class','room')->where("class_id",$class_id)
                            ->orderBy('day','asc')->orderBy('start','asc')->get();
           
            return DataTables::of($data)->addColumn('days', function($row){
                return DAY[$row->day];
              })->addColumn('time', function($row){
                return DAY[$row->day].', '.date('H:i',strtotime($row->start)).' - '.date('H:i',strtotime($row->end));
              })->addColumn('lecturer', function($row){
                $txt="";
                $dosen = ScheduleLecturer::with('lecturer','sls')->where(["schedule_id" => $row->id])->get();
                $txt.="<ul class='text-center'>";
                $i=0;
                foreach ($dosen as $obj) {
                    $i++;
                    
                    $txt.='<li>
                        <span class="mt-5"> '.title_lecturer($obj->lecturer).'</span>
                        <span class="badge badge-xs bg-'.$obj->sls->bg.'" >'.$obj->sls->name.'</span></li>';

                }

                $txt.="</ul>";

                return $txt;
              })->rawColumns(['lecturer'])->make(true);   
        }
    }

   
}