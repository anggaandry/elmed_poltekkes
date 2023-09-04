<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\AbsenceStart;
use App\Models\Admin;
use App\Models\Classes;
use App\Models\ElearningClass;
use App\Models\ExamClass;
use App\Models\Lecturer;
use App\Models\LecturerStudyProgram;
use App\Models\Log;
use App\Models\QuizClass;
use App\Models\Schedule;
use App\Models\ScheduleLecturer;
use App\Models\SKS;
use App\Models\StudyProgramFull;
use App\Models\University;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;


class ScheduleController extends Controller
{
    
    public function index()
    {
        $data=[];
        return view('dosen/schedule',$data);
    }

   

    public function ajax_table(Request $request)
    {
        if ($request->ajax()) {
            $year = $request->input("_year");
            $odd = $request->input("_odd");
            $lecturer_id = $request->input("_lecturer");

            $data=Schedule::with('sks','sks.subject','class','room')->whereHas('schedule_lecturer',function ($q) use($lecturer_id) {
                                    $q->where('lecturer_id', '=', $lecturer_id);
                                })
                                ->whereHas('class', function($q) use ($year,$odd){
                                    $q->where(['year'=> $year,'odd'=>$odd]);
                                })->orderBy('day','asc')->orderBy('start','asc')->get();
           
            return DataTables::of($data)->addColumn('days', function($row){
                return DAY[$row->day];
              })->addColumn('time', function($row){
                return date('H:i',strtotime($row->start)).' - '.date('H:i',strtotime($row->end));
              })->addColumn('lecturer', function($row){
                $txt="";
                $dosen = ScheduleLecturer::with('lecturer','sls')->where(["schedule_id" => $row->id])->get();
                $txt.="<ul class='text-center'>";
                $i=0;
                foreach ($dosen as $obj) {
                    $i++;
                    
                    $txt.='<li>
                        <span class="mt-5"> '.title_lecturer($obj->lecturer).'</span>
                        <span class="badge badge-xs bg-'.$obj->sls->bg.'" >'.$obj->sls->name.'</span> </li>';

                }

                $txt.="</ul>";

                return $txt;
              })->rawColumns(['lecturer'])->make(true);   
        }
    }


    

}