<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\AbsenceStart;
use App\Models\Admin;
use App\Models\Calendar;
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


class DashboardController extends Controller
{
    private $now_hour;
    private $now_date;
    
    public function __construct()
    {
        $this->now_hour = date('Y-m-d H:i:s');
        $this->now_date = date('Y-m-d');
    }

    public function index()
    {
        $schedule_data=[];
        $schedule=Schedule::where(['day'=>date('w')])->whereHas('schedule_lecturer',function ($q) {
                                $q->where('lecturer_id', '=', akun('dosen')->id);
                            })
                            ->whereHas('class', function($q) {
                                $q->where(['year'=> semester_now()->year,'odd'=>semester_now()->odd]);
                            })->orderBy('start','asc')->get();
        
        foreach ($schedule as $item) {
            $item->moved=false;
            array_push($schedule_data,$item);
        }

        $schedule_move=AbsenceStart::where("date",date('Y-m-d'))->where('moved_from',"!=",null)->get();
        foreach($schedule_move as $obj){
            $schedule_lec=ScheduleLecturer::where(['schedule_id'=>$obj->schedule_id,'lecturer_id'=>akun('dosen')->id])->first();
            if($schedule_lec){
                $item=$obj->schedule;
                $item->moved=true;
                $item->start=$obj->start;
                $item->end=$obj->end;
                array_push($schedule_data,$item);
            }
            
        }

        usort($schedule_data, function($a, $b)
        {
            return strcmp($a->start, $b->start);
        });

        // /return '<pre>'.json_encode($schedule_data,JSON_PRETTY_PRINT).'</pre>';

        $elearning=ElearningClass::where('start','<=',$this->now_hour)->where('end','>=',$this->now_hour)
                                    ->whereHas('elearning', function($q) {
                                        $q->where(['lecturer_id'=> akun('dosen')->id]);
                                    })->get();

        $quiz=QuizClass::where('start','<=',$this->now_hour)->where('publish','=',0)
                        ->whereHas('quiz', function($q) {
                            $q->where(['lecturer_id'=> akun('dosen')->id]);
                        })->get();
       
        
        $exam=ExamClass::where('start','<=',$this->now_hour)->where('publish','=',0)
                        ->whereHas('exam', function($q) {
                            $q->where(['lecturer_id'=> akun('dosen')->id]);
                        })->get();
                        
        $event=Calendar::where('date',date('Y-m-d'))->first();
        if($event){
            $holiday=$event->name;
            $schedule_data=[];
        }else{
            $holiday="Libur jadwal kosong";
            if(date('w')==0){
                $holiday="Libur hari minggu";
            }
        }
                        
        $data=[
            "schedule"=>$schedule_data,
            "elearning"=>$elearning,
            "quiz"=>$quiz,
            "exam"=>$exam,
            "holiday_name"=>$holiday,
        ];
        return view('dosen/dashboard',$data);
    }

    public function profile(Request $request)
    {
        $id = akun('dosen')->id;
        $tab = $request->input("tab")?$request->input("tab"):0;
        $lecturer_data=Lecturer::where('id',$id)->first();
        $last_activity=Log::where('lecturer_id',$id)->orderBy('created_at','DESC')->first();
        
        $lsp_data=LecturerStudyProgram::where('lecturer_id',$id)->get();
        $in_prodi=[];
        foreach($lsp_data as $item){
            array_push($in_prodi,$item->prodi_id);
        }

        $subject_data=[];
        $sks_data=SKS::whereIn('prodi_id',$in_prodi)->where('status',1)->orderBy('semester','ASC')->get();
        foreach($sks_data as $item){
            $check_schedule=Schedule::where(['sks_id'=>$item->id])->whereHas('schedule_lecturer',function ($q) use($id) {
                                            $q->where('lecturer_id', '=', $id);
                                        })->first();
                                        
            if($check_schedule){
                array_push($subject_data,$item);
            }
        }

        $data = [
            "tab"=>$tab,
            "lecturer_data"=>$lecturer_data,
            "last_activity"=>$last_activity,
            "prodi_dosen"=>$lsp_data,
            "subject_data"=>$subject_data
        ];
        
        return view('dosen/profile', $data);
    }


    public function ajax_class(Request $request)
    {
        if ($request->ajax()) {
            $year = $request->input("_year");
            $odd = $request->input("_odd");
            $lecturer_id = $request->input("_lecturer");

            $data=[];
            $class_da=Classes::where(['year'=>$year,'odd'=>$odd])->orderBy('name','asc')->get();
            foreach($class_da as $item){
                $check_schedule=Schedule::where(['class_id'=>$item->id])->whereHas('schedule_lecturer',function ($q) use($lecturer_id) {
                                                                            $q->where('lecturer_id', '=', $lecturer_id);
                                                                        })->first();
                if($check_schedule){
                    array_push($data,$item);
                }
            }
                
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('kelas', function($row){
                      $name=  $row->name."<br><small> Prodi ".$row->prodi->program->name.' - '.$row->prodi->study_program->name.' '.$row->prodi->category->name."</small>";
                      return $name;
              })->rawColumns(['kelas'])->make(true);   
        }
    }
}